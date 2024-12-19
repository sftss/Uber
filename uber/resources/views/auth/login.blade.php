@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-header">Login</div>
            <div class="card">

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
                                    value="{{ old('mail_client') }}" required autofocus>
                            </div>

                            <div class="form-group">
                                <label for="mdp_client">Mot de passe</label>
                                <input type="password" id="mdp_client" name="mdp_client" class="form-control" required>
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
                    <div class="mt-3">
                        <a class="CreerCompte" href="{{ route('loginch') }}">Se connecter en tant que chauffeur ici</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
