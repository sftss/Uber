@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">


<section class="restaurants-list">
    @if (isset($lieux) && $lieux->isNotEmpty())
        @foreach ($lieux as $lieu)
            <a href="{{ route('lieux.show', $lieu->id_lieu_de_vente_pf) }}" class="restaurant-card" >
                <img src="{{ $lieu->photo_lieu }}" alt="Image de {{ $lieu->nom_etablissement }}" class="restaurant-image">
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
