@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">View Archived Project Version Guides</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Project</th>
                                <th>Project Version</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($guides as $guide)
                                <tr>
                                    <td>{{ get_name(get_name($guide->version_id, 'id', 'project_id', 'project_versions'), 'id', 'name', 'projects') }}</td>
                                    <td>{{ get_name($guide->version_id, 'id', 'name', 'project_versions') }}</td>
                                    <td>{{ $guide->title }}</td>
                                    <td>{{ $guide->description }}</td>
                                    <td><a href="/project_versions/restore_guide/{{ $guide->id }}" class="btn btn-primary btn-sm">Restore</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
@endpush
