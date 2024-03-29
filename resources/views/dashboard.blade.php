@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Projects</h3>
                </div>
                <div class="card-body">

                    @include('flash::message')

                    <div class="row">
                        @can('projects_create')
                            <div class="col-md-6">
                                <a class="btn-icon-clipboard" title="Create New Project" href="{{ route('projects.create') }}">
                                    <div>
                                        <i class="ni ni-fat-add"></i>
                                        <span>
                                        <h3>Create New Project</h3>
                                        <p>Create a new project and easily document your software</p>
                                    </span>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        <div class="col-md-6">
                            @if(count($projects) > 0)
                                @can('projects_list')
                                    <a class="btn-icon-clipboard" title="{{ $projects[0]->name }}" href="/projects/{{ $projects[0]->id }}">
                                        <div>
                                            <i class="ni ni-folder-17"></i>
                                            <span>
                                        <h3>{{ $projects[0]->name }}</h3>
                                        <p>{{ $projects[0]->description }}</p>
                                    </span>
                                        </div>
                                    </a>
                                @endcan
                            @endif
                        </div>
                    </div>

                    @php $counter = 1; @endphp

                    @while($counter < count($projects))
                        <div class="row">
                            <div class="col-md-6">
                                @if(isset($projects[$counter]))
                                    @can('projects_list')
                                        <a class="btn-icon-clipboard" title="{{ $projects[$counter]->name }}" href="/projects/{{ $projects[$counter]->id }}">
                                            <div>
                                                <i class="ni ni-folder-17"></i>
                                                <span>
                                                    <h3>{{ $projects[$counter]->name }}</h3>
                                                    <p>{{ $projects[$counter]->description }}</p>
                                                </span>
                                            </div>
                                        </a>
                                    @endcan
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if(isset($projects[$counter + 1]))
                                    @can('projects_list')
                                        <a class="btn-icon-clipboard" title="{{ $projects[$counter + 1]->name }}" href="/projects/{{ $projects[$counter + 1]->id }}">
                                            <div>
                                                <i class="ni ni-folder-17"></i>
                                                <span>
                                                    <h3>{{ $projects[$counter + 1]->name }}</h3>
                                                    <p>{{ $projects[$counter + 1]->description }}</p>
                                                </span>
                                            </div>
                                        </a>
                                    @endcan
                                @endif
                            </div>
                        </div>

                        @php $counter += 2; @endphp
                    @endwhile

                    <br><br>

                    @can('view_archived_projects') <a href="{{ route('projects.view_archived_projects') }}" class="btn btn-default btn-rounded btn-sm text-white">View Archived Projects</a>@endcan

                    @can('role_list') <a href="{{ route('roles.index') }}" class="btn btn-primary btn-rounded btn-sm text-white">Manage Roles</a>@endcan

                    @can('users_list') <a href="{{ route('user.index') }}" class="btn btn-primary btn-rounded btn-sm text-white">Manage Users</a>@endcan

                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
@endpush
