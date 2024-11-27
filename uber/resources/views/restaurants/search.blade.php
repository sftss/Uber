<link href="{{ asset('style/app.css') }}" rel="stylesheet">
<a href="{{ url('/') }}" class="back-button">
    <p>Retour</p>
</a>

<form action="{{ route('restaurants.search') }}" method="GET" class="search-form">
    <input type="text" name="search" placeholder="Rechercher par Nom ou par Ville" value="{{ $query ?? '' }}"
        class="search-input">
    <button type="submit" class="search-button">Rechercher</button>
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


<!-- c qui -->
<!--  toi t'es qui-->
<!-- sefer -->
<!-- c berkan -->
<!-- bg c que ta fait   mais ta niqué mon style sur les boutons retour-->
<!-- ouais y'avais un conflit j'ai plus touché après tu peux le remettre stv-->
<!-- on voit demain au pire jvais nehess-->
<!-- azy il reste que le bail de période a mettre bonne nuit-->