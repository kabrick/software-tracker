@extends('layouts.app')

@push('styles')
    <style>
        .carousel-caption {
            position: relative;
            left: 0;
            top: 0;
        }
    </style>
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

                    @php $steps = $guide->steps()->get(); $counter = 0;@endphp

                    <div id="steps_carousel" class="carousel slide" data-ride="carousel">
                        <ul class="carousel-indicators">
                            @foreach($steps as $step)
                                <li data-target="#steps_carousel" data-slide-to="{{ $counter }}" @if($counter == 0) class="active" @endif></li>
                                @php $counter++ @endphp
                            @endforeach
                        </ul>

                        <div class="carousel-inner">
                            @php $counter = 1;@endphp
                            @foreach($steps as $step)
                                <div class="carousel-item @if($counter == 1) active @endif">
                                    <img src="{{ $step->images }}" alt="image" width='950' height='550' class="img-center">
                                    <div class="carousel-caption d-none d-md-block bg-dark mb-4" style="margin-top: 20px">
                                        <h3>Step {{ $counter }}</h3>
                                        <p>{{ $step->description }}</p>
                                    </div>
                                </div>
                                @php $counter++ @endphp
                            @endforeach
                        </div>

                        <a class="carousel-control-prev bg-dark" href="#steps_carousel" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a class="carousel-control-next bg-dark" href="#steps_carousel" data-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script></script>
@endpush
