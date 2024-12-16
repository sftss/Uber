@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}

        <a href="{{ route('cart.index') }}" class="btn btn-outline-light">Voir mon panier</a>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<a href="{{ url('/lieux/search') }}">
    <p>Retour</p>
</a>

<div class="restaurant-card">
    <img src="{{ $lieu->photo_lieu }}" alt="Image de {{ $lieu->nom_etablissement }} " class="restaurant-image">
    <div class="lieu-vente-details">
        <h3>{{ $lieu->nom_etablissement }}</h3>
        <p><strong>Ville :</strong> {{ $lieu->ville }}</p>
        <p><strong>Livraison :</strong> {{ $lieu->propose_livraison ? 'Oui' : 'Non' }}</p>
        <p><strong>Horaires :</strong>
            {{ date('H:i', strtotime($lieu->horaires_ouverture)) }} -
            {{ date('H:i', strtotime($lieu->horaires_fermeture)) }}
        </p>
    </div>
</div>

<form method="GET" action="{{ route('lieux.show', $lieu->id_lieu_de_vente_pf) }}" class="filter-form">
    <div class="form-group-restau">
        <label for="produit" class="form-label">Rechercher un produit</label>
        <input type="text" id="produit" name="produit" value="{{ request('produit') }}" class="form-input"
            placeholder="Nom du produit">
    </div>
    <div class="form-group-restau">
        <label for="categorie" class="form-label">Catégorie</label>
        <select id="categorie" name="categorie" class="form-input">
            <option value="">-- Sélectionner une catégorie --</option>
            @foreach ($categories as $categorie)
                <option value="{{ $categorie->id_categorie_produit }}"
                    {{ request('categorie') == $categorie->id_categorie_produit ? 'selected' : '' }}>
                    {{ $categorie->libelle_categorie }}
                </option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Rechercher</button>
</form>

<section class="menus-container">
    <h2>Produits disponibles</h2>
    <div class="menus">
        @if ($produits->isNotEmpty())
            @foreach ($produits as $produit)
                <div class="menu-card">
                    <img src="{{ $produit->photo_produit }}" alt="">
                    <h4>{{ $produit->nom_produit }}</h4>
                    <p><strong>Catégorie :</strong> {{ $produit->libelle_categorie }} </p>
                    <p><strong>Prix :</strong> {{ $produit->prix_produit }} €</p>
                    <form action="{{ route('cart.add', ['type' => 'produit', 'id' => $produit->id_produit]) }}"
                        method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary ajtpanier">Ajouter au panier</button>
                    </form>
                </div>
            @endforeach
        @else
            <p class="no-results">Aucun produit disponible dans ce lieu de vente.</p>
        @endif
    </div>
</section>
<a href="{{ url('/panier') }}" id="panier">🛒</a>

<script src="{{ asset('js/main.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Sélectionner l'alerte
        const alert = document.querySelector('.alert');
        if (alert) {
            // Attendre 5 secondes (5000 ms) avant de masquer l'alerte
            setTimeout(() => {
                alert.classList.add('hide');
            }, 5000);
        }
    });
</script>
