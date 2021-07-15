<?php

namespace App\Http\Controllers;

use App\Models\ProjectVersion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('project_versions.create');
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
            $project_version->contact_names = implode(",", str_replace(",", "-", $request->contact_names));
            $project_version->contact_phones = implode(",", str_replace(",", "-", $request->contact_phones));
            $project_version->contact_emails = implode(",", str_replace(",", "-", $request->contact_emails));
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

        return view('project_versions.show', compact('project_version'));
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
            $project_version->contact_names = implode(",", str_replace(",", "-", $request->contact_names));
            $project_version->contact_phones = implode(",", str_replace(",", "-", $request->contact_phones));
            $project_version->contact_emails = implode(",", str_replace(",", "-", $request->contact_emails));
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
}
