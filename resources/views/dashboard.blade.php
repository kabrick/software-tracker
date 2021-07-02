@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Projects</h3>
                </div>
                <div class="card-body">
                    <div class="row">
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
                        <div class="col-md-6">
                            @if(count($projects) > 0)
                                <a class="btn-icon-clipboard" title="Project 1" href="">
                                    <div>
                                        <i class="ni ni-folder-17"></i>
                                        <span>
                                        <h3>{{ $projects[0]->name }}</h3>
                                        <p>{{ $projects[0]->description }}</p>
                                    </span>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>

                    @if(count($projects) > 1)
                    @endif
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
@endpush
