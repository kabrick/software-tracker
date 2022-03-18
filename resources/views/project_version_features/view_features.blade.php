@extends('layouts.app')

@push('styles')
    <link href="{{ asset('assets/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('argon/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <style></style>
@endpush

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="/projects/{{ get_name($version_id, 'id', 'project_id', 'project_versions') }}">{{ get_name(get_name($version_id, 'id', 'project_id', 'project_versions'), 'id', 'name', 'projects') }}</a></li>
                    <li class="breadcrumb-item"><a href="/project_versions/{{ $version_id }}">{{ get_name($version_id, 'id', 'name', 'project_versions') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Project Version Features</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Project Version Features</h3>
                </div>
                <div class="card-body">
                    @include('flash::message')

                    {{ Form::open(['route' => 'project_version_features.search_features', 'data-toggle' => 'validator']) }}
                    {{ Form::hidden('version_id', $version_id) }}

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('user_id', 'User') }}
                                {{ Form::select('user_id', $users, '', ['class' => 'form-control', 'id' => 'user_id']) }}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('module_id', 'Module') }}
                                {{ Form::select('module_id', $modules, '', ['class' => 'form-control', 'id' => 'module_id']) }}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('date_search', 'Search By') }}
                                {{ Form::select('date_search', [-1 => 'All', 0 => 'Today', 1 => 'Yesterday', 2 => 'Custom Date', 3 => 'Date Range'], '', ['class' => 'form-control', 'id' => 'date_search']) }}
                            </div>
                        </div>

                        <div class="col-md-2" style="display: none;" id="on_date_div">
                            <div class="form-group">
                                {{ Form::label('on_date', 'On Date') }}
                                {{ Form::text('on_date','',['class' => 'form-control', 'readonly','id'=>'on_date']) }}
                            </div>
                        </div>

                        <div class="col-md-2" style="display: none;" id="start_date_div">
                            <div class="form-group">
                                {{ Form::label('start_date', 'Start Date') }}
                                {{ Form::text('start_date','',['class' => 'form-control', 'readonly','id'=>'start_date']) }}
                            </div>
                        </div>

                        <div class="col-md-2" style="display: none;" id="end_date_div">
                            <div class="form-group">
                                {{ Form::label('end_date', 'End Date') }}
                                {{ Form::text('end_date','',['class' => 'form-control', 'readonly','id'=>'end_date']) }}
                            </div>
                        </div>

                        <div class="col-md-2">
                            {{ Form::button('Submit', ['type'=>'submit','class'=>'btn btn-success waves-effect waves-light']) }}
                        </div>
                    </div>

                    {{ Form::close() }}

                    <hr>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="input-group">
                                {{ Form::text('suggested_title', '', ['class' => 'form-control', 'placeholder' => 'Filter By Title...', 'id' => 'suggested_title']) }}
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>

                    <hr>

                    @foreach($chunked_features as $features)
                        <div class="row">
                            @foreach($features as $feature)
                                <div class="col">
                                    <a class="btn-icon-clipboard" title="{{ $feature->title }}" href="/project_version_features/feature_details/{{ $feature->id }}">
                                        <div>
                                            <i class="ni ni-folder-17"></i>
                                            <div>
                                                <h3 class="feature_title">{{ $feature->title }}</h3>
                                                <p>Module: {{ $feature->module_title }}</p>
                                                <p>@if($feature->is_published == 1) <span style="color: green">Published</span> @else <span style="color: orangered">Unpublished</span> @endif</p>
                                                <p>Last updated By {{ $feature->username }} {{ \Carbon\Carbon::parse($feature->updated_at)->fromNow() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    {{--<div class="row">
                        <div class="col-md-4">
                            <a href="/project_version_features/share_pdf/{{ $version_id }}" target="_blank" class="btn btn-outline-primary btn-rounded col-md-12">Share PDF</a>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="{{ asset('assets/vendor/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('argon/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $('#user_id, #module_id').select2();

        $('#suggested_title').keyup(function () {
            let search_text = $(this).val().toLowerCase();
            if (search_text.length > 3) {
                $('.feature_title').each(function () {
                    if (!$(this).html().toLowerCase().includes(search_text)) {
                        $(this).parent().parent().parent().hide();
                    }
                });
            } else {
                // restore all
                $('.feature_title').each(function () {
                    $(this).parent().parent().parent().show();
                });
            }
        });

        $('#date_search').change(function () {
            if (this.value == 2){
                $('#on_date_div').show();
                $('#start_date_div').hide();
                $('#end_date_div').hide();
            } else if (this.value == 3){
                $('#start_date_div').show();
                $('#end_date_div').show();
                $('#on_date_div').hide();
            } else {
                $('#on_date_div').hide();
                $('#start_date_div').hide();
                $('#end_date_div').hide();
            }
        });

        $('#on_date,#start_date,#end_date').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });
    </script>
@endpush
