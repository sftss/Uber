<!-- @extends('layouts.header')

<link rel="stylesheet" href="{{ URL::asset('assets/style/compte.css') }}" />


<div class="container info-compte">
    <h1>Informations du Compte</h1>

    <div class="account-info">
        <h2>Bonjour, {{ auth()->user()->prenom_cp }} !</h2>

        <div class="info-item">
            <strong>Email :</strong>
            <p>{{ auth()->user()->mail_client }}</p>
        </div>

        <div class="info-item">
            <strong>Nom :</strong>
            <p>{{ auth()->user()->prenom_cp }} {{ auth()->user()->nom_cp }}</p>
        </div>

        <div class="info-item">
            <strong>Numéro de téléphone :</strong>
            <p>{{ auth()->user()->tel_client }}</p>
        </div>

        <h3>Informations de la carte bancaire</h3>
        <div class="info-item">
            <strong>Numéro de la carte :</strong>
            <p>{{ auth()->user()->card_number ?? 'Non renseigné' }}</p>
        </div>
        <div class="info-item">
            <strong>Nom du titulaire :</strong>
            <p>{{ auth()->user()->cardholder_name ?? 'Non renseigné' }}</p>
        </div>
        <div class="info-item">
            <strong>Date d’expiration :</strong>
            <p>{{ auth()->user()->expiration_date ?? 'Non renseigné' }}</p>
        </div>

        <div class="actions">
            <a href="{{ url('/info-compte/edit') }}" class="btn btn-outline-light">Modifier mes informations</a>
        </div>
    </div>
</div>
 -->
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger style="z-index: 999;"  chat-title="Chatbot" chat-icon="{{ asset('assets/img/chat.png') }}"
        agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
