<?php

namespace App\Http\Controllers;

use App\Models\ProjectVersionGuide;
use App\Models\ProjectVersionGuidesStep;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $project_version_guide_step = ProjectVersionGuidesStep::findOrFail($removed_step);
            $project_version_guide_step->forceDelete();
        }

        flash("Guide has been updated successfully")->success();
        return redirect('project_versions/publish_guide/' . $guide->id);
    }

    public function publish_guide($id) {
        $guide = ProjectVersionGuide::find($id);

        return view('project_version_guides.publish_guide', compact('guide'));
    }
}
