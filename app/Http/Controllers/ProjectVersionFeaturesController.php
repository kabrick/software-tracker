<?php

namespace App\Http\Controllers;

use App\Models\ProjectVersionFeature;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectVersionFeaturesController extends Controller {

    public function view_features($version_id) {
        $features = ProjectVersionFeature::where('version_id', $version_id)->get();

        return view('project_version_features.view_features', compact('version_id', 'features'));
    }

    public function create_feature($parent_id, $version_id) {
        return view('project_version_features.create_feature', compact('parent_id', 'version_id'));
    }

    public function store_feature(Request $request) {
        $feature = new ProjectVersionFeature();
        $feature->version_id = $request->version_id;
        $feature->parent_version_id = $request->parent_id;
        $feature->title = $request->title;
        $feature->description = $request->description;
        $feature->created_by = Auth::user()->id;

        $feature_image = $request->file('feature_image');
        $feature_image_name = Carbon::now()->timestamp . "." . $feature_image->getClientOriginalExtension();
        $feature_image_path = $feature_image->storeAs('uploads/guides_images', $feature_image_name, 'public');
        $feature->image = "/storage/" . $feature_image_path;

        $feature->save();

        flash("Feature has been created successfully")->success();
        return redirect('project_version_features/view_features/' . $request->version_id);
    }
}
