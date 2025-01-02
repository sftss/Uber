@extends('layouts.header')


<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">

<h1 class="title">Vos Commandes</h1>

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
            </div>
        @endif
    @endforeach
</div>
