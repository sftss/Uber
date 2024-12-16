@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container mt-5">
    <h1 id="AncienneCommandeTxt" class="mb-4">Vos anciennes commandes</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Numéro de Commande</th>
                <th>Libellé Plat</th>
                <th>Quantité Plat</th>
                <th>Nom Produit</th>
                <th>Quantité Produit</th>
                <th>Libellé Menu</th>
                <th>Quantité Menu</th>
                <th>Prix Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotal = 0;
            @endphp
            @foreach ($client as $result)
                @php
                    $prixPlat = ($result->quantite_plat ?? 0) * ($result->prix_plat ?? 0);
                    $prixProduit = ($result->quantite_produit ?? 0) * ($result->prix_produit ?? 0);
                    $prixMenu = ($result->quantite_menu ?? 0) * ($result->prix_menu ?? 0);
                    $totalCommande = $prixPlat + $prixProduit + $prixMenu;
                    $grandTotal += $totalCommande;
                @endphp
                <tr>
                    <td>{{ $result->id_commande_repas ?? '-' }}</td>
                    <td>{{ $result->libelle_plat ?? '-' }}</td>
                    <td>{{ $result->quantite_plat ?? '-' }}</td>
                    <td>{{ $result->nom_produit ?? '-' }}</td>
                    <td>{{ $result->quantite_produit ?? '-' }}</td>
                    <td>{{ $result->libelle_menu ?? '-' }}</td>
                    <td>{{ $result->quantite_menu ?? '-' }}</td>
                    <td>{{ number_format($totalCommande, 2) }} €</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7">Grand Total</th>
                <th>{{ number_format($grandTotal, 2) }} €</th>
            </tr>
        </tfoot>
    </table>
</div>
