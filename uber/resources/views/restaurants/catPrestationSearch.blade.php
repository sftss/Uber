<link href="{{ asset('style/app.css') }}" rel="stylesheet">

<a href="{{ url("/") }}">
    <p>arrière</p>
</a>

<form action="{{ route('restaurants.search') }}" method="GET">
    <input type="text" name="search" placeholder="Search by restaurant or town" value="{{ $query ?? '' }}">
    <button type="submit">Search</button>
</form>


@if(isset($restaurants))
    @foreach($restaurants as $restaurant)
        <div>
            <h3>{{ $restaurant->nom_etablissement }}</h3>
            <p>Town: {{ $restaurant->ville }}</p>
        </div>
    @endforeach
@endif
