@extends('layouts.chauffeur-header')

<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />
<body>
<section class="suggestions">

<div class="card">
        <img src="{{ asset('assets/img/reserve.webp') }}" loading="lazy" alt="Course" class="icon" />
        <div class="content">
            <h3>Les propositions de courses</h3>
            <p>
                Visualiser les courses a prendre en charge
            </p>
            <a href="{{ url('/map') }}" class="details">Détails</a>
        </div>
    </div>

    <div class="card">
        <img src="{{ asset('assets/img/voiture.webp') }}" loading="lazy" alt="Deux-roues" class="icon" />
        <div class="content">
            <h3>Vos courses déjà effectuées</h3>
            <p>
                Consultez l'historique des courses que vous avez passées
            </p>
            <a href="#" class="details">Détails</a>
        </div>
    </div>
</section>
        
</body>