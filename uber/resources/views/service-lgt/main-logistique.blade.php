@extends('layouts.servicelogistique-header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<body>
    <section class="suggestions">
        <h1>Allez où vous voulez avec Uber</h1>
        <p class="subtitle">Suggestions</p>

        <div class="card">
            <img src="{{ asset('assets/img/voiture.webp') }}" loading="lazy" alt="Course" class="icon" />
            <div class="content">
                <h3>Véhicule</h3>
                <p>
                    Réalisé des demandes d'amenagement des vehicules
                </p>
                <a href="{{ url('/voirvehicule') }}" class="details">Détails</a>
            </div>
        </div>
    </section>
</body>
