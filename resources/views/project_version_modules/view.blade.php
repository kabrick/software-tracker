@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="/projects/{{ $project_version->project_id }}">{{ get_name($project_version->project_id, 'id', 'name', 'projects') }}</a></li>
                    <li class="breadcrumb-item"><a href="/project_versions/{{ $project_version->id }}">{{ $project_version->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Project Version Modules</li>
                </ol>
            </nav>

            {{ Form::hidden('version_id', $project_version->id, ['id' => 'version_id']) }}
            {{ Form::hidden('current_module_title', '', ['id' => 'current_module_title']) }}
            {{ Form::hidden('current_module_desc', '', ['id' => 'current_module_desc']) }}
            {{ Form::hidden('current_module_parent_id', '', ['id' => 'current_module_parent_id']) }}

            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header bg-transparent">
                            <div class="row">
                                <div class="col-md-6"><h3>Modules</h3></div>
                                <div class="col-md-6">
                                    <a href="#" onclick="modules_back_button()" class="btn btn-outline-dark btn-rounded btn-sm">Back</a>
                                    <a href="#" class="btn btn-outline-success btn-rounded btn-sm" data-toggle="modal" data-target="#module_form">Add Module</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="modules_div">

                            @if(count($modules) > 0)
                                @foreach($modules as $module)
                                    <a href="#" onclick="select_module({{ $module->id }})">{{ $module->title }}</a>
                                    <br><br>
                                @endforeach
                            @else
                                <div class="text-center"><code>No modules available</code></div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4"><h3 id="module_div_title">Module Features</h3></div>
                                <div class="col-md-8">
                                    <a href="#" class="btn btn-outline-primary btn-rounded" onclick="edit_module()">Edit Module</a>
                                    <a href="#" class="btn btn-outline-warning btn-rounded" onclick="archive_module()">Archive Module</a>
                                    <a href="#" class="btn btn-outline-success btn-rounded" onclick="add_feature()">Add Feature To Module</a>
                                    <a href="#" class="btn btn-outline-info btn-rounded" onclick="print_module()">Print Module</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('flash::message')

                            <div id="module_details_div"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="module_form" tabindex="-1" role="dialog" aria-labelledby="module_form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-secondary border-0 mb-0">
                        <div class="card-body px-lg-5 py-lg-5">
                            <div class="text-center text-muted mb-4">
                                <h4>Add Module</h4>
                            </div>
                            <form role="form">
                                <div class="form-group">
                                    <label>Module Title</label>
                                    {{ Form::text('title', '', ['class' => 'form-control', 'required', 'id' => 'module_title']) }}
                                </div>
                                <div class="form-group">
                                    <label>Module Description</label>
                                    {{ Form::text('description', '', ['class' => 'form-control', 'required', 'id' => 'module_desc']) }}
                                </div>
                                <div class="form-group">
                                    <label>Parent Module</label>
                                    {{ Form::text('parent_module_text', 'None', ['class' => 'form-control', 'readonly', 'id' => 'parent_module_text']) }}
                                    {{ Form::hidden('parent_module_id', 0, ['id' => 'parent_module_id']) }}
                                    {{ Form::hidden('module_id', 0, ['id' => 'module_id']) }}
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary my-4" onclick="save_module_form()">Save</button>
                                    <button type="button" class="btn btn-danger" onclick="cancel_module_form()">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        function cancel_module_form() {
            $('#title, #description').val('');
            $('#module_form').modal('hide');
            $('#module_id').val('0');
        }

        function save_module_form() {
            let title = $('#module_title').val();
            let description = $('#module_desc').val();
            let parent_module_id = $('#parent_module_id').val();
            let module_id = $('#module_id').val();
            let version_id = $('#version_id').val();

            if(module_id == '0') {
                // create a new module
                $.ajax({
                    method: 'POST',
                    url: '/project_version_modules/create',
                    data: {'title': title, 'description': description, 'parent_module_id': parent_module_id, 'version_id': version_id},
                    success: function(response){
                        if (response == '1') {
                            // all clear
                            alert("Module has been created successfully!");
                            cancel_module_form();
                            update_modules_list();
                        } else {
                            alert("An error occurred! Please try again later");
                        }
                    }
                });
            } else {
                // edit the current module
                $.ajax({
                    method: 'POST',
                    url: '/project_version_modules/edit',
                    data: {'title': title, 'description': description, 'id': module_id},
                    success: function(response){
                        if (response == '1') {
                            // all clear
                            alert("Module has been edited successfully!");
                            cancel_module_form();
                            select_module(module_id);
                        } else {
                            alert("An error occurred! Please try again later");
                        }
                    }
                });
            }
        }

        function update_modules_list() {
            let parent_module_id = $('#parent_module_id').val();

            $.ajax({
                method: 'GET',
                url: '/project_version_modules/fetch_modules/' + parent_module_id,
                success: function(response){
                    $('#modules_div').html(response);
                }
            });
        }

        function select_module(module_id) {
            $('#parent_module_id').val(module_id);

            $.ajax({
                method: 'GET',
                url: '/project_version_modules/fetch_module_details/' + module_id,
                success: function(response){
                    let decoded_json = JSON.parse(response);
                    $('#module_details_div').html(decoded_json["html"]);
                    $('#module_div_title').text(decoded_json["title"] + " - Module Features");
                    $('#parent_module_text').val(decoded_json["title"]);
                    $('#current_module_title').val(decoded_json["title"]);
                    $('#current_module_desc').val(decoded_json["description"]);
                    $('#current_module_parent_id').val(decoded_json["parent_module_id"]);

                    update_modules_list();
                }
            });
        }

        function edit_module() {
            let parent_module_id = $('#parent_module_id').val();

            if (parent_module_id == '0') {
                alert("Please select a module to edit");
            } else {
                $('#module_title').val($('#current_module_title').val());
                $('#module_desc').val($('#current_module_desc').val());
                $('#module_id').val(parent_module_id);

                $('#module_form').modal('show');
            }
        }

        function archive_module() {
            let parent_module_id = $('#parent_module_id').val();

            if (parent_module_id == '0') {
                alert("Please select a module to archive");
            } else {
                if(confirm("Are you sure you want to archive this module. It's features will not be accessible after this")) {
                    $.ajax({
                        method: 'GET',
                        url: '/project_version_modules/archive/' + parent_module_id,
                        success: function(response){
                            if (response != "error") {
                                alert("Module has been archived");
                                select_module(response);
                            } else {
                                alert("An error occurred! Please try again later");
                            }
                        }
                    });
                }
            }
        }

        function add_feature() {
            let parent_module_id = $('#parent_module_id').val();

            if (parent_module_id == '0') {
                alert("Please select a module to add a feature to");
            } else {
                window.location.href = '/project_version_features/create_feature/' + parent_module_id;
            }
        }

        function modules_back_button() {
            let parent_module_id = $('#current_module_parent_id').val();

            select_module(parent_module_id);
        }

        function print_module() {
            let parent_module_id = $('#parent_module_id').val();

            if (parent_module_id == '0') {
                alert("Please select a module to print");
            } else {
                window.open('/project_version_modules/print_module/' + parent_module_id, '_blank');
            }
        }
    </script>
@endpush
