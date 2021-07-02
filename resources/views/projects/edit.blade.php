@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Edit Project</h3>
                </div>
                <div class="card-body">
                    {{ Form::model($project, ['method' => 'PUT', 'route' => ['projects.update',$project], 'data-toggle' => 'validator']) }}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Project Name</label>
                                {{ Form::text('name', $project->name, ['class' => 'form-control compulsory', 'required']) }}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Project Description</label>
                                {{ Form::text('description', $project->description, ['class' => 'form-control compulsory', 'required']) }}
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
@endpush
