@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="/projects/{{ get_name($version_id, 'id', 'project_id', 'project_versions') }}">{{ get_name(get_name($version_id, 'id', 'project_id', 'project_versions'), 'id', 'name', 'projects') }}</a></li>
                    <li class="breadcrumb-item"><a href="/project_versions/{{ $version_id }}">{{ get_name($version_id, 'id', 'name', 'project_versions') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Project Version Features</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Create Project Version Features</h3>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => 'project_version_features.store_feature', 'data-toggle' => 'validator', 'enctype' => 'multipart/form-data']) }}

                    {{ Form::hidden('version_id', $version_id) }}
                    {{ Form::hidden('parent_id', $parent_id) }}

                    <div class="row">
                        <div class="col-md-6">
                            <img src='http://placehold.jp/600x250.png' width='600' height='250' alt='image' id='image_preview'>
                        </div>
                        <div class="col-md-6">
                            <div class='custom-file'>
                                <input type='file' name='feature_image' class='custom-file-input chosen_image' onchange='preview_image(this)' required>
                                <label class='custom-file-label' for='feature_image'>Choose image</label>
                            </div>

                            <hr>

                            <div class="form-group">
                                <label>Feature Title</label>
                                {{ Form::text('title', '', ['class' => 'form-control compulsory', 'required']) }}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Feature Description</label>
                        <textarea name="description" class="form-control compulsory" required rows="7"></textarea>
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
    <script>
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
