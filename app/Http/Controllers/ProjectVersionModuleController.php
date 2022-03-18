<?php

namespace App\Http\Controllers;

use App\Models\ProjectVersion;
use App\Models\ProjectVersionModule;
use Illuminate\Http\Request;

class ProjectVersionModuleController extends Controller {

    public function view($project_version_id) {
        $project_version = ProjectVersion::find($project_version_id);

        $modules = ProjectVersionModule::where('parent_module_id', 0)->get();

        return view('project_version_modules.view', compact('project_version', 'modules'));
    }

    public function create(Request $request) {
        $module = new ProjectVersionModule();
        $module->parent_module_id = $request->parent_module_id;
        $module->version_id = $request->version_id;
        $module->description = $request->description;
        $module->title = $request->title;
        $module->created_by = auth()->id();

        if ($module->save()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function edit(Request $request) {
        $module = ProjectVersionModule::find($request->id);

        $module->description = $request->description;
        $module->title = $request->title;
        $module->updated_by = auth()->id();

        if ($module->save()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function fetch_modules($parent_module_id) {
        $html = "";

        $modules = ProjectVersionModule::where('parent_module_id', $parent_module_id)->get();

        foreach ($modules as $module) {
            $html .= "<a href='#' onclick='select_module(\"" . $module->id . "\")'>" . $module->title . "</a><br><br>";
        }

        return $html;
    }

    public function fetch_module_details($module_id) {
        $html = "";

        $module = ProjectVersionModule::find($module_id);

        if ($module) {
            $html .= "<h4>Description</h4><p>" . $module->description . "</p>";

            $response = [
                "title" => $module->title,
                "description" => $module->description,
                "parent_module_id" => $module->parent_module_id,
                "html" => $html
            ];
        } else {
            $response = [
                "title" => 'None',
                "description" => '',
                "parent_module_id" => 0,
                "html" => $html
            ];
        }

        return json_encode($response);
    }

    public function archive($module_id) {
        $module = ProjectVersionModule::find($module_id);
        $parent_module_id = $module->parent_module_id;

        if ($module->delete()) {
            return $parent_module_id;
        } else {
            return "error";
        }
    }
}
