@extends('layouts.header')
<link href="{{ URL::asset('assets/style/compte.css') }}" rel="stylesheet">

<div class="container containerMdp info-compte">
    <h1>Modifier mes informations</h1>

    <form action="{{ route('compte.update') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $client->mail_client }}"
                required>
        </div>

        <div class="form-group">
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" class="form-control" value="{{ $client->prenom_cp }}"
                required>
        </div>

        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ $client->nom_cp }}"
                required>
        </div>

        <div class="form-group">
            <label for="telephone">Numéro de téléphone :</label>
            <input type="text" name="telephone" id="telephone" class="form-control" value="{{ $client->tel_client }}"
                required>
        </div>

        <div class="form-group">
            <a class="btn chnagerMdp" href="{{ route('password.edit') }}"> Modifier votre mot de passe</a>
        </div>

        <button style="margin: auto;" type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger style="z-index: 999;"  chat-title="Chatbot" chat-icon="{{ asset('assets/img/chat.png') }}"
        agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
