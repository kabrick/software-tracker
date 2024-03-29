@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </nav>

            @include('users.menu')

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">View Users</h3>
                </div>
                <div class="card-body">
                    @include('flash::message')

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                {{--<th>Phone</th>--}}
                                <th>Email</th>
                                <th>Roles</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $user->name }}</td>
                                    {{--<td>
                                        {{ $user->phone_number }}
                                    </td>--}}
                                    <td>
                                        {{ $user->email }}
                                    </td>
                                    <td>
                                        <ul>
                                            @foreach($user->getRoleNames() as $role_name)
                                                <li>{{ $role_name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        @can('users_edit')<a class="btn btn-sm btn-warning btn-rounded" href="{{ route('user.edit',$user->id) }}"><i class="fa fa-pencil"></i> Edit</a>@endcan
                                        @can('users_delete')
                                            {!! Form::open(['method' => 'DELETE','route' => ['user.destroy', $user->id],'style'=>'display:inline']) !!}
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

