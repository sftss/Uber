@extends('layouts.header')

<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container containerVerif">
    <h1>Vérification de votre compte</h1>
    <form style="display: flex;flex-direction: column;margin: 1% 0 4% 0;" action="{{ route('verification.submit') }}"
        method="POST">
        @csrf
        <div class="form-group form-groupVerif">
            <label for="code">Code de vérification</label>
            <input type="text" name="code" id="code" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary butVerifier">Vérifier</button>
    </form>
</div>
<script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
<df-messenger style="z-index: 999;" intent="WELCOME" chat-title="Uber Bot"
    agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
