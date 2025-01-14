@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">

<h1 class="title">Commandes de {{ $etablissement->nom_etablissement ?? 'Non spécifié' }}</h1>

<p><strong>Total des commandes :</strong> {{ $commandes->count() }}</p>
<p><strong>Commandes en cours :</strong>
    {{ $commandes->filter(function ($commande) {
            return is_null($commande->temps_de_livraison);
        })->count() }}
</p>

<!-- Formulaire de filtrage -->
<form method="GET" action="{{ route('lieux.affichercommandes', $id) }}" class="mb-3">
    <div class="d-flex align-items-center">
        <button type="submit" name="filter" value="urgent" class="btn btn-primary">
            Commandes à livrer dans 1h ou moins
        </button>
        <a href="{{ route('lieux.affichercommandes', $id) }}" class="btn btn-secondary ms-2">
            Réinitialiser
        </a>
    </div>
</form>

<div class="commandes-container">
    @foreach ($commandes as $commande)
        @if (is_null($commande->temps_de_livraison))
            <div class="restaurant-card">
                <h3 class="restaurant-title">Commande #{{ $commande->id_commande_repas }}</h3>
                <p class="restaurant-detail">
                    Produits :
                    @if (empty($commande->produits))
                        Aucun
                    @else
                        {{ $commande->produits }}
                    @endif
                </p>

                @if ($etablissement->propose_livraison == 'True')
                    <p>Attribuer un chauffeur</p>

                    <form method="POST"
                        action="{{ route('lieux.attribuerChauffeur', $commande->id_lieu_de_vente_pf) }}">
                        @csrf
                        <input type="hidden" name="id_commande_repas" value="{{ $commande->id_commande_repas }}">

                        @if (is_null($commande->id_chauffeur))
                            <select name="id_chauffeur" id="id_chauffeur" required>
                                <option value="null">Aucun chauffeur</option>
                                @foreach ($livreurs as $livreur)
                                    @if ($livreur->est_dispo == 'true')
                                        <option value="{{ $livreur->id_chauffeur }}"
                                            @if ($commande->id_chauffeur == $livreur->id_chauffeur) selected @endif>
                                            {{ $livreur->prenom_chauffeur }} {{ $livreur->nom_chauffeur }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        @else
                            <p>Chauffeur actuel : {{ $commande->nom_chauffeur }}</p>
                            <p>Modifier le chauffeur</p>
                            <select name="id_chauffeur" id="id_chauffeur" required>
                                <option value="null">Aucun chauffeur</option>
                                @foreach ($livreurs as $livreur)
                                    @if ($livreur->est_dispo == 'true')
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
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger style="z-index: 999;"  chat-title="Chatbot" chat-icon="{{ asset('assets/img/chat.png') }}"
        agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
