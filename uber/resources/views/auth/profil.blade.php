@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/compte.css') }}" />

<div class="container info-compte">
    <h1>Informations du Compte</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @else
        <div class="account-info">
            <!-- Informations générales du client -->

            <div class="info-item">
                <strong>Email :</strong>
                <p>{{ $client->first()->mail_client }}</p>
            </div>

            <div class="info-item">
                <strong>Nom :</strong>
                <p>{{ $client->first()->prenom_cp }} {{ $client->first()->nom_cp }}</p>
            </div>

            <div class="info-item">
                <strong>Numéro de téléphone :</strong>
                <p>0{{ $client->first()->tel_client }}</p>
            </div>

            <h3>Informations de la carte bancaire</h3>

            @if($client->isEmpty() || $client->first()->num_cb === null)
                <p>Vous n'avez actuellement aucune carte bancaire associée.</p>


            @else
                @foreach ($client as $card)
                    <div class="info-item">
                        <strong>Numéro de la carte :</strong>
                        <p>{{ $card->num_cb ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="info-item">
                        <strong>Nom du titulaire :</strong>
                        <p>{{ $card->nom_cb ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="info-item">
                        <strong>Date d’expiration :</strong>
                        <p>{{ $card->date_fin_validite ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="info-item">
                        <strong>Type de carte :</strong>
                        <p>{{ $card->type_cb ?? 'Non renseigné' }}</p>
                    </div>

                    <hr />
                @endforeach
            @endif
            <a href="{{ route('card.create', ['id_client' => auth()->user()->id_client]) }}" class="btn btn-primary">Ajouter une carte bancaire</a>


            <!-- Bouton pour modifier les informations -->
            <div class="actions">
                <a href="{{ url('/info-compte/edit') }}" class="btn btn-outline-light">Modifier mes informations</a>
            </div>
        </div>
    @endif
</div>
