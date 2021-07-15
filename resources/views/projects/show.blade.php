@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">{{ $project->name }} Project Details</h3>
                </div>
                <div class="card-body">
                    <h4>Description</h4>

                    <p>{{ $project->description }}</p>

                    <hr>

                    <h4>Project Versions</h4>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
@endpush
