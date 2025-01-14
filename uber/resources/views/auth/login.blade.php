@auth
    @php
        $role = auth()->user()->role->lib_role ?? 'guest';
    @endphp
@else
    @php
        $role = 'guest';
    @endphp
@endauth

@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container">
    <div>
        <div>
            <div class="card-header">Connexion client</div>
            <div class="card nimp">
                <div class="card-body">
                    <div id=formLogin>
                        <form method="POST" action="{{ route('login') }}">

                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger pageLoginErreur">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="mail_client">Email</label>
                                <input type="email" id="mail_client" name="mail_client" class="form-control"
                                    placeholder="Entrez votre email" value="{{ old('mail_client') }}" required
                                    autofocus>
                            </div>

                            <div class="form-group">
                                <div style="display: flex;align-items:baseline;gap:5px">
                                    <label for="mdp_client">Mot de passe</label>
                                    <div class="info-bubble" onclick="toggleInfo(this)">
                                        ?
                                        <div class="info-content">
                                            Le mot de passe doit contenir au moins 8 caractères.
                                        </div>
                                    </div>
                                </div>
                                <input placeholder="Entrez votre mot de passe" type="password" id="mdp_client"
                                    name="mdp_client" class="form-control" required>
                            </div>

                            <div class="button-container">
                                <button class="btn btn-primary">Se connecter</button>
                            </div>
                        </form>
                    </div>
                    <div class="mt-3">
                        <a class="CreerCompte" href="{{ route('register') }}">Pas encore inscrit ? Créez un compte dès
                            maintenant</a>
                    </div>
                    {{-- <!-- <div class="mt-3">
                        <a class="CreerCompte" href="{{ route('loginch') }}">Se connecter en tant que chauffeur ici</a>
                    </div> --> --}}
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger style="z-index: 999;"  chat-title="Chatbot" chat-icon="{{ asset('assets/img/chat.png') }}"
        agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
