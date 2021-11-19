<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectVersion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller {

    public function index() {
        $projects = DB::table('projects')
            ->whereNull('deleted_at')->get();

        return view('projects.index', compact('projects'));
    }

    public function create() {
        return view('projects.create');
    }

    public function store(Request $request) {
        if (isset($request->name) && $request->name != "") {

            $project = new Project();
            $logged_in_user_id = Auth::user()->id;

            $project->name = $request->name;
            $project->description = $request->description;
            $project->created_by = $logged_in_user_id;
            $project->updated_by = $logged_in_user_id;

            $project->save();

            return redirect('/projects/');
        } else {
            flash("Project name was not entered")->error();
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {
        $project = Project::find($id);

        $project_versions = ProjectVersion::where("project_id", $id)->get();

        session()->put("project_id", $id);

        return view('projects.show', compact('project', 'project_versions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {
        $project = Project::find($id);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        if (isset($request->name) && $request->name != "") {

            $project = Project::find($id);

            $project->name = $request->name;
            $project->description = $request->description;
            $project->updated_by = Auth::user()->id;

            $project->save();

            return redirect('/projects/' . $id);
        } else {
            flash("Project name was not entered")->error();
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        //
    }

    public function archive_project($id) {
        $project = Project::find($id);

        if ($project->delete()) {
            flash("Project has been archived")->success();
            return redirect('/home/');
        } else {
            flash("An error occurred. Project was not archived")->error();
            return back()->withInput();
        }
    }

    public function delete_project($id) {
        $project = Project::find($id);

        if ($project->forceDelete()) {
            flash("Project has been deleted")->success();
            return redirect('/home/');
        } else {
            flash("An error occurred. Project was not deleted")->error();
            return back()->withInput();
        }
    }

    public function view_archived_projects() {
        $projects = Project::onlyTrashed()->get();

        return view('projects.view_archived_projects', compact('projects'));
    }

    public function restore_project($id) {
        $project = Project::withTrashed()->find($id);

        if($project->restore()){
            flash("Project has been restored")->success();
            return redirect('/projects/' . $project->id);
        } else {
            flash("An error occurred. Project was not restored")->error();
            return back()->withInput();
        }
    }
}
