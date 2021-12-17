<?php

namespace App\Http\Controllers;

use App\Models\ProjectVersionGuide;
use App\Models\ProjectVersionGuidesStep;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectVersionGuideController extends Controller {

    public function create_guide($version_id) {
        return view('project_version_guides.create_guide', compact('version_id'));
    }

    public function store_guide(Request $request) {
        $guide = new ProjectVersionGuide();
        $guide->version_id = $request->version_id;
        $guide->title = $request->title;
        $guide->description = $request->description;

        $guide->created_by = Auth::user()->id;
        $guide->save();

        $guide_step_description = $request->step_description;
        $guide_step_image = $request->file('step_image');

        for ($i = 0; $i < count($guide_step_description); $i++) {
            $step = new ProjectVersionGuidesStep();
            $step->guide_id = $guide->id;
            $step->description = $guide_step_description[$i];

            // for the images
            $step_image = $guide_step_image[$i];
            $step_image_name = Carbon::now()->timestamp . "-" . $i . "." . $step_image->getClientOriginalExtension();
            $step_path = $step_image->storeAs('uploads/guides_images', $step_image_name, 'public');

            $step->images = "/storage/" . $step_path;
            $step->created_by = Auth::user()->id;
            $step->save();
        }

        flash("Guide has been created successfully")->success();
        return redirect('project_versions/publish_guide/' . $guide->id);
    }

    public function edit_guide($id) {
        $guide = ProjectVersionGuide::find($id);

        return view('project_version_guides.edit_guide', compact('guide'));
    }

    public function update_guide(Request $request) {
        $original_step_ids = $request->original_step_ids;
        $step_ids = $request->step_id;
        $removed_steps = array_diff($original_step_ids, $step_ids);
        $guide_id = $request->guide_id;

        $guide = ProjectVersionGuide::find($guide_id);
        $guide->title = $request->title;
        $guide->description = $request->description;

        $guide->updated_by = Auth::user()->id;
        $guide->save();

        $guide_step_description = $request->step_description;
        $guide_step_image = $request->file('step_image');

        for ($i = 0; $i < count($step_ids); $i++) {
            if ($step_ids[$i] == 0) {
                $step = new ProjectVersionGuidesStep();
                $step->created_by = Auth::user()->id;
            } else {
                $step = ProjectVersionGuidesStep::find($step_ids[$i]);
                $step->updated_by = Auth::user()->id;
            }

            $step->guide_id = $guide->id;
            $step->description = $guide_step_description[$i];

            if (isset($guide_step_image[$i])) {
                // for the images
                $step_image = $guide_step_image[$i];
                $step_image_name = Carbon::now()->timestamp . "-" . $i . "." . $step_image->getClientOriginalExtension();
                $step_path = $step_image->storeAs('uploads/guides_images', $step_image_name, 'public');
                $step->images = "/storage/" . $step_path;
            }

            $step->save();
        }

        // remove the discarded steps
        foreach ($removed_steps as $removed_step) {
            $project_version_guide_step = ProjectVersionGuidesStep::find($removed_step);
            $project_version_guide_step->forceDelete();
        }

        flash("Guide has been updated successfully")->success();
        return redirect('project_versions/publish_guide/' . $guide->id);
    }

    public function publish_guide($id) {
        $guide = ProjectVersionGuide::find($id);

        $project_versions = DB::table('project_versions')
            ->whereNull('deleted_at')->whereNotIn('id', [$guide->version_id])->get(['id', 'name']);

        return view('project_version_guides.publish_guide', compact('guide', 'project_versions'));
    }

    public function archive_guide($id) {
        $guide = ProjectVersionGuide::find($id);

        if ($guide->delete()) {
            flash("Project version guide has been archived")->success();
            return redirect('/project_versions/' . $guide->version_id);
        } else {
            flash("An error occurred. Project version guide was not archived")->error();
            return back()->withInput();
        }
    }

    public function delete_guide($id) {
        $guide = ProjectVersionGuide::find($id);

        if ($guide->forceDelete()) {
            flash("Project version guide has been deleted")->success();
            return redirect('/project_versions/' . $guide->version_id);
        } else {
            flash("An error occurred. Project version guide was not deleted")->error();
            return back()->withInput();
        }
    }

    public function view_archived_guides() {
        $guides = ProjectVersionGuide::onlyTrashed()->get();

        return view('project_version_guides.view_archived_guides', compact('guides'));
    }

    public function restore_guide($id) {
        $guide = ProjectVersionGuide::withTrashed()->find($id);

        if($guide->restore()){
            flash("Project version guide has been restored")->success();
            return redirect('project_versions/publish_guide/' . $guide->id);
        } else {
            flash("An error occurred. Project version guide was not restored")->error();
            return back()->withInput();
        }
    }

    public function clone_guide($id, $version_id) {
        $guide = ProjectVersionGuide::find($id);

        $new_guide = new ProjectVersionGuide();
        $new_guide->version_id = $version_id;
        $new_guide->title = $guide->title;
        $new_guide->description = $guide->description;
        $new_guide->created_by = Auth::user()->id;
        $new_guide->save();

        $new_guide_steps = $guide->steps()->get();

        foreach ($new_guide_steps as $guide_step) {
            $new_guide_steps = new ProjectVersionGuidesStep();
            $new_guide_steps->guide_id = $new_guide->id;
            $new_guide_steps->description = $guide_step->description;
            $new_guide_steps->images = $guide_step->images;
            $new_guide_steps->created_by = Auth::user()->id;
            $new_guide_steps->save();
        }

        return "Guide was successfully cloned to the " . get_name($version_id, 'id', 'name', 'project_versions') . " project version";
    }

    public function share_guide_pdf($id) {
        $guide = ProjectVersionGuide::find($id);

        $title = get_name(get_name($guide->version_id, 'id', 'project_id', 'project_versions'), 'id', 'name', 'projects');

        $data = [
            'guide' => $guide,
            'title' => $title
        ];

        $pdf = SnappyPDF::loadView('project_version_guides/share_guide_pdf', $data)
            ->setOrientation('portrait')
            ->setOption('margin-bottom', 7)
            ->setOption('margin-top', 5)
            ->setOption('footer-html', '<i>' . $title . '</i>');

        return $pdf->inline($title . '-' . $guide->title . '.pdf');
    }
}
