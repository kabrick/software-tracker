@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="/projects/{{ $project_version->project_id }}">{{ get_name($project_version->project_id, 'id', 'name', 'projects') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Project Version Details</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">{{ $project_version->name }} Project Version Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>Description</h4>

                            <p>{{ $project_version->description }}</p>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="/project_versions/{{ $project_version->id }}/edit" class="btn btn-outline-primary btn-rounded col-md-12">Edit</a>
                                </div>
                                <div class="col-md-6">
                                    <a href="/project_versions/clone/{{ $project_version->id }}" class="btn btn-outline-success btn-rounded col-md-12" onclick="return confirm('Are you sure you want to clone this project version? This will replicate all the guides and features included.')">Clone</a>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="/project_versions/archive_version/{{ $project_version->id }}/" class="btn btn-outline-warning btn-rounded col-md-12" onclick="return confirm('Are you sure you want to archive this project version?')">Archive</a>
                                </div>
                                <div class="col-md-6">
                                    <a href="/project_versions/delete_version/{{ $project_version->id }}/" class="btn btn-outline-danger btn-rounded col-md-12" onclick="return confirm('Are you sure you want to permanently delete this project version?')">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h4>Latest Project Version Module Features</h4>

                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn-icon-clipboard" title="Add New Guide" href="/project_version_modules/view/{{ $project_version->id }}">
                                <div>
                                    <i class="ni ni-fat-add"></i>
                                    <span>
                                        <h3>View Modules</h3>
                                        <p>Create and manage existing project version modules as well as managing module features</p>
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            @if(count($project_features) > 0)
                                <a class="btn-icon-clipboard" title="{{ $project_features[0]->title }}" href="/project_version_features/feature_details/{{ $project_features[0]->id }}">
                                    <div>
                                        <i class="ni ni-folder-17"></i>
                                        <span>
                                        <h3>{{ $project_features[0]->title }}</h3>
                                        <p>Last updated By {{ $project_features[0]->name }} {{ \Carbon\Carbon::parse($project_features[0]->updated_at)->fromNow() }}</p>
                                    </span>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>

                    @php $counter = 1; @endphp

                    @while($counter < count($project_features))
                        <div class="row">
                            <div class="col-md-6">
                                @if(isset($project_features[$counter]))
                                    <a class="btn-icon-clipboard" title="{{ $project_features[$counter]->title }}" href="/project_version_features/feature_details/{{ $project_features[$counter]->id }}">
                                        <div>
                                            <i class="ni ni-folder-17"></i>
                                            <span>
                                        <h3>{{ $project_features[$counter]->title }}</h3>
                                        <p>Last updated By {{ $project_features[$counter]->name }} {{ \Carbon\Carbon::parse($project_features[$counter]->updated_at)->fromNow() }}</p>
                                    </span>
                                        </div>
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if(isset($project_features[$counter + 1]))
                                    <a class="btn-icon-clipboard" title="{{ $project_features[$counter + 1]->title }}" href="/project_version_features/feature_details/{{ $project_features[$counter + 1]->id }}">
                                        <div>
                                            <i class="ni ni-folder-17"></i>
                                            <span>
                                        <h3>{{ $project_features[$counter + 1]->title }}</h3>
                                        <p>Last updated By {{ $project_features[$counter + 1]->name }} {{ \Carbon\Carbon::parse($project_features[$counter + 1]->updated_at)->fromNow() }}</p>
                                    </span>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>

                        @php $counter += 2; @endphp
                    @endwhile

                    <br><br>

                    <a href="/project_version_features/view_features/{{ $project_version->id }}" class="btn btn-default btn-rounded btn-sm text-white">View All Features</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        let last_guide_id = 1;
        let id = <?php echo $project_version->id; ?>;

        $('#view_more_guides').click(function () {
            $.ajax({
                method: 'POST',
                url: '/project_versions/view_more_guides',
                data: {'id': id, 'last_guide_id': last_guide_id},
                success: function(response){
                    let responseArray = JSON.parse(response);

                    last_guide_id = responseArray["last_guide_id"];
                    $("#guides_container").append(responseArray["html"]);

                    window.location.href = '#view_more_guides';
                }
            });
        });
    </script>
@endpush
