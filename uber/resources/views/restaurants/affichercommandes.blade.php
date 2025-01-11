@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">

<h1 class="title">Commandes de {{ $restaurant->nom_etablissement ?? 'Non spécifié' }}</h1>

<p><strong>Total des commandes :</strong> {{ $commandes->count() }}</p>
<p><strong>Commandes en cours :</strong> 
    {{ $commandes->filter(function($commande) {
        return is_null($commande->temps_de_livraison);
    })->count() }}
</p>

<!-- Formulaire de filtrage -->
<form method="GET" action="{{ route('restaurants.affichercommandes', $id) }}" class="mb-3">
    <div class="d-flex align-items-center">
        <button type="submit" name="filter" value="urgent" class="btn btn-primary">
            Commandes à livrer dans 1h ou moins
        </button>
        <a href="{{ route('restaurants.affichercommandes', $id) }}" class="btn btn-secondary ms-2">
            Réinitialiser
        </a>
    </div>
</form>

<!-- Affichage des commandes -->
<div class="commandes-container">
    @foreach ($commandes as $commande)
        @if (is_null($commande->temps_de_livraison))
            <div class="restaurant-card">
                <h3 class="restaurant-title">Commande #{{ $commande->id_commande_repas }}</h3>
                
                @if(!empty($commande->menus))
                    <p class="restaurant-detail">
                        Produits : {{ $commande->menus }}
                    </p>
                @endif
                @if(!empty($commande->plats))
                    <p class="restaurant-detail">
                        Produits : {{ $commande->plats }}
                    </p>
                @endif
                @if(!empty($commande->produits))
                    <p class="restaurant-detail">
                        Produits : {{ $commande->produits }}
                    </p>
                @endif
                
                



                @if($restaurant->propose_livraison=='True')
                    <p>Attribuer un chauffeur</p>

                    <form method="POST" action="{{ route('restaurants.attribuerChauffeur', $id) }}">
                        @csrf
                        <input type="hidden" name="id_commande_repas" value="{{ $commande->id_commande_repas }}">

                        @if (is_null($commande->id_chauffeur))
                            <select name="id_chauffeur" id="id_chauffeur" required>
                                <option value="null">Aucun chauffeur</option>
                                @foreach ($livreurs as $livreur)
                                   @if($livreur->est_dispo == 'true') 
                                    <option value="{{ $livreur->id_chauffeur }}"
                                        @if ($commande->id_chauffeur == $livreur->id_chauffeur) selected @endif>
                                        {{ $livreur->prenom_chauffeur }} {{ $livreur->nom_chauffeur }} 
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                        @else
                            <p>Chauffeur actuel : {{$commande->nom_chauffeur}}</p>
                            <p>Modifier le chauffeur</p>
                            <select name="id_chauffeur" id="id_chauffeur" required>
                                <option value="null">Aucun chauffeur</option>
                                @foreach ($livreurs as $livreur)
                                   @if($livreur->est_dispo == 'true') 
                                    <option value="{{ $livreur->id_chauffeur }}"
                                        @if ($commande->id_chauffeur == $livreur->id_chauffeur) selected @endif>
                                        {{ $livreur->prenom_chauffeur }} {{ $livreur->nom_chauffeur }} 
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                        @endif

                        <button type="submit">Attribuer</button>
                    </form>
                @endif
            </div>
        @endif
    @endforeach
</div>
