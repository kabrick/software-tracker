@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Role</li>
                </ol>
            </nav>

            @include('roles.menu')

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Create Role</h3>
                </div>
                <div class="card-body">
                    @include('flash::message')

                    {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
                    <div class="form-group">
                        {{ Form::label('name', 'Role Name') }}
                        {{ Form::text('name',null,['class' => 'form-control compulsory']) }}
                    </div>

                    @foreach($permissions->chunk(3) AS $permission)
                        <div class="row">
                            @foreach($permission as $item)
                                <div class="col-md-4">

                                    {{ Form::checkbox('permission[]', $item->id, false, ['class' => 'permission']) }}
                                    {{ $item->description }} <br>

                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <hr>

                    {{ Form::button('Submit',['type'=>'submit','class'=>'btn btn-success waves-effect waves-light m-r-10']) }}
                    {{ Form::button('Cancel',['type'=>'reset','class'=>'btn btn-default waves-effect waves-light']) }}

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
<script type="text/javascript">
    $("#check_all").on('click', function () {
        if ($('.permission').is(':checked')) {
            $('.permission').removeAttr('checked');
        } else {
            $('.permission').prop("checked", true);
        }
    });
</script>
@endpush

