<?php

namespace App\Http\Controllers;

use App\Models\ProjectVersionFeature;
use App\Models\ProjectVersionGuidesStep;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectVersionGuideController extends Controller {

    public function create_guide($module_id) {
        $version_id = get_name($module_id, 'id', 'version_id', 'project_version_modules');

        return view('project_version_guides.create_guide', compact('module_id', 'version_id'));
    }

    public function store_guide(Request $request) {
        $feature = new ProjectVersionFeature();
        $feature->version_id = $request->version_id;
        $feature->module_id = $request->module_id;
        $feature->title = $request->title;
        $feature->description = $request->description;
        $feature->print_order = 0;
        $feature->type = 1;

        $feature->created_by = Auth::user()->id;
        $feature->save();

        $guide_step_description = $request->step_description;
        $guide_step_image = $request->file('step_image');

        for ($i = 0; $i < count($guide_step_description); $i++) {
            $step = new ProjectVersionGuidesStep();
            $step->feature_id = $feature->id;
            $step->description = $guide_step_description[$i];

            // for the images
            $step_image = $guide_step_image[$i];
            $step_image_name = Carbon::now()->timestamp . "-" . $i . "." . $step_image->getClientOriginalExtension();
            $step_path = $step_image->storeAs('uploads/guides_images', $step_image_name, 'public');

            $step->images = "/storage/" . $step_path;
            $step->created_by = Auth::user()->id;
            $step->save();
        }

        flash("Feature has been created successfully")->success();
        return redirect('/project_version_features/feature_details/' . $feature->id);
    }

    public function edit_guide($id) {
        $feature = ProjectVersionFeature::find($id);

        return view('project_version_guides.edit_guide', compact('feature'));
    }

    public function update_guide(Request $request) {
        $original_step_ids = $request->original_step_ids;
        $step_ids = $request->step_id;
        $removed_steps = array_diff($original_step_ids, $step_ids);
        $feature_id = $request->feature_id;

        $feature = ProjectVersionFeature::find($feature_id);
        $feature->title = $request->title;
        $feature->description = $request->description;

        $feature->updated_by = Auth::user()->id;
        $feature->save();

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

            $step->feature_id = $feature->id;
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

        flash("Feature has been updated successfully")->success();
        return redirect('/project_version_features/feature_details/' . $feature->id);
    }
}
