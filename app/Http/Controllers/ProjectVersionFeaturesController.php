<?php

namespace App\Http\Controllers;

use App\Models\ProjectVersionFeature;
use App\Models\ProjectVersionGuide;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectVersionFeaturesController extends Controller {

    public function view_features($version_id) {
        // get top level features only
        $features = DB::table('project_version_features')
            ->where('version_id', $version_id)
            ->where('parent_version_id', 0)
            ->whereNull('deleted_at')
            ->get(['id', 'title', 'parent_version_id', 'is_published']);

        $features_array = [];

        foreach ($features as $feature) {
            // get all features with parent id of that feature and follow the rabbit hole down
            $child_features = DB::table('project_version_features')
                ->where('version_id', $version_id)
                ->where('parent_version_id', $feature->id)
                ->whereNull('deleted_at')
                ->get(['id', 'title', 'is_published']);

            $child_features_array = [];

            foreach ($child_features as $child_feature) {
                // find how many features have this feature as a parent
                $child_features_count = DB::table('project_version_features')
                    ->where('version_id', $version_id)
                    ->where('parent_version_id', $child_feature->id)
                    ->whereNull('deleted_at')
                    ->count();

                $child_features_array[] = [
                    "id" => $child_feature->id,
                    "title" => $child_feature->title,
                    "is_published" => $child_feature->is_published,
                    "count" => $child_features_count,
                ];
            }

            $features_array[] = [
                "id" => $feature->id,
                "title" => $feature->title,
                "is_published" => $feature->is_published,
                "children" => $child_features_array,
            ];
        }

        return view('project_version_features.view_features', compact('version_id', 'features_array'));
    }

    public function create_feature($parent_id, $version_id) {
        return view('project_version_features.create_feature', compact('parent_id', 'version_id'));
    }

    public function store_feature(Request $request) {
        if (is_null($request->description) || $request->description == '') {
            flash('Please make sure to add a description before submitting')->error();
            return back()->withInput();
        }

        $feature = new ProjectVersionFeature();
        $feature->version_id = $request->version_id;
        $feature->parent_version_id = $request->parent_id;
        $feature->title = $request->title;
        $feature->description = $request->description;
        $feature->created_by = Auth::user()->id;

        $feature->save();

        flash("Feature has been created successfully")->success();
        return redirect('project_version_features/view_features/' . $request->version_id);
    }

    public function feature_details($id) {
        $feature = ProjectVersionFeature::find($id);

        $child_features = DB::table('project_version_features')
            ->where('parent_version_id', $id)
            ->whereNull('deleted_at')
            ->get(['id', 'title']);

        return view('project_version_features.feature_details', compact('id', 'feature', 'child_features'));
    }

    public function fetch_child_features($id): string {
        $child_features = DB::table('project_version_features')
            ->where('parent_version_id', $id)
            ->whereNull('deleted_at')
            ->get(['id', 'title', 'is_published']);

        $html = "<hr><ul>";

        foreach ($child_features as $child_feature) {
            // find how many features have this feature as a parent
            $child_features_count = DB::table('project_version_features')
                ->where('parent_version_id', $child_feature->id)
                ->count();

            $html .= '<li class="list-group-item"><div class="row">';
            $html .= '<div class="col">';
            $html .= '<a href="/project_version_features/feature_details/' . $child_feature->id . '" style="color: #1a174d">' . $child_feature->title . '</a>';

            if ($child_feature->is_published == 0) {
                $html .= '&nbsp;&nbsp;<span class="text-red">Unpublished</span>';
            }

            $html .= '</div>';

            if ($child_features_count > 0) {
                $html .= '<div class="col"><a href="#nested_features_' . $child_feature->id . '" onclick="fetch_child_features(' . $child_feature->id . ')" id="nested_features_id_' . $child_feature->id . '"><i class="ni ni-bold-right float-right"></i></a></div>';
            }

            $html .= '</div><div id="nested_features_' . $child_feature->id . '"></div></li>';
        }

        $html .= "</ul>";

        return $html;
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
        $feature->parent_version_id = $request->parent_id;
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
}
