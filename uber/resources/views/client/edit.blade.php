@extends('layouts.header')

<link href="{{ URL::asset('assets/style/compte.css') }}" rel="stylesheet">

<div class="container info-compte">
    <h1>Modifier mes informations</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('compte.update') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $client->mail_client }}" required>
        </div>

        <div class="form-group">
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" class="form-control" value="{{ $client->prenom_cp }}" required>
        </div>

        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ $client->nom_cp }}" required>
        </div>

        <div class="form-group">
            <label for="telephone">Numéro de téléphone :</label>
            <input type="text" name="telephone" id="telephone" class="form-control" value="{{ $client->tel_client }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>
