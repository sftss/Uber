@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container">
    <div>
        <div>
            <div class="card-header">Connexion chauffeur</div>
            <div class="card nimp">
                <div class="card-body">
                    <div id=formLogin>
                        <form method="POST" action="{{ route('loginch') }}">
                            @csrf

                            <div class="form-group">
                                <label for="mail_chauffeur">Email</label>
                                <input type="email" id="mail_chauffeur" name="mail_chauffeur" class="form-control"
                                    placeholder="Entrez votre email" value="{{ old('mail_chauffeur') }}" required
                                    autofocus>
                            </div>

                            <div class="form-group">
                                <div style="display: flex;align-items:baseline;gap:5px">
                                    <label for="mdp_chauffeur">Mot de passe</label>
                                    <div class="info-bubble" onclick="toggleInfo(this)">
                                        ?
                                        <div class="info-content">
                                            Le mot de passe doit contenir au moins 8 caractères.
                                        </div>
                                    </div>
                                </div>
                                <input placeholder="Entrez votre mot de passe" type="password" id="mdp_chauffeur"
                                    name="mdp_chauffeur" class="form-control" required>
                            </div>

                            <div class="button-container">
                                <button class="btn btn-primary">Se connecter</button>
                            </div>
                        </form>
                    </div>
                    <div class="mt-3">
                        <a class="CreerCompte" href="{{ route('registerch') }}">S'inscrire en tant que chauffeur ici</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger style="z-index: 999;"  chat-title="Chatbot" chat-icon="{{ asset('assets/img/chat.png') }}"
        agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
