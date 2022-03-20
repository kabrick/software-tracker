<?php

namespace App\Http\Controllers;

use App\Models\ProjectVersionFeature;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectVersionFeaturesController extends Controller {

    public function view_features($version_id) {
        $chunked_features = ProjectVersionFeature::where('project_version_features.version_id', $version_id)
            ->leftJoin('users', 'users.id', '=', 'project_version_features.updated_by')
            ->leftJoin('project_version_modules', 'project_version_modules.id', '=', 'project_version_features.module_id')
            ->orderBy('project_version_features.updated_at', 'desc')
            ->get(['project_version_features.id', 'project_version_features.title', 'project_version_features.is_published',
                'users.name as username', 'project_version_features.updated_at', 'project_version_modules.title as module_title'])
            ->chunk(4);

        $users = DB::table('users')->whereNull('deleted_at')->pluck('name', 'id')->toArray();
        $users = [0 => "--select--"] + $users;

        $modules = DB::table('project_version_modules')->whereNull('deleted_at')->pluck('title', 'id')->toArray();
        $modules = [0 => "--select--"] + $modules;

        return view('project_version_features.view_features', compact('version_id', 'chunked_features', 'users', 'modules'));
    }

    public function search_features(Request $request) {
        $version_id = $request->version_id;
        $filters = [];

        if ($request->user_id) {
            $filters[] = ['project_version_features.created_by', '=', $request->user_id];
        }

        if ($request->module_id) {
            $filters[] = ['project_version_features.module_id', '=', $request->module_id];
        }

        if ($request->date_search == 0) {
            $filters[] = ['project_version_features.created_at', '>', now()->startOfDay()];
            $filters[] = ['project_version_features.created_at', '<', now()->endOfDay()];
        } elseif ($request->date_search == 1) {
            $filters[] = ['project_version_features.created_at', '>', Carbon::yesterday()->startOfDay()];
            $filters[] = ['project_version_features.created_at', '<', Carbon::yesterday()->endOfDay()];
        } elseif ($request->date_search == 2) {
            $filters[] = ['project_version_features.created_at', '>', Carbon::parse($request->on_date)->startOfDay()];
            $filters[] = ['project_version_features.created_at', '<', Carbon::parse($request->on_date)->endOfDay()];
        } elseif ($request->date_search == 3) {
            $filters[] = ['project_version_features.created_at', '>', Carbon::parse($request->start_date)->startOfDay()];
            $filters[] = ['project_version_features.created_at', '<', Carbon::parse($request->end_date)->endOfDay()];
        }

        $chunked_features = ProjectVersionFeature::where('project_version_features.version_id', $version_id)
            ->where($filters)
            ->leftJoin('users', 'users.id', '=', 'project_version_features.updated_by')
            ->leftJoin('project_version_modules', 'project_version_modules.id', '=', 'project_version_features.module_id')
            ->orderBy('project_version_features.updated_at', 'desc')
            ->get(['project_version_features.id', 'project_version_features.title', 'project_version_features.is_published',
                'users.name as username', 'project_version_features.updated_at', 'project_version_modules.title as module_title'])
            ->chunk(4);

        $users = DB::table('users')->whereNull('deleted_at')->pluck('name', 'id')->toArray();
        $users = [0 => "--select--"] + $users;

        $modules = DB::table('project_version_modules')->whereNull('deleted_at')->pluck('title', 'id')->toArray();
        $modules = [0 => "--select--"] + $modules;

        return view('project_version_features.view_features', compact('version_id', 'chunked_features', 'users', 'modules'));
    }

    public function create_feature($parent_id) {
        $version_id = get_name($parent_id, 'id', 'version_id', 'project_version_modules');

        return view('project_version_features.create_feature', compact('parent_id', 'version_id'));
    }

    public function store_feature(Request $request) {
        if (is_null($request->description) || $request->description == '') {
            flash('Please make sure to add a description before submitting')->error();
            return back()->withInput();
        }

        $feature = new ProjectVersionFeature();
        $feature->version_id = get_name($request->module_id, 'id', 'version_id', 'project_version_modules');
        $feature->module_id = $request->module_id;
        $feature->title = $request->title;
        $feature->description = $request->description;
        $feature->print_order = 0;
        $feature->type = 0;
        $feature->created_by = Auth::user()->id;
        $feature->updated_by = Auth::user()->id;

        $feature->save();

        flash("Feature has been created successfully")->success();
        return redirect('/project_version_features/feature_details/' . $feature->id);
    }

    public function feature_details($id) {
        $feature = ProjectVersionFeature::find($id);

        return view('project_version_features.feature_details', compact('id', 'feature'));
    }

    public function archive($id) {
        $feature = ProjectVersionFeature::find($id);

        if ($feature->delete()) {
            flash("Project version feature has been archived")->success();
            return redirect('/project_versions/' . $feature->version_id);
        } else {
            flash("An error occurred. Project version feature was not archived")->error();
            return back()->withInput();
        }
    }

    public function delete($id) {
        $feature = ProjectVersionFeature::find($id);

        if ($feature->forceDelete()) {
            flash("Project version feature has been deleted")->success();
            return redirect('/project_versions/' . $feature->version_id);
        } else {
            flash("An error occurred. Project version feature was not deleted")->error();
            return back()->withInput();
        }
    }

    public function view_archived() {
        $features = ProjectVersionFeature::onlyTrashed()->get();

        return view('project_version_features.view_archived', compact('features'));
    }

    public function restore($id) {
        $feature = ProjectVersionFeature::withTrashed()->find($id);

        if($feature->restore()){
            flash("Project version feature has been restored")->success();
            return redirect('project_version_features/feature_details/' . $feature->id);
        } else {
            flash("An error occurred. Project version feature was not restored")->error();
            return back()->withInput();
        }
    }

    public function edit($id) {
        $feature = ProjectVersionFeature::find($id);

        return view('project_version_features.edit', compact('id', 'feature'));
    }

    public function update(Request $request) {
        $id = $request->id;

        if (is_null($request->description) || $request->description == '') {
            flash('Please make sure to add a description before submitting')->error();
            return back()->withInput();
        }

        $feature = ProjectVersionFeature::find($id);
        $feature->version_id = $request->version_id;
        $feature->module_id = $request->module_id;
        $feature->title = $request->title;
        $feature->description = $request->description;
        $feature->updated_by = Auth::user()->id;

        $feature->save();

        flash("Feature has been updated successfully")->success();
        return redirect('project_version_features/feature_details/' . $id);
    }

    public function publish($id) {
        $feature = ProjectVersionFeature::find($id);
        $feature->is_published = 1;
        $feature->updated_by = Auth::user()->id;

        if ($feature->save()) {
            flash("Feature has been published successfully")->success();
        } else {
            flash("An error occurred. Please try again!")->success();
        }

        return redirect('project_version_features/feature_details/' . $id);
    }

    public function unpublish($id) {
        $feature = ProjectVersionFeature::find($id);
        $feature->is_published = 0;
        $feature->updated_by = Auth::user()->id;

        if ($feature->save()) {
            flash("Feature has been un-published successfully")->success();
        } else {
            flash("An error occurred. Please try again!")->success();
        }

        return redirect('project_version_features/feature_details/' . $id);
    }

    public function generate_feature_pdf($feature_id) {
        $feature = ProjectVersionFeature::find($feature_id);

        $title = $feature->title;

        $html = "<h3>$feature->title</h3>";
        $html .= "<br>$feature->description<br><br>";
        $html .= "<hr>";

        if ($feature->type == 1) {
            $steps = $feature->steps()->get(); $counter = 1;
            $html .= "<br><br>";

            foreach($steps as $step) {
                $html .= "<h3>Step " . $counter . "</h3><p>" . $step->description . "</p>";
                $html .= "<img src='" . asset($step->images) . "' width='600' height='250' alt='image'>";
                $html .= "<br><br>";

                $counter++;
            }
        }

        $data = [
            'html' => $html,
            'title' => $title
        ];

        $pdf = SnappyPDF::loadView('project_version_features/generate_feature_pdf', $data)
            ->setOrientation('portrait')
            ->setOption('margin-bottom', 7)
            ->setOption('margin-top', 5)
            ->setOption('footer-html', '<i>' . $title . '</i>');

        return $pdf->inline($title . '.pdf');
    }
}
