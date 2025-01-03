@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container">
    <div>
        <div>
            <div class="card-header">Connexion chauffeur</div>
            <div class="card" style="transform: translateX(-2%);">

                <div class="card-body">
                    <div id=formLogin>
                        <form method="POST" action="{{ route('loginch') }}">
                            @csrf

                            <div class="form-group">
                                <label for="mail_chauffeur">Email</label>
                                <input type="email" id="mail_chauffeur" name="mail_chauffeur" class="form-control"
                                    value="{{ old('mail_chauffeur') }}" required autofocus>
                            </div>

                            <div class="form-group">
                                <label for="mdp_chauffeur">Mot de passe</label>
                                <input type="password" id="mdp_chauffeur" name="mdp_chauffeur" class="form-control"
                                    required>
                            </div>

                            <div class="button-container">
                                <button class="btn btn-primary">Se connecter</button>
                            </div>
                        </form>
                    </div>
                    <div class="mt-3">
                        <a class="CreerCompte" style="transform: translateX(10%);"
                            href="{{ route('registerch') }}">S'inscrire en tant que chauffeur ici</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
