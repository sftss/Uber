<!--<link href="{{ asset('style/app.css') }}" rel="stylesheet">

<a href="{{ url('/') }}">
    <p>arrière</p>
</a>

<form action="{{ route('restaurants.search') }}" method="GET">
    <input type="text" name="search" placeholder="Search by restaurant or town" value="{{ $query ?? '' }}">
    <button type="submit">Search</button>
</form>

<div>
    <input type="checkbox" id="livre" name="livre" />
    <label for="livre">Livraison</label>

    <input type="checkbox" id="Click&Collect" name="Click&Collect" />
    <label for="Click&Collect">Click&Collect</label>
</div


@if (isset($restaurants))
@foreach ($restaurants as $restaurant)
<div>
            <h3>{{ $restaurant->nom_etablissement }}</h3>
            <p>Town: {{ $restaurant->ville }}</p>
        </div>
@endforeach
@endif
-->

<link href="{{ asset('style/app.css') }}" rel="stylesheet">

<a href="{{ url('/') }}">
    <p>arrière</p>
</a>

<form action="{{ route('restaurants.filter') }}" method="GET">
    <input type="text" name="lieu" placeholder="Search by town" value="{{ $lieu ?? '' }}">

    <div>
        <input type="checkbox" id="livre" name="livre" {{ request('livre') ? 'checked' : '' }} />
        <label for="livre">Livraison</label>

        <input type="checkbox" id="emporter" name="emporter" {{ request('emporter') ? 'checked' : '' }} />
        <label for="emporter">À emporter</label>
    </div>

    <button type="submit">Search</button>
</form>


<section class="restaurants-list">
    @if (isset($restaurants) && $restaurants->isNotEmpty())
        @foreach ($restaurants as $restaurant)
            <div class="restaurant-card">
                <img src="{{ $restaurant->photo_restaurant }}" alt="Image de {{ $restaurant->nom_etablissement }}"
                    class="restaurant-image">
                <div class="restaurant-details">
                    <h3>{{ $restaurant->nom_etablissement }}</h3>
                    <p><strong>Ville :</strong> {{ $restaurant->ville }}</p>
                    <p><strong>Livraison :</strong> {{ $restaurant->propose_livraison ? 'Oui' : 'Non' }}</p>
                    <p><strong>À emporter :</strong> {{ $restaurant->propose_retrait ? 'Oui' : 'Non' }}</p>
                </div>
            </div>
        @endforeach
    @elseif(isset($restaurants))
        <p class="no-results">Aucun restaurant ne correspond à vos critères.</p>
    @endif
</section>
