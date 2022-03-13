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
                    @include('flash::message')

                    @if(count($features_array) > 0)
                        <ul class="list-group list-group-heading">
                            @foreach($features_array as $feature)
                                <li class="list-group-item">
                                    <a href="/project_version_features/feature_details/{{ $feature["id"] }}" style="color: #1a174d">{{ $feature["title"] }}</a>
                                    @if($feature["is_published"] == 0)<span class="text-red">Unpublished</span>@endif

                                    @if(count($feature["children"]) > 0)
                                        <hr>

                                        <ul></ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <br><br>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <a href="/project_version_features/create_feature/0/{{ $version_id }}" class="btn btn-outline-success btn-rounded col-md-12">Add top level feature for this project version</a>
                        </div>
                        <div class="col-md-4">
                            <a href="/project_version_features/share_pdf/{{ $version_id }}" target="_blank" class="btn btn-outline-primary btn-rounded col-md-12">Share PDF</a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('project_version_features.view_archived') }}" class="btn btn-outline-default btn-rounded col-md-12">View Archived Project Version Features</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        function fetch_child_features(id) {
            let nested_features_div = $('#nested_features_' + id);
            let nested_features_id = $('#nested_features_id_' + id);

            if (nested_features_div.html() === '') {
                $.ajax({
                    method: 'GET',
                    url: '/project_version_features/fetch_child_features/' + id,
                    success: function(response){
                        nested_features_id.html('<i class="ni ni-bold-down float-right"></i>');
                        nested_features_div.html(response);
                    }
                });
            } else {
                nested_features_id.html('<i class="ni ni-bold-right float-right"></i>');
                nested_features_div.html('');
            }
        }
    </script>
@endpush
