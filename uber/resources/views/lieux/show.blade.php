@extends('layouts.header')
<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">

<div class="restaurant-card">
    <img src="{{ $lieu->photo_lieu }}" alt="Image de {{ $lieu->nom_etablissement }} " class="restaurant-image">
    <div class="lieu-vente-details">
        <h3>{{ $lieu->nom_etablissement }}</h3>
        <p><strong>Adresse : </strong> {{ $lieu->adresse->rue }} {{ $lieu->adresse->cp }} {{ $lieu->adresse->ville }}
        </p>
        <p><strong>Livraison :</strong> {{ $lieu->propose_livraison ? 'Oui' : 'Non' }}</p>
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
                                <td>{{ $horaire->lib_jour }}</td>
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
    @if (auth()->check() && $lieu->id_proprietaire == auth()->user()->id_client)
        <p>Vous êtes le propriétaire</p>
        <div class="createProprioBut">
            <a class="btn btn-primary"
                href="{{ route('lieux.produit.create', ['lieu_id' => $lieu->id_lieu_de_vente_pf]) }}">Ajouter un
                produit</a>
        </div>
        <a href="{{ route('lieux.affichercommandes', ['id' => $lieu->id_lieu_de_vente_pf]) }}"
                class="btn btn-primary">Afficher les commandes en cours</a>
    @endif
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
                <option value="{{ $categorie->id_categorie_produit }}">
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
                    @if($produit->note_produit)
                        <p>Note : {{ $produit->note_produit }}</p>
                    @else
                        <p>Note : Aucune note disponible</p>
                    @endif

                    @if($produit->nb_avis)
                        <p>{{ $produit->nb_avis }} avis</p>
                    @else
                        <p>Aucun avis disponible</p>
                    @endif
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
        const alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(() => {
                alert.classList.add('hide');
            }, 5000);
        }
    });
</script>
