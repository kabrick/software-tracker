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
                    @if(count($features_array) > 0)
                        <ul class="list-group list-group-heading">
                            @foreach($features_array as $feature)
                                <li class="list-group-item">
                                    <a href="/project_version_features/feature_details/{{ $feature["id"] }}" style="color: #1a174d">{{ $feature["title"] }}</a>

                                    @if(count($feature["children"]) > 0)
                                        <hr>

                                        <ul>
                                            @foreach($feature["children"] as $feature_children)
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col">
                                                            <a href="/project_version_features/feature_details/{{ $feature_children["id"] }}" style="color: #1a174d">{{ $feature_children["title"] }}</a>
                                                        </div>
                                                        @if($feature_children["count"] > 0)
                                                            <div class="col">
                                                                <a class="pull-right text-sm" href="#nested_features_{{ $feature_children["id"] }}" onclick="fetch_child_features({{ $feature_children["id"] }})" id="nested_features_id_{{ $feature_children["id"] }}">View Nested Features</a>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div id="nested_features_{{ $feature_children["id"] }}"></div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <br><br>

                        <a href="/project_version_features/create_feature/0/{{ $version_id }}" class="btn btn-outline-success btn-rounded col-md-12">Add top level feature for this project version</a>
                    @else
                        <a href="/project_version_features/create_feature/0/{{ $version_id }}" class="btn btn-outline-success btn-rounded col-md-12">Create your first feature for this project version</a>
                    @endif
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
                        nested_features_id.text('Hide Nested Features');
                        nested_features_div.html(response);
                    }
                });
            } else {
                nested_features_id.text('View Nested Features');
                nested_features_div.html('');
            }
        }
    </script>
@endpush
