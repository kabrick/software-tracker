@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Roles</li>
                </ol>
            </nav>

            @include('roles.menu')

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Permissions given to users with role of {{ $role->name }}</h3>
                </div>
                <div class="card-body">
                    @include('flash::message')

                    @foreach($rolePermissions->chunk(6) as $permissions)
                        <div class="row">
                            @foreach($permissions as $permission)
                                <div class="col-md-2">
                                    <h4><b>{{ $permission['description'] }}</b></h4>
                                    <br>
                                    <p>{{ $permission['long_description'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection


