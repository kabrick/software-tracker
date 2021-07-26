@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
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

                            <a href="#" class="btn btn-outline-info btn-rounded col-md-12">View Project Version Features</a>
                        </div>
                        <div class="col-md-2">
                            <a href="/project_versions/{{ $project_version->id }}/edit" class="btn btn-outline-primary btn-rounded col-md-12">Edit</a>

                            <hr>

                            <a href="#" class="btn btn-outline-success btn-rounded col-md-12">Clone</a>

                            <hr>

                            <a href="/project_versions/archive_version/{{ $project_version->id }}/" class="btn btn-outline-warning btn-rounded col-md-12">Archive</a>

                            <hr>

                            <a href="/project_versions/delete_version/{{ $project_version->id }}/" class="btn btn-outline-danger btn-rounded col-md-12">Delete</a>
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
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
@endpush
