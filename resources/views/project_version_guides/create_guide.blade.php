@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Create Project Version Guide</h3>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => 'project_versions.store_guide', 'data-toggle' => 'validator', 'enctype' => 'multipart/form-data']) }}

                    {{ Form::hidden('version_id', $version_id) }}

                    <div class="row">
                        <div class="col-md-6">
                            <img src="http://placehold.jp/600x250.png" width="600" height="250" alt="image" id="image_preview0">

                            <br><br>

                            <div class="custom-file">
                                <input type="file" name="feature_image" class="custom-file-input chosen_image" id="0" onchange='preview_image(this)' required>
                                <label class="custom-file-label" for="image_preview">Choose image</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Guide Title</label>
                                {{ Form::text('title', '', ['class' => 'form-control compulsory', 'required']) }}
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group">
                                <label>Guide Description</label>
                                <textarea name="description" class="form-control compulsory" required></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class='input_fields_wrap'>
                        <div class='row'>
                            <div class='col-md-6'>
                                <img src='http://placehold.jp/600x250.png' width='600' height='250' alt='image' id='image_preview1'>

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
                                    <textarea name='step_description[]' class='form-control compulsory' required></textarea>
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
        let max_contacts = 5;
        let wrapper = $(".input_fields_wrap");

        let x = 1; // initial row count

        $(".add_step").click(function (e) {
            e.preventDefault();
            if (x < max_contacts) {
                x++;
                $(wrapper).append("\
                <div><hr>\
            <div class='row'>\
                    <div class='col-md-6'>\
                    <img src='http://placehold.jp/600x250.png' width='600' height='250' alt='image' id='image_preview" + x + "'>\
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
            <textarea name='step_description[]' class='form-control compulsory' required></textarea>\
            </div>\
            </div>\
            </div></div>");
            }
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
