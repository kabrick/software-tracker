<html>
<head>
    <title>{{ $guide->title }}</title>
</head>

<tbody>
    <div class="form-group">
        <label><b>Guide Description</b></label>
        <p>{{ $guide->description }}</p>
    </div>

    <br><br>

    <h2>Steps</h2>

    <br><br>

    @php $steps = $guide->steps()->get(); $counter = 1; @endphp

    @foreach($steps as $step)
        @if($counter > 1) <hr> @endif
        <h3>Step {{ $counter }}</h3>

        <img src='{{ asset($step->images) }}' height='350' alt='image'>

        <p>{{ $step->description }}</p>

        @php $counter++ @endphp
        <br><br>
    @endforeach
</tbody>
</html>
