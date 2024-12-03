@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">

<div class="restaurant-card">

    <img src="{{ $restaurant->photo_restaurant }}" alt="Image de {{ $restaurant->nom_etablissement }}"
        class="restaurant-image">
    <div class="restaurant-details">
        <h3>{{ $restaurant->nom_etablissement }}</h3>
        <p><strong>Description : </strong> Restaurant proposant {{ $restaurant->description_etablissement }}</p>
        <p><strong>Adresse : </strong>{{ $restaurant->rue }}, {{ $restaurant->cp }} {{ $restaurant->ville }}</p>
        <p><strong>Livraison :</strong> {{ $restaurant->propose_livraison ? 'Oui' : 'Non' }}</p>
        <p><strong>À emporter :</strong> {{ $restaurant->propose_retrait ? 'Oui' : 'Non' }}</p>
        <p><strong>Catégorie :</strong> {{ $restaurant->lib_categorie ?? 'Non spécifiée' }}</p>
        <p><strong>Horaires :</strong>
            {{ date('H:i', strtotime($restaurant->horaires_ouverture)) }} -
            {{ date('H:i', strtotime($restaurant->horaires_fermeture)) }}
        </p>
    </div>
</div>

</section>
<section class="menus-container">
    <h2>Menus disponibles</h2>
    <div class="menus">

        @if ($menus->isEmpty())
            <p>Aucun menu disponible pour ce restaurant.</p>
        @else
            @foreach ($menus as $menu)
                <div class="menu-card">
                    <img src="{{ $menu->photo_menu }}" alt="Image de {{ $menu->photo_menu }}">
                    <h3>{{ $menu->libelle_menu }}</h3>
                    <p>Prix : {{ $menu->prix_menu }} €</p>
                </div>
            @endforeach

        @endif
    </div>
</section>

<section class="menus-container">
    <h2>Plats disponibles</h2>
    <div class="menus">

        @if ($plats->isEmpty())
            <p>Aucun plats disponible pour ce restaurant.</p>
        @else
            @foreach ($plats as $plat)
                <div class="menu-card">
                    <img src="{{ $plat->photo_plat }}" alt="Image de {{ $plat->photo_plat }}">
                    <h3>{{ $plat->libelle_plat }}</h3>
                    <p>Prix : {{ $plat->prix_plat }} €</p>
                    <p>Note : {{ $plat->note_plat }}</p>
                    <p>{{ $plat->nb_avis }} avis</p>
                </div>
            @endforeach

        @endif
    </div>
</section>

<script src="{{ asset('js/main.js') }}"></script>
