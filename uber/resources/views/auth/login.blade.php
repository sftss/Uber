@extends('layouts.header')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-header">Login</div>
            <div class="card">

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Email -->
                        <div class="form-group">
                            <label for="mail_client">Email</label>
                            <input type="email" id="mail_client" name="mail_client" class="form-control"
                                value="{{ old('mail_client') }}" required autofocus>
                        </div>

                        <!-- Mot de passe -->
                        <div class="form-group">
                            <label for="mdp_client">Mot de passe</label>
                            <input type="password" id="mdp_client" name="mdp_client" class="form-control" required>
                        </div>

                        <!-- Bouton de connexion -->
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </form>

                    <div class="mt-3">
                        <!-- Lien vers la page d'inscription -->
                        <a href="{{ route('register') }}">Pas encore inscrit ? Créez un compte dès maintenant</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
