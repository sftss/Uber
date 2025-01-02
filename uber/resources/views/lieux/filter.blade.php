@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">

<div class="txtFilter">Faîte vos courses avec Uber 🛒</div>

<form method="GET" action="{{ route('lieux.search') }}" class="filter-form">
    <div class="form-group-restau">
        <label for="lieu" class="form-label">Rechercher un lieu de vente</label>
        <input type="text" id="lieu" name="lieu" value="{{ request('lieu') }}" class="form-input"
            placeholder="Nom de l'établissement ou ville">
    </div>

    <div class="form-group-restau form-group-lieu checkboxes">
        <label id="livraisonTxtCourse" for="livre">Livraison</label>
        <input type="checkbox" id="livre" name="livre" {{ request('livre') ? 'checked' : '' }}>
    </div>

    <div class="form-group-restau">
        <div class="planifier">
            <h2 class="planifier-header"><span>Planifier</span>
                <span class="toggle-arrow">➤</span>
            </h2>
            <div class="interface-planif" style="display: none;">
                <div class="jours">
                    <!-- génération jour -->
                </div>
                <div class="horraires">
                    <!-- génération heure -->
                </div>
            </div>
        </div>

    </div>
    <input type="hidden" id="horaire-selected" name="horaire-selected" value="">

    <button type="submit" class="btn btn-primary">Filtrer</button>
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
                    <p><strong>Horaires:</strong>
                        @if ($lieu->horaires_ouverture && $lieu->horaires_fermeture)
                            {{ date('H:i', strtotime($lieu->horaires_ouverture)) }} -
                            {{ date('H:i', strtotime($lieu->horaires_fermeture)) }}
                        @else
                            Fermé
                        @endif
                    </p>
                </div>
            </a>
        @endforeach
    @elseif(isset($lieux))
        <p class="no-results">Aucun lieu ne correspond à vos critères.</p>
    @endif
</section>
<a href="{{ url('/panier') }}" id="panier">🛒</a>
<script src="{{ asset('js/main.js') }}"></script>
