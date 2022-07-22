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
                    <h3 class="mb-0">View Roles</h3>
                </div>
                <div class="card-body">
                    @include('flash::message')

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Users</th>
                                <th>Permissions</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($roles as $key => $role)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        {{ $role->users_count }}
                                    </td>
                                    <td>
                                        {{ $role->permissions_count }}
                                    </td>
                                    <td>
                                        @can('role_list')<a class="btn btn-sm btn-rounded btn-info" href="{{ route('roles.show',$role->id) }}"><i class="fa fa-info-circle"></i> Details</a>@endcan
                                        @can('role_edit')<a class="btn btn-sm btn-warning btn-rounded" href="{{ route('roles.edit',$role->id) }}"><i class="fa fa-pencil"></i> Edit</a>@endcan
                                        @can('role_delete')
                                            {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                            {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-rounded btn-danger']) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

