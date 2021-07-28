@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="/projects/{{ $project_version->project_id }}">{{ get_name($project_version->project_id, 'id', 'name', 'projects') }}</a></li>
                    <li class="breadcrumb-item"><a href="/project_versions/{{ $project_version->id }}">{{ get_name($project_version->id, 'id', 'name', 'project_versions') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Project Version</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Edit Project Version</h3>
                </div>
                <div class="card-body">
                    {{ Form::model($project_version, ['method' => 'PUT', 'route' => ['project_versions.update',$project_version], 'data-toggle' => 'validator']) }}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Version Name</label>
                                {{ Form::text('name', $project_version->name, ['class' => 'form-control compulsory', 'required']) }}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Version Description</label>
                                {{ Form::text('description', $project_version->description, ['class' => 'form-control compulsory', 'required']) }}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div>

                    @php
                        $contact_names = explode(",", $project_version->contact_names);
                        $contact_phones = explode(",", $project_version->contact_phones);
                        $contact_emails = explode(",", $project_version->contact_emails);
                    @endphp

                    <div class='input_fields_wrap'>
                        @for($i = 0; $i < count($contact_names); $i++)
                            <div>
                                <hr>

                                <div class='row'>
                                    <div class='col-md-4'>
                                        <div class='form-group'>
                                            {{ Form::label('contact_names', 'Contact Name') }}
                                            {{ Form::text('contact_names[]', $contact_names[$i], ['class' => 'form-control', 'required']) }}
                                        </div>
                                    </div>
                                    <div class='col-md-4'>
                                        <div class='form-group'>
                                            {{ Form::label('contact_phones', 'Contact Phone') }}
                                            {{ Form::text('contact_phones[]', $contact_phones[$i], ['class' => 'form-control', 'required']) }}
                                        </div>
                                    </div>
                                    <div class='col-md-3'>
                                        <div class='form-group'>
                                            {{ Form::label('contact_emails', 'Contact Email') }}
                                            {{ Form::email('contact_emails[]', $contact_emails[$i], ['class' => 'form-control', 'required']) }}
                                        </div>
                                    </div>
                                    <div class='col-md-1'>
                                        @if($i == 0)
                                            <button class='btn btn-sm btn-rounded btn-success add_item'><i class='fa fa-plus'></i> Add Contact</button>
                                        @else
                                            <button class='btn btn-sm btn-rounded btn-danger remove_field'><i class='fa fa-trash'></i> Remove</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endfor
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
        let max_contacts = 5;
        let wrapper = $(".input_fields_wrap");

        let x = 0; // initial row count

        $(".add_item").click(function (e) {
            e.preventDefault();
            if (x < max_contacts) {
                $(wrapper).append("\
                <div><hr>\
            <div class='row'>\
            <div class='col-md-4'>\
            <div class='form-group'>\
            <label for='contact_names'>Contact Name</label>\
            <input type='text' name='contact_names[]' class='form-control' required>\
            </div>\
            </div>\
            <div class='col-md-4'>\
            <div class='form-group'>\
            <label for='contact_phones'>Contact Phone</label>\
            <input type='text' name='contact_phones[]' class='form-control' required>\
            </div>\
            </div>\
            <div class='col-md-3'>\
            <div class='form-group'>\
            <label for='answer_c'>Contact Email</label>\
            <input type='email' name='contact_emails[]' class='form-control' required>\
            </div></div>\
            <div class='col-md-1'>\
            <button class='remove_field btn btn-sm btn-rounded btn-danger'><i class='fa fa-trash'></i> Remove</button>\
            </div>\
            </div></div>");
                x++;
            }
        });

        $(wrapper).on("click", ".remove_field", function (e) {
            e.preventDefault();
            $(this).parent('div').parent('div').parent('div').remove();
        });
    </script>
@endpush
