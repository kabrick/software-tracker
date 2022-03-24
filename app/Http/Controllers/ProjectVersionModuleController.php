<?php

namespace App\Http\Controllers;

use App\Models\ProjectVersion;
use App\Models\ProjectVersionFeature;
use App\Models\ProjectVersionModule;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectVersionModuleController extends Controller {

    public function view($project_version_id) {
        $project_version = ProjectVersion::find($project_version_id);

        $modules = ProjectVersionModule::where('version_id', $project_version_id)->where('parent_module_id', 0)->orderBy('print_order')->get();

        return view('project_version_modules.view', compact('project_version', 'modules'));
    }

    public function create(Request $request) {
        $module = new ProjectVersionModule();
        $module->parent_module_id = $request->parent_module_id;
        $module->version_id = $request->version_id;
        $module->description = $request->description;
        $module->title = $request->title;
        $module->print_order = 0;
        $module->created_by = auth()->id();
        $module->updated_by = auth()->id();

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

    public function fetch_modules($parent_module_id, $version_id) {
        $html = "";

        $modules = ProjectVersionModule::where('parent_module_id', $parent_module_id)->where('version_id', $version_id)->get();

        if (count($modules) > 0) {
            foreach ($modules as $module) {
                $html .= "<a href='#' onclick='select_module(\"" . $module->id . "\")'>" . $module->title . "</a><br><br>";
            }
        } else {
            $html .= "<div class='text-center'><code>No modules available</code></div>";
        }

        return $html;
    }

    public function fetch_module_details($module_id) {
        $html = "";

        $module = ProjectVersionModule::find($module_id);

        if ($module) {
            $html .= "<h4>Description</h4><p>" . $module->description . "</p>";

            $chunked_features = ProjectVersionFeature::where('module_id', $module_id)
                ->where('version_id', $module->version_id)
                ->leftJoin('users', 'users.id', '=', 'project_version_features.updated_by')
                ->orderBy('project_version_features.updated_at', 'desc')
                ->get(['project_version_features.id', 'project_version_features.title', 'project_version_features.is_published',
                    'users.name', 'project_version_features.updated_at'])->chunk(3);

            foreach ($chunked_features as $features) {
                $html .= '<div class="row">';
                foreach ($features as $feature) {
                    $html .= '<div class="col-md-4">';
                    $html .= '<a class="btn-icon-clipboard" title="' . $feature->title . '" href="/project_version_features/feature_details/' . $feature->id . '">';
                    $html .= '<div><i class="ni ni-folder-17"></i><div style="margin-left: 10px"><h3>' . $feature->title . '</h3>';
                    $html .= '<p>Last updated By ' . $feature->name . ' ' . Carbon::parse($feature->updated_at)->fromNow() . '</p>';
                    $html .= '</div></div></a></div>';
                }
                $html .= '</div>';
            }

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

    public function generate_manual($version_id) {
        $html = "";

        $modules = DB::table('project_version_modules')
            ->where('version_id', $version_id)
            ->where('parent_module_id', 0)
            ->whereNull('deleted_at')
            ->orderBy('print_order')
            ->get(['id', 'title', 'description']);

        $top_level = 1;

        foreach ($modules as $module) {
            $html .= "<h1 style='color: maroon'>$top_level   $module->title</h1>";
            $html .= "<hr style='border-top: 2px dashed orange;'>";
            $html .= "<br>$module->description<br><br>";
            $html .= "<hr style='border-top: 2px dashed orange;'>";
            $html .= $this->get_module_features($module->id);
            $html .= $this->get_child_modules($module->id, $top_level);

            $top_level++;
        }

        $title = get_name(get_name($version_id, 'id', 'project_id', 'project_versions'), 'id', 'name', 'projects');

        $data = [
            'html' => $html,
            'title' => $title
        ];

        $pdf = SnappyPDF::loadView('project_version_modules/generate_manual', $data)
            ->setOrientation('portrait')
            ->setOption('margin-bottom', 7)
            ->setOption('margin-top', 5)
            ->setOption('footer-html', '<i>' . $title . '</i>');

        return $pdf->inline($title . '.pdf');
    }

    public function get_child_modules($module_id, $current_level): string {
        $modules = DB::table('project_version_modules')
            ->where('parent_module_id', $module_id)
            ->whereNull('deleted_at')
            ->orderBy('print_order')
            ->get(['id', 'title', 'description']);

        $sub_level = 1;
        $return_html = "";

        foreach ($modules as $module) {
            $return_html .= "<h1 style='color: maroon'>$current_level.$sub_level   $module->title</h1>";
            $return_html .= "<hr style='border-top: 2px dashed orange;'>";
            $return_html .= "<br>$module->description<br><br>";
            $return_html .= "<hr style='border-top: 2px dashed orange;'>";
            $return_html .= $this->get_module_features($module->id);
            $return_html .= $this->get_child_modules($module->id, $current_level . "." . $sub_level);

            $sub_level++;
        }

        return $return_html;
    }

    public function get_module_features($module_id): string {
        $features = ProjectVersionFeature::where('is_published', 1)
            ->where('module_id', $module_id)
            ->whereNull('deleted_at')
            ->orderBy('print_order')
            ->get();

        $return_html = "";

        foreach ($features as $feature) {
            $return_html .= "<h2>$feature->title</h2>";
            $return_html .= "$feature->description<br><br><hr>";

            if ($feature->type == 1) {
                $steps = $feature->steps()->get(); $counter = 1;
                $return_html .= "<br><br>";

                foreach($steps as $step) {
                    $return_html .= "<h3>Step " . $counter . "</h3><p>" . $step->description . "</p>";
                    $return_html .= "<img src='" . asset($step->images) . "' width='600' height='250' alt='image'>";
                    $return_html .= "<br><br>";

                    $counter++;
                }
            }
        }

        return $return_html;
    }

    public function print_module($module_id) {
        $html = "";

        $module = ProjectVersionModule::find($module_id);

        $html .= "<h1 style='color: maroon'>1   $module->title</h1>";
        $html .= "<hr style='border-top: 2px dashed orange;'>";
        $html .= "<br>$module->description<br><br>";
        $html .= "<hr style='border-top: 2px dashed orange;'>";
        $html .= $this->get_module_features($module->id);
        $html .= $this->get_child_modules($module->id, 1);

        $title = $module->title;

        $data = [
            'html' => $html,
            'title' => $title
        ];

        $pdf = SnappyPDF::loadView('project_version_modules/generate_manual', $data)
            ->setOrientation('portrait')
            ->setOption('margin-bottom', 7)
            ->setOption('margin-top', 5)
            ->setOption('footer-html', '<i>' . $title . '</i>');

        return $pdf->inline($title . '.pdf');
    }

    public function set_manual_print_order($version_id) {
        $project_version = ProjectVersion::find($version_id);

        $modules = ProjectVersionModule::where('parent_module_id', 0)->where('version_id', $version_id)->orderBy('print_order')->get();

        return view('project_version_modules.set_manual_print_order', compact('project_version', 'modules'));
    }

    public function fetch_module_details_lists($module_id) {
        $html = "";

        $module = ProjectVersionModule::find($module_id);

        if ($module) {
            $child_modules = ProjectVersionModule::where('parent_module_id', $module_id)->orderBy('print_order')->get();

            if (count($child_modules) > 0) {
                $html .= "<div id='list'>";
                foreach ($child_modules as $child_module) {
                    $html .= '<div class="draggable"><input name="module_id[]" value="' . $child_module->id . '" type="hidden">' . $child_module->title . '</div>';
                }
                $html .= "</div>";
            } else {
                $html .= "<div class='text-center'><code>No modules available</code></div>";
            }

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

    public function save_manual_print_order(Request $request) {
        $module_ids = is_array($request->module_id) ? $request->module_id : [];
        $order = 1;

        foreach ($module_ids as $id) {
            $module = ProjectVersionModule::find($id);
            $module->print_order = $order;
            $module->save();
            $order++;
        }

        flash("Module print order has been saved")->success();
        return redirect('/project_version_modules/set_manual_print_order/' . $request->version_id);
    }

    public function set_manual_print_order_features($version_id) {
        $project_version = ProjectVersion::find($version_id);

        $modules = ProjectVersionModule::where('parent_module_id', 0)->where('version_id', $version_id)->orderBy('print_order')->get();

        return view('project_version_modules.set_manual_print_order_features', compact('project_version', 'modules'));
    }

    public function fetch_module_details_lists_features($module_id) {
        $html = "";

        $module = ProjectVersionModule::find($module_id);

        if ($module) {
            $features = ProjectVersionFeature::where('module_id', $module_id)->orderBy('print_order')->get();

            if (count($features) > 0) {
                $html .= "<div id='list'>";
                foreach ($features as $feature) {
                    $html .= '<div class="draggable"><input name="feature_id[]" value="' . $feature->id . '" type="hidden">' . $feature->title . '</div>';
                }
                $html .= "</div>";
            }

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

    public function save_manual_print_order_features(Request $request) {
        $module_ids = is_array($request->feature_id) ? $request->feature_id : [];
        $order = 1;

        foreach ($module_ids as $id) {
            $module = ProjectVersionFeature::find($id);
            $module->print_order = $order;
            $module->save();
            $order++;
        }

        flash("Module print order has been saved")->success();
        return redirect('/project_version_modules/set_manual_print_order_features/' . $request->version_id);
    }
}
