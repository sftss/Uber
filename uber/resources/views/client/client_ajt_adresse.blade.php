@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/compte.css') }}" />

<form action="{{ route('ajouter.adresse') }}" method="POST" class="adresse-form">
    @csrf
    <h1 style="text-align:center;margin:0 0 2% 0;">Modifier mes informations</h1>

    <div class="form-group">
        <label for="rue" class="form-label">Rue :</label>
        <input type="text" id="rue" name="rue" required maxlength="255" class="form-input">
        @error('rue')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="cp" class="form-label">Code postal :</label>
        <input type="text" id="cp" name="cp" required pattern="\d{5}"
            title="Entrez un code postal valide à 5 chiffres" class="form-input">
        @error('cp')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="ville" class="form-label">Ville :</label>
        <input type="text" id="ville" name="ville" required maxlength="100" class="form-input">
        @error('ville')
            <span class="error-message">{{ $message }}</span>
        @enderror

    </div>
    <div id="suggestionsDepart"></div>
    <!-- Champ caché pour déterminer d'où provient l'utilisateur -->
    <input type="hidden" name="from" value="{{ request()->query('from') }}" class="form-input">

    <button type="submit" class="form-submit-btn">Soumettre</button>
    <script src="{{ URL::asset('js/autocompletion.js') }}" defer></script>
</form>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger style="z-index: 999;"  chat-title="Chatbot" chat-icon="{{ asset('assets/img/chat.png') }}"
        agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
