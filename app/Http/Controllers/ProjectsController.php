<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller {

    public function index() {
        $projects = DB::table('projects')->get();

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

        return view('projects.show', compact('project'));
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

            return redirect('/projects/');
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
}
