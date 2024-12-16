@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/compte.css') }}" />

<div class="container info-compte">
    <h1>Informations du Compte</h1>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @else
        <div class="account-info">
            <!-- Informations générales du client -->
            <div class="info-item">
                <strong>Email :</strong>
                <p>{{ $client->first()->mail_client }}</p>
            </div>

            <div class="info-item">
                <strong>Nom :</strong>
                <p>{{ $client->first()->prenom_cp }} {{ $client->first()->nom_cp }}</p>
            </div>

            <div class="info-item">
                <strong>Numéro de téléphone :</strong>
                <p>0{{ $client->first()->tel_client }}</p>
            </div>

            @if ($client->isEmpty() || $client->first()->num_cb === null)
                <p>Vous n'avez actuellement aucune carte bancaire associée.</p>
            @else
                <!-- Modal -->
                <div class="modal fade" id="cardModal" tabindex="-1" role="dialog" aria-labelledby="cardModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="cardModalLabel">Cartes Bancaires Associées</h4>
                            </div>
                            <div class="modal-body">
                                @foreach ($client as $card)
                                    <div class="card-info affichageCB">
                                        <p><strong>Numéro de la carte :</strong> {{ $card->num_cb ?? 'Non renseigné' }}
                                        </p>
                                        <p><strong>Nom du titulaire :</strong> {{ $card->nom_cb ?? 'Non renseigné' }}
                                        </p>
                                        <p><strong>Date d’expiration :</strong>
                                            {{ \Carbon\Carbon::parse($card->date_fin_validite)->format('m/y') ?? 'Non renseigné' }}
                                        </p>
                                        <p><strong>Type de carte :</strong> {{ $card->type_cb ?? 'Non renseigné' }}</p>
                                        <!-- Lien pour supprimer la carte -->
                                        <a href="{{ route('card.delete', ['id_client' => Auth::user()->id_client, 'id_cb' => $card->id_cb]) }}"
                                            class="btn btn-danger"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette carte ?')">‣
                                            Supprimer cette carte</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <a href="{{ route('card.create', ['id_client' => auth()->user()->id_client]) }}"
                class="ajouterInfo">Ajouter une carte bancaire dès maintenant 💳</a>
            <hr />

            <div class="info_chauffeur infoAdresse">
                @if ($client->isNotEmpty() && !empty($client->first()->ville))
                    @foreach ($client as $adresse)
                        <div class="affichageCB">
                            <p><strong>Adresse :</strong> {{ $adresse->rue }}, {{ $adresse->cp }},
                                {{ $adresse->ville }}
                            </p>

                            <!-- Lien pour supprimer l'adresse -->
                            <a href="#" class="btn btn-danger"
                                onclick="event.preventDefault(); 
                if (confirm('Êtes-vous sûr de vouloir supprimer cette adresse ?')) {
                    document.getElementById('delete-form-{{ $adresse->id_adresse }}').submit();}">
                                ‣ Supprimer cette adresse
                            </a>

                            <!-- Formulaire caché pour supprimer l'adresse -->
                            <form id="delete-form-{{ $adresse->id_adresse }}"
                                action="{{ route('supprimer.adresse', $adresse->id_adresse) }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="from" value="profil">
                                <!-- Optionnel, si vous voulez garder la logique 'from' -->
                            </form>
                        </div>
                    @endforeach
                @else
                    <p id="sansAdresse">Vous n'avez pas d'adresse enregistrée, veuillez en enregistrer une.</p>
                @endif
                <a href="{{ route('ajtadresse', ['from' => 'profil']) }}"
                    class="btn btn-outline-light ajouterInfo">Ajouter une adresse 🏠</a>
            </div>

            <!-- Bouton pour modifier les informations -->
            <hr>

            <div class="actions">
                <a href="{{ url('/info-compte/edit') }}" class="btn btn-outline-light ajouterInfo">Modifier mes
                    informations</a>
            </div>
        </div>
    @endif
</div>

<!-- Inclure les scripts nécessaires pour Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
