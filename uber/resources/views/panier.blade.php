

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">


@extends('layouts.header')

<section class="paniers-list">
    @if (isset($paniers) && $paniers->isNotEmpty())
        <div class="client-info">
            <h2>Informations du Client</h2>
            <p><strong>Prénom :</strong> {{ $paniers->first()->prenom_cp }}</p>
            <p><strong>Nom :</strong> {{ $paniers->first()->nom_cp }}</p>
        </div>
        
        <h2>Détails des commandes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Commande</th>
                    <th>Montant</th>
                    <th>Nom Produit</th>
                    <th>Quantité Produit</th>
                    <th>Libellé Menu</th>
                    <th>Quantité Menu</th>
                    <th>Libellé Plat</th>
                    <th>Quantité Plat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($paniers as $panier)
                    <tr>
                        <td>{{ $panier->id_commande_repas }}</td>
                        <td>{{ $panier->montant }}</td>
                        <td>{{ $panier->nom_produit }}</td>
                        <td>{{ $panier->quantite_produit }}</td>
                        <td>{{ $panier->libelle_menu }}</td>
                        <td>{{ $panier->quantite_menu }}</td>
                        <td>{{ $panier->libelle_plat }}</td>
                        <td>{{ $panier->quantite_plat }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Aucune commande trouvée pour ce client.</p>
    @endif
</section>



<script src="{{ asset('js/main.js') }}"></script>
