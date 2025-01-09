@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">

<div class="restaurant-card">

    <img src="{{ $restaurant->photo_restaurant }}" alt="Image de {{ $restaurant->nom_etablissement }}"
        class="restaurant-image">
    <div class="restaurant-details">

        {{-- <?php dd($restaurant); ?> --}}
        <h3>{{ $restaurant->nom_etablissement }}</h3>
        <p><strong>Description : </strong> {{ $restaurant->description_etablissement }}</p>
        <p><strong>Adresse : </strong>{{ $restaurant->rue }} {{ $restaurant->cp }} {{ $restaurant->ville }}</p>
        <p><strong>Livraison :</strong> {{ $restaurant->propose_livraison ? 'Oui' : 'Non' }}</p>
        <p><strong>À emporter :</strong> {{ $restaurant->propose_retrait ? 'Oui' : 'Non' }}</p>
        <p><strong>Catégorie :</strong> {{ $restaurant->lib_categorie ?? 'Non spécifiée' }}</p>
        <p><strong>Horaires:</strong>
            @if ($horaires->isNotEmpty())
                <table>
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Horaires</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($horaires as $horaire)
                            <tr>
                                <td>{{ ucfirst($horaire->jour) }}</td>
                                <td>
                                    @if ($horaire->horaires_ouverture && $horaire->horaires_fermeture)
                                        {{ date('H:i', strtotime($horaire->horaires_ouverture)) }} -
                                        {{ date('H:i', strtotime($horaire->horaires_fermeture)) }}
                                    @else
                                        Fermé
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Les horaires ne sont pas disponibles pour ce restaurant.</p>
            @endif
        </p>
    </div>
</div>

<div class="createProprio">
    @if (auth()->check() && $restaurant->id_proprietaire == auth()->user()->id_client)
        <p>Vous êtes le propriétaire</p>
        <div class="createProprioBut">
            <a href="{{ route('produit.create', ['restaurant_id' => $restaurant->id_restaurant]) }}"
                class="btn btn-primary">Ajouter un produit</a>
            <a href="{{ route('plat.create', ['restaurant_id' => $restaurant->id_restaurant]) }}"
                class="btn btn-primary">Ajouter un plat</a>
            <a href="{{ route('menu.create', ['restaurant_id' => $restaurant->id_restaurant]) }}"
                class="btn btn-primary">Ajouter un menu</a>
        </div>
        <a href="{{ route('restaurants.affichercommandes', ['id' => $restaurant->id_restaurant]) }}"
                class="btn btn-primary">Afficher les commandes en cours</a>
    @endif
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
        <a href="{{ route('cart.index') }}" class="btn btn-outline-light">Voir mon panier</a>
    </div>
@endif

<form id="formRechercherProd" method="GET" action="{{ route('restaurants.show', $restaurant->id_restaurant) }}">
    <div class="RechercherProduitMagasin">
        <label for="search">Recherche</label>
        <input type="text" id="search" name="recherche" value="{{ old('recherche') }}"
            placeholder="Rechercher dans les menus, plats, produits...">
    </div>
    <div class="RechercherProduitMagasin">
        <label for="categorie">Catégorie de produit</label>
        <select name="categorie" id="categorie">
            <option value="">Sélectionner une catégorie</option>
            @foreach ($categoriesProduits as $cat)
                <option value="{{ $cat->id_categorie_produit }}"
                    {{ old('categorie', $categorieId ?? '') == $cat->id_categorie_produit ? 'selected' : '' }}>
                    {{ $cat->libelle_categorie }}
                </option>
            @endforeach
        </select>
    </div>
    <button class="filter-but-eat" style="margin-top: 2%;" type="submit">Filtrer</button>
</form>

<a href="{{ url('/panier') }}" id="panier">🛒</a>

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
                    <p>Catégorie : {{ $menu->categorie_produit }} </p>
                    <p>Prix : {{ $menu->prix_menu }} €</p>
                    <form action="{{ route('cart.add', ['type' => 'menu', 'id' => $menu->id_menu]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary ajtpanier">Ajouter au panier</button>
                    </form>
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
                    <p>Catégorie : {{ $plat->categorie_plat }} </p>
                    <p>Prix : {{ $plat->prix_plat }} €</p>
                    
                    @if($plat->note_plat)
                        <p>Note : {{ $plat->note_plat }}</p>
                    @else
                        <p>Note : Aucune note disponible</p>
                    @endif

                    @if($plat->nb_avis)
                        <p>{{ $plat->nb_avis }} avis</p>
                    @else
                        <p>Aucun avis disponible</p>
                    @endif

                    <form action="{{ route('cart.add', ['type' => 'plat', 'id' => $plat->id_plat]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary ajtpanier">Ajouter au panier</button>
                    </form>
                </div>
            @endforeach
        @endif
    </div>
</section>

<section class="menus-container">
    <h2>Produits disponibles</h2>
    <div class="menus">

        @if ($produits->isEmpty())
            <p>Aucun produits disponible pour ce restaurant.</p>
        @else
            @foreach ($produits as $produit)
                <div class="menu-card">
                    <img src="{{ $produit->photo_produit }}" alt="{{ $produit->nom_produit }}">
                    <h3>{{ $produit->nom_produit }}</h3>
                    <p>Catégorie : {{ $produit->categorie_produit }} </p>
                    <p>Prix : {{ $produit->prix_produit }} €</p>

                    <form action="{{ route('cart.add', ['type' => 'produit', 'id' => $produit->id_produit]) }}"
                        method="POST">
                        @csrf
                        <button type="submit" class="btn-primary ajtpanier">Ajouter au panier</button>
                    </form>
                </div>
            @endforeach

        @endif
    </div>
</section>

<script src="{{ asset('js/main.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(() => {
                alert.classList.add('hide');
            }, 5000);
        }
    });
</script>
