@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
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
                                {{ Form::text('name', '', ['class' => 'form-control compulsory', 'required']) }}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Version Description</label>
                                {{ Form::text('description', '', ['class' => 'form-control compulsory', 'required']) }}
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class='input_fields_wrap'>
                        <div class='row'>
                            <div class='col-md-4'>
                                <div class='form-group'>
                                    {{ Form::label('contact_names', 'Contact Name') }}
                                    {{ Form::text('contact_names[]', '', ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='form-group'>
                                    {{ Form::label('contact_phones', 'Contact Phone') }}
                                    {{ Form::text('contact_phones[]', '', ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                            <div class='col-md-3'>
                                <div class='form-group'>
                                    {{ Form::label('contact_emails', 'Contact Email') }}
                                    {{ Form::email('contact_emails[]', '', ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                            <div class='col-md-1'>
                                <button class='btn btn-sm btn-rounded btn-success add_item'><i class='fa fa-plus'></i> Add Contact</button>
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
