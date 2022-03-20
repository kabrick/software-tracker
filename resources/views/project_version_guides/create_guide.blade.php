@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="/projects/{{ get_name($version_id, 'id', 'project_id', 'project_versions') }}">{{ get_name(get_name($version_id, 'id', 'project_id', 'project_versions'), 'id', 'name', 'projects') }}</a></li>
                    <li class="breadcrumb-item"><a href="/project_versions/{{ $version_id }}">{{ get_name($version_id, 'id', 'name', 'project_versions') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Project Version Guide</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Create Project Version Features</h3>
                    <a href="/project_version_features/create_feature/{{ $module_id }}">Use Free-Text Template</a>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => 'project_versions.store_guide', 'data-toggle' => 'validator', 'enctype' => 'multipart/form-data']) }}

                    {{ Form::hidden('module_id', $module_id) }}
                    {{ Form::hidden('version_id', $version_id) }}

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Parent Module</label>
                                {{ Form::text('parent_module', get_name($module_id, 'id', 'title', 'project_version_modules'), ['class' => 'form-control', 'readonly']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Feature Title</label>
                                {{ Form::text('title', '', ['class' => 'form-control', 'required']) }}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Feature Description</label>
                                <textarea name="description" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class='input_fields_wrap'>
                        <div class='row'>
                            <div class='col-md-6'>
                                <img src='{{ asset('assets/img/brand/600x250.png') }}' width='600' height='250' alt='image' id='image_preview1'>

                                <br><br>

                                <div class='custom-file'>
                                    <input type='file' name='step_image[]' class='custom-file-input chosen_image' id='1' onchange='preview_image(this)' required>
                                    <label class='custom-file-label' for='image_preview'>Choose image</label>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <h3>Step 1</h3>

                                <div class='form-group'>
                                    <label>Step Description</label>
                                    <textarea name='step_description[]' class='form-control' required rows="7"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br><br>

                    {{ Form::button('Submit',['type'=>'submit','class'=>'btn btn-success waves-effect waves-light m-r-10']) }}
                    {{ Form::button('Cancel',['type'=>'reset','class'=>'btn btn-default waves-effect waves-light']) }}
                    <a class="btn btn-primary waves-effect waves-light text-white add_step">Add Step</a>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        let wrapper = $(".input_fields_wrap");

        let x = 1; // initial row count

        $(".add_step").click(function (e) {
            e.preventDefault();
            x++;
            $(wrapper).append("\
                <div><hr>\
            <div class='row'>\
                    <div class='col-md-6'>\
                    <img src='<?php echo asset('assets/img/brand/600x250.png'); ?>' width='600' height='250' alt='image' id='image_preview" + x + "'>\
                    <br><br>\
                    <div class='custom-file'>\
                    <input type='file' name='step_image[]' class='custom-file-input chosen_image' id='" + x + "' onchange='preview_image(this)' required>\
                    <label class='custom-file-label' for='image_preview'>Choose image</label>\
            </div>\
            </div>\
            <div class='col-md-6'>\
            <h3>Step " + x + "</h3>\
            <div class='form-group'>\
            <label>Step Description</label>\
            <textarea name='step_description[]' class='form-control' required rows='7'></textarea>\
            </div>\
            <a class='remove_field btn btn-danger btn-rounded btn-sm text-white'><i class='fa fa-trash'></i> Remove Step</a>\
            </div>\
            </div></div>");
        });

        $(wrapper).on("click", ".remove_field", function (e) {
            e.preventDefault();
            $(this).parent('div').parent('div').parent('div').remove();
        });

        function preview_image(element) {
            if (element.files) {
                let current_id = element.id;
                var filesAmount = element.files.length;

                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();

                    reader.onload = function(event) {
                        $("#image_preview" + current_id).attr('src', event.target.result);
                    }

                    reader.readAsDataURL(element.files[i]);
                }
            }
        }
    </script>
@endpush
