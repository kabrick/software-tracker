@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">{{ $project->name }} Project Details</h3>
                </div>
                <div class="card-body">
                    <h4>Description</h4>

                    <p>{{ $project->description }}</p>

                    <hr>

                    <h4>Project Versions</h4>

                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn-icon-clipboard" title="Add New Project Version" href="{{ route('project_versions.create') }}">
                                <div>
                                    <i class="ni ni-fat-add"></i>
                                    <span>
                                        <h3>Add New Project Version</h3>
                                        <p>Add a new project version to track the changes in your project</p>
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            @if(count($project_versions) > 0)
                                <a class="btn-icon-clipboard" title="{{ $project_versions[0]->name }}" href="/project_versions/{{ $project_versions[0]->id }}">
                                    <div>
                                        <i class="ni ni-folder-17"></i>
                                        <span>
                                        <h3>{{ $project_versions[0]->name }}</h3>
                                        <p>{{ $project_versions[0]->description }}</p>
                                    </span>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>

                    @php $counter = 1; @endphp

                    @while($counter < count($project_versions))
                        <div class="row">
                            <div class="col-md-6">
                                @if(isset($project_versions[$counter]))
                                    <a class="btn-icon-clipboard" title="{{ $project_versions[$counter]->name }}" href="/project_versions/{{ $project_versions[$counter]->id }}">
                                        <div>
                                            <i class="ni ni-folder-17"></i>
                                            <span>
                                        <h3>{{ $project_versions[$counter]->name }}</h3>
                                        <p>{{ $project_versions[$counter]->description }}</p>
                                    </span>
                                        </div>
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if(isset($project_versions[$counter + 1]))
                                    <a class="btn-icon-clipboard" title="{{ $project_versions[$counter + 1]->name }}" href="/project_versions/{{ $project_versions[$counter + 1]->id }}">
                                        <div>
                                            <i class="ni ni-folder-17"></i>
                                            <span>
                                        <h3>{{ $project_versions[$counter + 1]->name }}</h3>
                                        <p>{{ $project_versions[$counter + 1]->description }}</p>
                                    </span>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>

                        @php $counter += 2; @endphp
                    @endwhile
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
@endpush