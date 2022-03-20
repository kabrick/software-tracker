@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col "><nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="/projects/{{ get_name($feature->version_id, 'id', 'project_id', 'project_versions') }}">{{ get_name(get_name($feature->version_id, 'id', 'project_id', 'project_versions'), 'id', 'name', 'projects') }}</a></li>
                    <li class="breadcrumb-item"><a href="/project_versions/{{ $feature->version_id }}">{{ get_name($feature->version_id, 'id', 'name', 'project_versions') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update Project Version Guide</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Edit Project Version Features</h3>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => 'project_versions.update_guide', 'data-toggle' => 'validator', 'enctype' => 'multipart/form-data']) }}

                    {{ Form::hidden('feature_id', $feature->id) }}

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Parent Module</label>
                                {{ Form::text('parent_module', get_name($feature->module_id, 'id', 'title', 'project_version_modules'), ['class' => 'form-control', 'readonly']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Feature Title</label>
                                {{ Form::text('title', $feature->title, ['class' => 'form-control', 'required']) }}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Feature Description</label>
                                <textarea name="description" class="form-control" required>{{ $feature->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class='input_fields_wrap'>
                        @php $steps = $feature->steps()->get(); $counter = 1; @endphp

                        @foreach($steps as $step)
                            {{ Form::hidden('original_step_ids[]', $step->id) }}
                            <div>
                                {{ Form::hidden('step_id[]', $step->id) }}
                                <hr>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <img src='{{ $step->images }}' width='600' height='250' alt='image' id='image_preview{{ $counter }}'>

                                        <br><br>

                                        <div class='custom-file'>
                                            <input type='file' name='step_image[]' class='custom-file-input chosen_image' id='{{ $counter }}' onchange='preview_image(this)'>
                                            <label class='custom-file-label' for='image_preview'>Choose image</label>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <h3>Step {{ $counter }}</h3>

                                        <div class='form-group'>
                                            <label>Step Description</label>
                                            <textarea name='step_description[]' class='form-control' required rows="7">{{ $step->description }}</textarea>
                                        </div>

                                        @if($counter > 1)
                                            <br><br>
                                            <a class='remove_field btn btn-danger btn-rounded btn-sm text-white'><i class='fa fa-trash'></i> Remove Step</a>
                                        @endif
                                    </div>
                                </div>
                                <br><br>
                            </div>
                            @php $counter++ @endphp
                        @endforeach
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

        let x = <?php echo $counter - 1; ?>; // initial row count

        $(".add_step").click(function (e) {
            e.preventDefault();
            x++;
            $(wrapper).append("\
                <div><hr>\
                <input type='hidden' value='0' name='step_id[]'/> \
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
