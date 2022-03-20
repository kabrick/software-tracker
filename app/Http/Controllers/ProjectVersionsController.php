<?php

namespace App\Http\Controllers;

use App\Models\ProjectVersion;
use App\Models\ProjectVersionFeature;
use App\Models\ProjectVersionGuidesStep;
use App\Models\ProjectVersionModule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectVersionsController extends Controller {
    /**
     * Display a listing of the resource.
     *
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create() {
        $project_id = session()->get("project_id");
        return view('project_versions.create', compact('project_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request) {
        if (isset($request->name) && $request->name != "") {

            $project_version = new ProjectVersion();

            $project_version->name = $request->name;
            $project_version->description = $request->description;
            $project_version->project_id = session()->get("project_id");
            $project_version->created_by = Auth::user()->id;

            $project_version->save();

            return redirect('/projects/' . session()->get("project_id"));
        } else {
            flash("Project version name was not entered")->error();
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     */
    public function show($id) {
        $project_version = ProjectVersion::find($id);

        $project_features = ProjectVersionFeature::where('version_id', $id)
            ->leftJoin('users', 'users.id', '=', 'project_version_features.updated_by')
            ->limit(7)
            ->orderBy('project_version_features.updated_at', 'desc')
            ->get(['project_version_features.id', 'project_version_features.title', 'project_version_features.is_published',
                'users.name', 'project_version_features.updated_at']);

        return view('project_versions.show', compact('project_version', 'project_features'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit($id) {
        $project_version = ProjectVersion::find($id);

        return view('project_versions.edit', compact('project_version'));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id) {
        if (isset($request->name) && $request->name != "") {

            $project_version = ProjectVersion::find($id);

            $project_version->name = $request->name;
            $project_version->description = $request->description;
            $project_version->updated_by = Auth::user()->id;

            $project_version->save();

            return redirect('/project_versions/' . $id);
        } else {
            flash("Project version name was not entered")->error();
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id) {
        //
    }

    public function archive_version($id) {
        $project_version = ProjectVersion::find($id);

        if ($project_version->delete()) {
            flash("Project version has been archived")->success();
            return redirect('/home/');
        } else {
            flash("An error occurred. Project version was not archived")->error();
            return back()->withInput();
        }
    }

    public function delete_version($id) {
        $project_version = ProjectVersion::find($id);

        if ($project_version->forceDelete()) {
            flash("Project version has been deleted")->success();
            return redirect('/home/');
        } else {
            flash("An error occurred. Project version was not deleted")->error();
            return back()->withInput();
        }
    }

    public function view_archived_versions() {
        $project_versions = ProjectVersion::onlyTrashed()->get();

        return view('project_versions.view_archived_versions', compact('project_versions'));
    }

    public function restore_version($id) {
        $project_version = ProjectVersion::withTrashed()->find($id);

        if($project_version->restore()){
            flash("Project version has been restored")->success();
            return redirect('/project_versions/' . $project_version->id);
        } else {
            flash("An error occurred. Project version was not restored")->error();
            return back()->withInput();
        }
    }

    public function clone($id) {
        $logged_in = Auth::user()->id;

        // create a new project version
        $project_version = DB::table('project_versions')->where('id', $id)->first();

        $new_project_version = new ProjectVersion();
        $new_project_version->project_id = $project_version->project_id;
        $new_project_version->name = $project_version->name;
        $new_project_version->description = $project_version->description;
        $new_project_version->created_by = $logged_in;
        $new_project_version->save();

        $new_project_version_id = $new_project_version->id;

        // create new project features
        $project_version_modules = DB::table('project_version_modules')
            ->where('version_id', $id)->where('parent_module_id', 0)->get(['id']);

        foreach ($project_version_modules as $module) {
            $this->create_new_module($module->id, $new_project_version_id, 0, $logged_in);
        }

        flash("Project version has been cloned successfully")->success();
        return redirect('/project_versions/' . $new_project_version_id);
    }

    // recursive function to view child features
    public function create_new_module($module_id, $version_id, $parent_module_id, $logged_in) {
        $old_module = ProjectVersionModule::withTrashed()->find($module_id);

        $new_module_id = DB::table('project_version_modules')->insertGetId([
            "version_id" => $version_id,
            "title" => $old_module->title,
            "description" => $old_module->description,
            "parent_module_id" => $parent_module_id,
            "print_order" => $old_module->print_order,
            "created_by" => $logged_in,
            "updated_by" => $logged_in,
            "created_at" => now(),
            "updated_at" => now()
        ]);

        // create new project guides
        $project_version_features = DB::table('project_version_features')
            ->where('module_id', $module_id)->get();

        foreach ($project_version_features as $feature) {
            $new_feature_id = DB::table('project_version_features')->insertGetId([
                "version_id" => $version_id,
                "title" => $feature->title,
                "description" => $feature->description,
                "module_id" => $new_module_id,
                "is_published" => $feature->is_published,
                "print_order" => $feature->print_order,
                "type" => $feature->type,
                "created_by" => $logged_in,
                "updated_by" => $logged_in,
                "created_at" => now(),
                "updated_at" => now()
            ]);

            if ($feature->type == 1) {
                $guide_steps = DB::table('project_version_guides_steps')
                    ->where('feature_id', $feature->id)->get();

                foreach ($guide_steps as $step) {
                    DB::table('project_version_guides_steps')->insert([
                        "feature_id" => $new_feature_id,
                        "description" => $step->description,
                        "images" => $step->images,
                        "created_by" => $logged_in,
                        "updated_by" => $logged_in,
                        "created_at" => now(),
                        "updated_at" => now()
                    ]);
                }
            }
        }

        $project_version_modules = DB::table('project_version_modules')
            ->where('parent_module_id', $module_id)->get(['id']);

        foreach ($project_version_modules as $module) {
            $this->create_new_module($module->id, $version_id, $new_module_id, $logged_in);
        }
    }
}
