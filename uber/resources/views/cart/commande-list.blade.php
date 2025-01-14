@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

@if ($commandes->isEmpty())
    <div style="margin: 14% 14%;" class="no-courses-message">
        <p>Vous n'avez aucune aucune commande pour le moment.</p>
        <p id="pouvezIci"><a class="nav-link" href="{{ url('/restaurants/search') }}">Vous pouvez commander ici</a></p>
    </div>
@else
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
                @foreach ($commandes as $idCommande => $commande)
                    @php
                        // Calcul du total de la commande (plat, produit, menu)
                        $totalCommande = 0;
                    @endphp
                    @foreach ($commande as $result)
                        @php
                            $prixPlat = ($result->quantite_plat ?? 0) * ($result->prix_plat ?? 0);
                            $prixProduit = ($result->quantite_produit ?? 0) * ($result->prix_produit ?? 0);
                            $prixMenu = ($result->quantite_menu ?? 0) * ($result->prix_menu ?? 0);
                            $totalCommande += $prixPlat + $prixProduit + $prixMenu;
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
                    @php
                        $grandTotal += $totalCommande;
                    @endphp
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
@endif
<script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
<df-messenger style="z-index: 999;" intent="WELCOME" chat-title="Uber Bot"
    agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
