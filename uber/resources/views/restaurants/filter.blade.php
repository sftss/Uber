<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">

<a href="{{ url('/') }}" class="back-button">
    <p>Retour</p>
</a>

<form action="{{ route('restaurants.filter') }}" method="GET" class="filter-form">
    <div class="form-group">
        <label for="lieu" class="form-label">Rechercher par ville :</label>
        <input type="text" id="lieu" name="lieu" class="form-input"
            placeholder="Rechercher par Nom ou par Ville" value="{{ $lieu ?? '' }}">
    </div>

    <div class="form-group checkboxes">
        <p> Mode de livraison</p>
        <div>
            <input type="checkbox" id="livre" name="livre" {{ request('livre') ? 'checked' : '' }}>
            <label for="livre">Livraison</label>
        </div>
        <div>
            <input type="checkbox" id="emporter" name="emporter" {{ request('emporter') ? 'checked' : '' }}>
            <label for="emporter">À emporter</label>
        </div>
    </div>

    <div class="form-group">
        <label for="categorie" class="form-label">Catégorie de restaurant :</label>
        <select id="categorie" name="categorie" class="form-input">
            <option value="">Toutes les catégories</option>
            @foreach ($categories as $categorie)
                <option value="{{ $categorie->id_categorie }}"
                    {{ request('categorie') == $categorie->id_categorie ? 'selected' : '' }}>
                    {{ $categorie->lib_categorie }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn-submit">Rechercher</button>
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
                    <p><strong>Catégorie :</strong> {{ $restaurant->lib_categorie ?? 'Non spécifiée' }}</p>
                </div>
            </div>
        @endforeach
    @elseif(isset($restaurants))
        <p class="no-results">Aucun restaurant ne correspond à vos critères.</p>
    @endif
</section>
