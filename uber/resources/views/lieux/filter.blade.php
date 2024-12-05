@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">

<form action="{{ route('restaurants.search') }}" method="GET" class="filter-form">
    <div class="form-group">
        <label for="lieu" class="form-label">Rechercher par ville ou par nom :</label>
        <input type="text" id="lieu" name="lieu" class="form-input"
            placeholder="Rechercher par Nom ou par Ville" value="{{ $lieu ?? '' }}">
    </div>

    <div class="form-group checkboxes">
        <p>Mode de livraison</p>
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
    <input type="hidden" id="horaire-selected" name="horaire-selected" value="">
    <button type="submit" class="btn-submit">Rechercher</button>
</form>

<section class="restaurants-list">
    @if (isset($lieux) && $lieux->isNotEmpty())
        @foreach ($lieux as $lieu)
            <a href="{{ route('lieux.show', $lieu->id_lieu_de_vente_pf) }}" class="restaurant-card">
                <img src="{{ $lieu->photo_lieu }}" alt="Image de {{ $lieu->nom_etablissement }}"
                    class="restaurant-image">
                <div class="restaurant-details">
                    <h3>{{ $lieu->nom_etablissement }}</h3>
                    <p><strong>Ville :</strong> {{ $lieu->ville }}</p>
                    <p><strong>Livraison :</strong> {{ $lieu->propose_livraison ? 'Oui' : 'Non' }}</p>
                    <p><strong>Horaires :</strong>
                        {{ date('H:i', strtotime($lieu->horaires_ouverture)) }} -
                        {{ date('H:i', strtotime($lieu->horaires_fermeture)) }}
                    </p>
                </div>
            </a>
        @endforeach
    @elseif(isset($lieux))
        <p class="no-results">Aucun lieu ne correspond à vos critères.</p>
    @endif
</section>
<script src="{{ asset('js/main.js') }}"></script>
