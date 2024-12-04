@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">


<a href="{{ url('/lieux/search')}}"><p>Retour</p></a>

<div class="restaurant-card">
    <img src="{{ $lieu->photo_lieu}}" alt="Image de {{$lieu->nom_etablissement}} ">
    <h3>{{ $lieu->nom_etablissement }}</h3>
    <p><strong>Ville :</strong> {{ $lieu->ville }}</p>
    <p><strong>Livraison :</strong> {{ $lieu->propose_livraison ? 'Oui' : 'Non' }}</p>
    <p><strong>Horaires :</strong>
    {{ date('H:i', strtotime($lieu->horaires_ouverture)) }} -
    {{ date('H:i', strtotime($lieu->horaires_fermeture)) }}
</p>
</div>




<section class="menus-container">
    <h2>Plats disponibles</h2>
    <div class="menus">
    @if ($produits->isNotEmpty())
        @foreach ($produits as $produit)
            <div class="menu-card">
                <img src="{{$produit->photo_produit}}" alt="">
                <h4>{{ $produit->nom_produit }}</h4>
                <p><strong>Prix :</strong> {{ $produit->prix_produit }} €</p>
            </div>
        @endforeach
    @else
        <p class="no-results">Aucun produit disponible dans ce lieu de vente.</p>
    @endif
</div>
</section>

<script src="{{ asset('js/main.js') }}"></script>
