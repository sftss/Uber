@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">

<div class="txtFilter">Une envie soudaine&nbsp;?<br>Régalez-vous de suite.</div>

<form action="{{ route('restaurants.search') }}" method="GET" class="filter-form">
    <div class="form-group">
        <label for="lieu" class="form-label">Rechercher par ville ou par nom :</label>
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

        <div class="planifier">
            <h2 class="planifier-header"><span>Planifier</span>
                <span class="toggle-arrow">➤</span>
            </h2>
            <div class="interface-planif" style="display: none;">
                <div class="jours">
                    <!-- génération jour -->
                </div>
                <div class="horraires">
                    <!-- génération heure -->
                </div>
            </div>
        </div>

    </div>
    <input type="hidden" id="horaire-selected" name="horaire-selected" value="">
    <button type="submit" class="btn-submit">Rechercher</button>
</form>

<section class="restaurants-list">
    @if (isset($restaurants) && $restaurants->isNotEmpty())
        @foreach ($restaurants as $restaurant)
            <a href="{{ route('restaurants.show', $restaurant->id_restaurant) }}" class="restaurant-card">
                <img src="{{ $restaurant->photo_restaurant }}" alt="Image de {{ $restaurant->nom_etablissement }}"
                    class="restaurant-image">
                <div class="restaurant-details">
                    <h3>{{ $restaurant->nom_etablissement }}</h3>
                    <p><strong>Ville :</strong> {{ $restaurant->ville }}</p>
                    <p><strong>Livraison :</strong> {{ $restaurant->propose_livraison ? 'Oui' : 'Non' }}</p>
                    <p><strong>À emporter :</strong> {{ $restaurant->propose_retrait ? 'Oui' : 'Non' }}</p>
                    <p><strong>Catégorie :</strong> {{ $restaurant->lib_categorie ?? 'Non spécifiée' }}</p>
                    <p><strong>Horaires :</strong>
                        {{ date('H:i', strtotime($restaurant->horaires_ouverture)) }} -
                        {{ date('H:i', strtotime($restaurant->horaires_fermeture)) }}
                    </p>
                </div>

            </a>
        @endforeach
    @elseif(isset($restaurants))
        <p class="no-results">Aucun restaurant ne correspond à vos critères.</p>
    @endif
</section>

<script src="{{ asset('js/main.js') }}"></script>
