@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="/projects/{{ get_name($feature->version_id, 'id', 'project_id', 'project_versions') }}">{{ get_name(get_name($feature->version_id, 'id', 'project_id', 'project_versions'), 'id', 'name', 'projects') }}</a></li>
                    <li class="breadcrumb-item"><a href="/project_versions/{{ $feature->version_id }}">{{ get_name($feature->version_id, 'id', 'name', 'project_versions') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Project Version Feature</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">{{ $feature->title }}</h3>
                </div>
                <div class="card-body">
                    @include('flash::message')

                    <div class="row">
                        <div class="col-md-3">
                            <a href="/project_version_features/archive/{{ $feature->id }}/" class="btn btn-outline-warning btn-rounded col-md-12" onclick="return confirm('Are you sure you want to archive this feature')">Archive</a>
                        </div>
                        <div class="col-md-3">
                            <a href="/project_version_features/delete/{{ $feature->id }}/" class="btn btn-outline-danger btn-rounded col-md-12" onclick="return confirm('Are you sure you want to permanently delete this feature')">Delete</a>
                        </div>
                        <div class="col-md-3">
                            <a href="/project_version_features/edit/{{ $feature->id }}" class="btn btn-outline-primary btn-rounded col-md-12">Edit</a>
                        </div>
                        <div class="col-md-3">
                            @if($feature->is_published == 0)
                                <a href="/project_version_features/publish/{{ $feature->id }}" class="btn btn-outline-default btn-rounded col-md-12">Publish</a>
                            @else
                                <a href="/project_version_features/unpublish/{{ $feature->id }}" class="btn btn-outline-default btn-rounded col-md-12">Un-publish</a>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-9">
                            {!! $feature->description !!}
                        </div>
                        <div class="col-md-3">
                            @if(count($child_features) > 0)
                                <h4>Child Features</h4>

                                <ul>
                                    @foreach($child_features as $child_feature)
                                        <li><a href="/project_version_features/feature_details/{{ $child_feature->id }}" style="color: #1a174d">{{ $child_feature->title }}</a></li>
                                    @endforeach
                                </ul>
                            @endif

                            <a href="/project_version_features/create_feature/{{ $feature->id }}/{{ $feature->version_id }}" class="btn btn-outline-default btn-rounded col-md-12">Add Child Feature</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script></script>
@endpush
