@extends('layouts.app')

@push('styles')
    <style></style>
@endpush

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="/projects/{{ get_name($feature->version_id, 'id', 'project_id', 'project_versions') }}">{{ get_name(get_name($feature->version_id, 'id', 'project_id', 'project_versions'), 'id', 'name', 'projects') }}</a></li>
                    <li class="breadcrumb-item"><a href="/project_versions/{{ $feature->version_id }}">{{ get_name($feature->version_id, 'id', 'name', 'project_versions') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Project Version Features</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Edit Project Version Features</h3>
                </div>
                <div class="card-body">
                    @include('flash::message')

                    {{ Form::open(['route' => 'project_version_features.update', 'data-toggle' => 'validator', 'enctype' => 'multipart/form-data']) }}

                    {{ Form::hidden('version_id', $feature->version_id) }}
                    {{ Form::hidden('parent_id', $feature->parent_version_id) }}
                    {{ Form::hidden('id', $feature->id) }}

                    <div class="row">
                        <div class="col-md-6">
                            <img src='{{ $feature->image }}' width='600' height='250' alt='image' id='image_preview'>
                        </div>
                        <div class="col-md-6">
                            <div class='custom-file'>
                                <input type='file' name='feature_image' class='custom-file-input chosen_image' onchange='preview_image(this)'>
                                <label class='custom-file-label' for='feature_image'>Choose image</label>
                            </div>

                            <hr>

                            <div class="form-group">
                                <label>Feature Title</label>
                                {{ Form::text('title', $feature->title, ['class' => 'form-control compulsory', 'required']) }}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Feature Description</label>
                        <textarea name="description" id="description">{{ $feature->description }}</textarea>
                    </div>

                    {{ Form::button('Submit',['type'=>'submit','class'=>'btn btn-success waves-effect waves-light m-r-10']) }}
                    {{ Form::button('Cancel',['type'=>'reset','class'=>'btn btn-default waves-effect waves-light']) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="{{ asset('js/tiny_mce.js') }}"></script>

    <script>
        tinymce.init({
            selector: 'textarea#description', // Replace this CSS selector to match the placeholder element for TinyMCE
            height: 400,
            //menubar: false,
            plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr ' +
                'pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
            menubar: 'insert format',
            toolbar: 'undo redo | bold | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | numlist bullist | ' +
                'forecolor backcolor | fullscreen preview save print | image media link',
            init_instance_callback : function(editor) {
                var freeTiny = document.querySelector('.tox .tox-notification--in');
                freeTiny.style.display = 'none';
            }
        });

        function preview_image(element) {
            if (element.files) {
                var filesAmount = element.files.length;

                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();

                    reader.onload = function(event) {
                        $("#image_preview").attr('src', event.target.result);
                    }

                    reader.readAsDataURL(element.files[i]);
                }
            }
        }
    </script>
@endpush
