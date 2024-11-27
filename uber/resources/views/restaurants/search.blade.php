<a href="{{ url("/") }}">
    <p>arrière</p>
</a>

<form action="{{ route('restaurants.search') }}" method="GET">
    <input type="text" name="search" placeholder="Rechercher par Nom ou par ville" value="{{ $query ?? '' }}">
    <button type="submit">Rechercher</button>
</form>


@if(isset($restaurants))
    @foreach($restaurants as $restaurant)
        <div>
            <h3>{{ $restaurant->nom_etablissement }}</h3>
            <p>Ville: {{ $restaurant->ville }}</p>
            <p>Livraison : {{ $restaurant->propose_livraison ? 'Oui' : 'Non' }}</p>
            <p>À emporter : {{ $restaurant->propose_retrait ? 'Oui' : 'Non' }}</p>
        </div>
    @endforeach
@endif