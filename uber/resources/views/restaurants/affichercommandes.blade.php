@extends('layouts.header')


<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">

<h1 class="title">Commandes de {{ $commandes[0]->restaurant }} </h1>


<p><strong>Total des commandes :</strong> {{ $commandes->count() }}</p>
<p><strong>Commandes en cours :</strong> 
    {{ $commandes->filter(function($commande) {
        return is_null($commande->horaire_livraison) && is_null($commande->temps_de_livraison);
    })->count() }}
</p>
<div class="commandes-container">
    @foreach ($commandes as $commande)
        @if (is_null($commande->horaire_livraison) && is_null($commande->temps_de_livraison))
            <div class="restaurant-card">
                <h3 class="restaurant-title">Commande #{{ $commande->id_commande_repas }}</h3>
                <p class="restaurant-detail">
                    Produits : 
                    @if(empty($commande->produits))
                        Aucun
                    @else
                        {{ $commande->produits }}
                    @endif
                </p>
                <p class="commande-detail">
                    Plats : 
                    @if(empty($commande->plats))
                        Aucun
                    @else
                        {{ $commande->plats }}
                    @endif
                </p>
                <p class="commande-detail">
                    Menus : 
                    @if(empty($commande->menus))
                        Aucun
                    @else
                        {{ $commande->menus }}
                    @endif
                </p>
                <p>Attribuer un chauffeur</p>

                <form method="POST" action="{{ route('restaurants.attribuerChauffeur', $commande->id_restaurant) }}">
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




            </div>
        @endif
    @endforeach
</div>
