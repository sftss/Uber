<form action="{{ route('restaurants.search') }}" method="GET">
    <input type="text" name="search" placeholder="Search by restaurant or town" value="{{ $query ?? '' }}">
    <button type="submit">Search</button>
</form>

@if(isset($restaurants))
    @foreach($restaurants as $restaurant)
        <div>
            <h3>{{ $restaurant->name }}</h3>
            <p>Town: {{ $restaurant->town }}</p>
        </div>
    @endforeach
@endif