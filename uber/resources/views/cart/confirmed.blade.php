@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />
<style>
    .full-screen-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        text-align: center;
        background-color: #f9f9f9;
    }
</style>

<div class="full-screen-container">
    <h1>Votre commande a bien été enregistrée !</h1>
    <br />
    <h1>Merci de votre confiance ❤️</h1>
</div>
<script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
<df-messenger style="z-index: 999;" intent="WELCOME" chat-title="HelperBot" agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745"
    language-code="fr"></df-messenger>
