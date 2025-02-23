@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/compte.css') }}" />

<div class="container info-compte">
    <h1>Ajouter une carte bancaire</h1>

    <form method="POST" action="{{ route('card.store', ['id_client' => auth()->user()->id_client]) }}">
        @csrf
        <input type="hidden" name="from" value="{{ request('from', 'cart') }}">
        <div class="form-group">
            <label for="num_cb">Numéro de la carte</label>
            <input type="text" id="num_cb" name="num_cb" class="form-control" value="{{ old('num_cb') }}"
                required>
            @error('num_cb')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="nom_cb">Nom du titulaire</label>
            <input type="text" id="nom_cb" name="nom_cb" class="form-control" value="{{ old('nom_cb') }}"
                required>
            @error('nom_cb')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="date_fin_validite">Date d’expiration</label>
            <input type="month" id="date_fin_validite" name="date_fin_validite" class="form-control"
                value="{{ old('date_fin_validite') }}" required>
            @error('date_fin_validite')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div id="butAddCard">
            <a href="{{ route('profil', ['id_client' => auth()->user()->id_client]) }}"
                class="btn btn-primary annuler">Annuler</a>
            <button type="submit" class="btn btn-primary">Ajouter la carte</button>
        </div>
    </form>
</div>

<script>
    document.querySelector('form').addEventListener('submit', function() {
        this.querySelector('button[type="submit"]').disabled = true;
    });
</script>
<script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
<df-messenger style="z-index: 999;" chat-title="Chatbot" chat-icon="{{ asset('assets/img/chat.png') }}"
    agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
