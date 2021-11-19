@extends('layouts.app')

@push('styles')
    <style></style>
@endpush

@section('content')

    <div class="row justify-content-center">
        <div class=" col ">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="/projects/{{ get_name($guide->version_id, 'id', 'project_id', 'project_versions') }}">{{ get_name(get_name($guide->version_id, 'id', 'project_id', 'project_versions'), 'id', 'name', 'projects') }}</a></li>
                    <li class="breadcrumb-item"><a href="/project_versions/{{ $guide->version_id }}">{{ get_name($guide->version_id, 'id', 'name', 'project_versions') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Guide</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">{{ $guide->title }}</h3>
                </div>
                <div class="card-body">
                    @include('flash::message')

                    <div class="form-group">
                        <label><b>Guide Description</b></label>
                        <p>{{ $guide->description }}</p>
                    </div>

                    <br><br>

                    <div class="row">
                        <div class="col-md-5"><hr></div>
                        <div class="col-md-2 text-center">
                            <h2>Steps</h2>
                        </div>
                        <div class="col-md-5"><hr></div>
                    </div>

                    <br><br>

                    @php $steps = $guide->steps()->get(); $counter = 1; @endphp

                    @foreach($steps as $step)
                        @if($counter > 1) <hr> @endif
                        <div class='row'>
                            <div class='col-md-6'>
                                <img src='{{ $step->images }}' width='600' height='250' alt='image' id='image_preview1' class="modal-image">
                            </div>
                            <div class='col-md-6'>
                                <h3>Step {{ $counter }}</h3>

                                <p>{{ $step->description }}</p>
                            </div>
                        </div>

                        @php $counter++ @endphp
                        <br><br>
                    @endforeach

                    <div class="row">
                        <div class="col-md-2">
                            <a href="/project_versions/edit_guide/{{ $guide->id }}" class="btn btn-outline-primary btn-rounded col-md-12">Edit</a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-success btn-rounded col-md-12" onclick="clone_guide()">Clone To Another Project Version</a>
                        </div>
                        <div class="col-md-3">
                            <a href="/project_versions/archive_guide/{{ $guide->id }}/" class="btn btn-outline-warning btn-rounded col-md-12" onclick="return confirm('Are you sure you want to archive this guide')">Archive</a>
                        </div>
                        <div class="col-md-2">
                            <a href="/project_versions/delete_guide/{{ $guide->id }}/" class="btn btn-outline-danger btn-rounded col-md-12" onclick="return confirm('Are you sure you want to permanently delete this guide')">Delete</a>
                        </div>
                        <div class="col-md-2">
                            <a href="/project_versions/present_guide/{{ $guide->id }}/" class="btn btn-outline-default btn-rounded col-md-12">Present Guide</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="modal-title-default">Clone Guide</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h4>Choose a project version to clone the guide to</h4>
                    <hr>

                    @foreach($project_versions as $project_version)
                        <div class="custom-control custom-radio mb-3">
                            <input type="radio" id="project_version_clone{{ $project_version->id }}" name="project_version_clone" value="{{ $project_version->id }}" class="custom-control-input project_version_clone">
                            <label class="custom-control-label" for="project_version_clone{{ $project_version->id }}">{{ $project_version->name }}</label>
                        </div>
                    @endforeach
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="complete_clone()">Clone</button>
                    <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Cancel</button>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        let guide_id = <?php echo $guide->id; ?>;

        function clone_guide() {
            if (confirm("Are you sure you want to clone this guide into another project version?")) {
                $('#modal-default').modal('show');
            }
        }

        function complete_clone() {
            let val = $(".project_version_clone:checked").val();

            if (val === undefined) {
                alert("Please select a project version to clone this guide to");
            } else {
                $.ajax({
                    method: 'GET',
                    url: '/project_versions/clone_guide/' + guide_id + '/' + val,
                    success: function(response){
                        alert(response);
                        $('#modal-default').modal('hide');
                    }
                });
            }
        }
    </script>
@endpush
