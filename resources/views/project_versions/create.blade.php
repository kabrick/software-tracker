@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="/projects/{{ $project_id }}">{{ get_name($project_id, 'id', 'name', 'projects') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Project Version</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Create Project Version</h3>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => 'project_versions.store', 'data-toggle' => 'validator']) }}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Version Name</label>
                                {{ Form::text('name', '', ['class' => 'form-control', 'required']) }}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Version Description</label>
                                {{ Form::text('description', '', ['class' => 'form-control', 'required']) }}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
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
    <script></script>
@endpush
