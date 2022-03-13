<?php

namespace App\Http\Controllers;

use App\Models\ProjectVersion;
use App\Models\ProjectVersionFeature;
use App\Models\ProjectVersionGuide;
use App\Models\ProjectVersionGuidesStep;
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

        $guides = ProjectVersionGuide::where('version_id', $id)->limit(1)->get();
        $guides_count = ProjectVersionGuide::where('version_id', $id)->count();

        return view('project_versions.show', compact('project_version', 'guides', 'guides_count'));
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

    public function view_more_guides(Request $request) {
        $last_guide_id = $request->last_guide_id;
        $id = $request->id;

        $guides = ProjectVersionGuide::where('version_id', $id)->where('id', '>', $last_guide_id)->limit(4)->get();

        $html_text = "";

        $counter = 0;

        while($counter < count($guides)) {
            $html_text .= "<div class='row'>";
            $html_text .= "<div class='col-md-6'>";
            if (isset($guides[$counter])) {
                $html_text .= "<a class='btn-icon-clipboard' title = '" . $guides[$counter]->title . "' href = '/project_versions/publish_guide/" . $guides[$counter]->id . "'>";
                $html_text .= "<div><i class='ni ni-folder-17'></i><span>";
                $html_text .= "<h3>" . $guides[$counter]->title . "</h3>";
                $html_text .= "<p>" . $guides[$counter]->description . "</p>";
                $html_text .= "</span></div></a>";
            }
            $html_text .= "</div><div class='col-md-6'>";
            if (isset($guides[$counter + 1])) {
                $html_text .= "<a class='btn-icon-clipboard' title = '" . $guides[$counter + 1]->title . "' href = '/project_versions/publish_guide/" . $guides[$counter + 1]->id . "'>";
                $html_text .= "<div><i class='ni ni-folder-17'></i><span>";
                $html_text .= "<h3>" . $guides[$counter + 1]->title . "</h3>";
                $html_text .= "<p>" . $guides[$counter + 1]->description . "</p>";
                $html_text .= "</span></div></a>";
            }
            $html_text .= "</div></div>";

            $counter += 2;
        }

        return json_encode([
            "last_guide_id" => $guides[count($guides) - 1]->id,
            "html" => $html_text
        ]);
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

        // create new project guides
        $project_version_guides = DB::table('project_version_guides')
            ->where('version_id', $id)->get();

        foreach ($project_version_guides as $guide) {
            $new_guide = new ProjectVersionGuide();
            $new_guide->version_id = $new_project_version_id;
            $new_guide->title = $guide->title;
            $new_guide->description = $guide->description;
            $new_guide->created_by = $logged_in;
            $new_guide->save();

            $new_guide_id = $new_guide->id;

            $guide_steps = DB::table('project_version_guides_steps')
                ->where('guide_id', $guide->id)->get();

            foreach ($guide_steps as $step) {
                $new_step = new ProjectVersionGuidesStep();
                $new_step->guide_id = $new_guide_id;
                $new_step->images = $step->images;
                $new_step->description = $step->description;
                $new_step->created_by = $logged_in;
                $new_step->save();
            }
        }

        // create new project features
        $project_version_features = DB::table('project_version_features')
            ->where('version_id', $id)->where('parent_version_id', 0)->get();

        foreach ($project_version_features as $feature) {
            $this->create_new_feature($feature->id, $new_project_version_id, 0);
        }

        flash("Project version has been cloned successfully")->success();
        return redirect('/project_versions/' . $new_project_version_id);
    }

    // recursive function to view child features
    public function create_new_feature($feature_id, $version_id, $parent_version_id) {
        $old_feature = ProjectVersionFeature::find($feature_id);

        $new_feature = new ProjectVersionFeature();
        $new_feature->version_id = $version_id;
        $new_feature->title = $old_feature->title;
        $new_feature->description = $old_feature->description;
        $new_feature->parent_version_id = $parent_version_id;
        $new_feature->is_published = $old_feature->is_published;
        $new_feature->created_by = Auth::user()->id;
        $new_feature->save();

        $project_version_features = DB::table('project_version_features')
            ->where('parent_version_id', $feature_id)->get();

        foreach ($project_version_features as $feature) {
            $this->create_new_feature($feature->id, $version_id, $new_feature->id);
        }
    }
}
