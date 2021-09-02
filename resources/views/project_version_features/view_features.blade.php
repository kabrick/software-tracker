@extends('layouts.app')

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
                    @if(count($features) > 0)
                    @else
                        <a href="/project_version_features/create_feature/0/{{ $version_id }}" class="btn btn-outline-success btn-rounded col-md-12">Create your first feature for this project version</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script></script>
@endpush
