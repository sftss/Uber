<link href="{{ URL::asset('assets/style/app.css') }}" rel="stylesheet">

@extends('layouts.header')
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">

<div>
    <div class="card-header">Connexion service</div>
    <div>
        <div>
            <div class="card">
                <div class="card-body" style="margin-top: -3%;">
                    <form method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Entrez votre email" value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Entrez votre mot de passe" required>
                        </div>

                        <div class="form-group">
                            <label for="role">Rôle</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="" disabled selected>Choisir un rôle</option>
                                <option value="logistique">Logistique</option>
                                <option value="facturation">Facturation</option>
                                <option value="administratif">Administratif</option>
                                <option value="rh">RH</option>
                                <option value="support">Support</option>
                            </select>
                        </div>

                        <div style="display: flex;justify-content: center;">
                            <button type="submit" class="btn btn-primary">Connexion</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
