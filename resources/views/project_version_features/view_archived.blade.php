@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Archived Project Version Features</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">View Archived Project Version Features</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Project</th>
                                <th>Project Version</th>
                                <th>Name</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($features as $feature)
                                <tr>
                                    <td>{{ get_name(get_name($feature->version_id, 'id', 'project_id', 'project_versions'), 'id', 'name', 'projects') }}</td>
                                    <td>{{ get_name($feature->version_id, 'id', 'name', 'project_versions') }}</td>
                                    <td>{{ $feature->title }}</td>
                                    <td><a href="/project_version_features/restore/{{ $feature->id }}" class="btn btn-primary btn-sm">Restore</a></td>
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
