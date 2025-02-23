@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/compte.css') }}" />

<div class="containerMdp container info-compte">
    <h2>Modifier votre mot de passe</h2>
    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="current_password">Mot de passe actuel</label>
            <input style="padding: 0.6rem;" type="password" name="current_password" id="current_password"
                class="form-control" required>
            @error('current_password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="new_password">Nouveau mot de passe</label>
            <input style="padding: 0.6rem;" type="password" name="new_password" id="new_password" class="form-control"
                required>
            @error('new_password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="new_password_confirmation">Confirmer le nouveau mot de passe</label>
            <input style="padding: 0.6rem;" type="password" name="new_password_confirmation"
                id="new_password_confirmation" class="form-control" required>
        </div>

        <button style="margin: auto;" type="submit" class="btn btn-primary">Changer le mot de passe</button>
    </form>
</div>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger style="z-index: 999;"  chat-title="Chatbot" chat-icon="{{ asset('assets/img/chat.png') }}"
        agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
