@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Commandes pour le restaurant {{ $commandes->first()->restaurant ?? 'Inconnu' }}</h1>

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

    <!-- Tableau des commandes -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Commande</th>
                <th>Produits</th>
                <th>Plats</th>
                <th>Menus</th>
                <th>Chauffeur</th>
                <th>Horaire Livraison</th>
                <th>Temps Estimé</th>
            </tr>
        </thead>
        <tbody>
            @forelse($commandes as $commande)
                <tr>
                    <td>{{ $commande->id_commande_repas }}</td>
                    <td>{{ $commande->produits }}</td>
                    <td>{{ $commande->plats }}</td>
                    <td>{{ $commande->menus }}</td>
                    <td>{{ $commande->nom_chauffeur ?? 'Non assigné' }}</td>
                    <td>{{ $commande->horaire_livraison }}</td>
                    <td>{{ $commande->horaire_livraison_estimee }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Aucune commande trouvée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
