<link href="{{ asset('style/app.css') }}" rel="stylesheet">
<a href="{{ url('/') }}" class="back-button">
    <p>Retour</p>
</a>

<form action="{{ route('restaurants.search') }}" method="GET" class="search-form">
    <input type="text" name="search" placeholder="Rechercher par Nom ou par Ville" value="{{ $query ?? '' }}"
        class="search-input">
    <button type="submit" class="search-button">Rechercher</button>
</form>

@if (isset($restaurants) && $restaurants->isNotEmpty())
    <section class="restaurants-list">
        @foreach ($restaurants as $restaurant)
            <div class="restaurant-card">
                <h3 class="restaurant-name">{{ $restaurant->nom_etablissement }}</h3>
                <p><strong>Ville:</strong> {{ $restaurant->ville }}</p>
                <p><strong>Livraison:</strong> {{ $restaurant->propose_livraison ? 'Oui' : 'Non' }}</p>
                <p><strong>À emporter:</strong> {{ $restaurant->propose_retrait ? 'Oui' : 'Non' }}</p>
            </div>
        @endforeach
    </section>
@else
    <p>Aucun restaurant trouvé.</p>
@endif

<!-- c qui -->
