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
                        <div class="col-md-10">
                            <h4>Description</h4>

                            <p>{{ $project_version->description }}</p>

                            @php
                            $contact_names = explode(",", $project_version->contact_names);
                            $contact_phones = explode(",", $project_version->contact_phones);
                            $contact_emails = explode(",", $project_version->contact_emails);
                            @endphp

                            <h4>Project Contacts</h4>

                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Email</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @for($i = 0; $i < count($contact_names); $i++)
                                        <tr>
                                            <td>{{ $contact_names[$i] }}</td>
                                            <td>{{ $contact_phones[$i] }}</td>
                                            <td>{{ $contact_emails[$i] }}</td>
                                        </tr>
                                    @endfor
                                    </tbody>
                                </table>
                            </div>

                            <br><br>

                            <a href="/project_version_features/view_features/{{ $project_version->id }}" class="btn btn-outline-info btn-rounded col-md-12">View Project Version Features</a>
                        </div>
                        <div class="col-md-2">
                            <a href="/project_versions/{{ $project_version->id }}/edit" class="btn btn-outline-primary btn-rounded col-md-12">Edit</a>

                            <hr>

                            <a href="#" class="btn btn-outline-success btn-rounded col-md-12">Clone</a>

                            <hr>

                            <a href="/project_versions/archive_version/{{ $project_version->id }}/" class="btn btn-outline-warning btn-rounded col-md-12" onclick="return confirm('Are you sure you want to archive this project version')">Archive</a>

                            <hr>

                            <a href="/project_versions/delete_version/{{ $project_version->id }}/" class="btn btn-outline-danger btn-rounded col-md-12" onclick="return confirm('Are you sure you want to permanently delete this project version')">Delete</a>
                        </div>
                    </div>

                    <hr>

                    <h4>Guides</h4>

                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn-icon-clipboard" title="Add New Guide" href="/project_versions/create_guide/{{ $project_version->id }}">
                                <div>
                                    <i class="ni ni-fat-add"></i>
                                    <span>
                                        <h3>Add New Guide</h3>
                                        <p>Add a new guide to help users perform actions in your application</p>
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            @if(count($guides) > 0)
                                <a class="btn-icon-clipboard" title="{{ $guides[0]->title }}" href="/project_versions/publish_guide/{{ $guides[0]->id }}">
                                    <div>
                                        <i class="ni ni-glasses-2"></i>
                                        <span>
                                        <h3>{{ $guides[0]->title }}</h3>
                                        <p>{{ $guides[0]->description }}</p>
                                    </span>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>

                    @php $counter = 1; @endphp

                    @while($counter < count($guides))
                        <div class="row">
                            <div class="col-md-6">
                                @if(isset($guides[$counter]))
                                    <a class="btn-icon-clipboard" title="{{ $guides[$counter]->title }}" href="/project_versions/publish_guide/{{ $guides[$counter]->id }}">
                                        <div>
                                            <i class="ni ni-folder-17"></i>
                                            <span>
                                        <h3>{{ $guides[$counter]->title }}</h3>
                                        <p>{{ $guides[$counter]->description }}</p>
                                    </span>
                                        </div>
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if(isset($guides[$counter + 1]))
                                    <a class="btn-icon-clipboard" title="{{ $guides[$counter + 1]->title }}" href="/project_versions/publish_guide/{{ $guides[$counter + 1]->id }}">
                                        <div>
                                            <i class="ni ni-folder-17"></i>
                                            <span>
                                        <h3>{{ $guides[$counter + 1]->title }}</h3>
                                        <p>{{ $guides[$counter + 1]->description }}</p>
                                    </span>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>

                        @php $counter += 2; @endphp
                    @endwhile

                    @if($guides_count > 3)
                        <div id="guides_container"></div>
                        <br>
                        <a href="#view_more_guides" id="view_more_guides">View More Guides</a>
                    @endif
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
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
