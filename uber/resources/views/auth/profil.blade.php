@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/compte.css') }}" />

<div class="container info-compte">
    <h1>Informations du Compte</h1>

    <div id="img-cont">
        <img alt="Photo de profil" class="pp" src="{{ Auth::user()->photo }}">
        <form action="{{ route('client.updatePhoto') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label class="lk_img" for="pp_img" style="cursor: pointer;">Modifier votre photo de profil</label>
            <input name="pp_img" type="file" accept="image/*" id="pp_img" onchange="this.form.submit()"
                style="display: none;">
        </form>
    </div>

    <div class="account-info">

        <div class="info-item">
            <p><strong>Email :</strong>
                {{ $client->first()->mail_client }}</p>
        </div>

        <div class="info-item">
            <p><strong>Prénom et nom :</strong>
                {{ $client->first()->prenom_cp }} {{ $client->first()->nom_cp }}</p>
        </div>

        <div class="info-item" style="margin: 0 0 2% 0;">
            <p><strong>Numéro de téléphone :</strong>
                {{ $client->first()->tel_client }}</p>
        </div>

        @if ($client->isEmpty() || $client->first()->num_cb === null)
            <p>Vous n'avez actuellement aucune carte bancaire associée.</p>
        @else
            <div class="modal fade" id="cardModal" tabindex="-1" role="dialog" aria-labelledby="cardModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="cardModalLabel">Cartes Bancaires Associées</h4>
                        </div>
                        <hr />
                        <div class="modal-body">
                            @foreach ($client as $card)
                                <div class="card-info affichageCB">
                                    <p><strong>Numéro de la carte :</strong> {{ substr($card->num_cb, 0, 4) }} **** ****
                                        {{ substr($card->num_cb, -4) ?? 'Non renseigné' }}
                                    </p>
                                    <p><strong>Nom du titulaire :</strong> {{ $card->nom_cb ?? 'Non renseigné' }}
                                    </p>
                                    <p><strong>Date d’expiration :</strong>
                                        {{ \Carbon\Carbon::parse($card->date_fin_validite)->format('m/y') ?? 'Non renseigné' }}
                                    </p>
                                    <p><strong>Type de carte :</strong> {{ $card->type_cb ?? 'Non renseigné' }}</p>
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

        <a href="{{ route('card.create', ['id_client' => auth()->user()->id_client]) }}" class="ajouterInfo">Ajouter
            une carte bancaire dès maintenant 💳</a>
        <hr />

        <div class="info_chauffeur infoAdresse">
            @if ($client->isNotEmpty() && !empty($client->first()->ville))
                @foreach ($client as $adresse)
                    <div class="affichageCB">
                        <p><strong>Adresse :</strong> {{ $adresse->rue }}, {{ $adresse->cp }},
                            {{ $adresse->ville }}
                        </p>

                        <a href="#" class="btn btn-danger"
                            onclick="event.preventDefault(); 
            if (confirm('Êtes-vous sûr de vouloir supprimer cette adresse ?')) {
                document.getElementById('delete-form-{{ $adresse->id_adresse }}').submit();}">
                            ‣ Supprimer cette adresse
                        </a>

                        <form id="delete-form-{{ $adresse->id_adresse }}"
                            action="{{ route('supprimer.adresse', $adresse->id_adresse) }}" method="POST"
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="from" value="profil">
                        </form>
                    </div>
                @endforeach
            @else
                <p id="sansAdresse">Vous n'avez pas d'adresse enregistrée, veuillez en enregistrer une.</p>
            @endif
            <a href="{{ route('ajtadresse', ['from' => 'profil']) }}" class="btn btn-outline-light ajouterInfo">Ajouter
                une adresse favorite 🏠</a>
        </div>
        <hr>

        <div class="actions">
            <a href="{{ url('/client/edit') }}" class="btn btn-outline-light ajouterInfo">Modifier mes informations</a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger style="z-index: 999;"  chat-title="Chatbot" chat-icon="{{ asset('assets/img/chat.png') }}"
        agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
