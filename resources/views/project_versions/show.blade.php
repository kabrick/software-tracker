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
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
@endpush
