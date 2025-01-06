@extends('layouts.rh-header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<body>
    <section class="suggestions">
        <h1>Allez où vous voulez avec Uber</h1>
        <p class="subtitle">Suggestions</p>

        <div class="card">
            <img src="{{ asset('assets/img/voiture.webp') }}" loading="lazy" alt="Course" class="icon" />
            <div class="content">
                <h3>Chauffeur</h3>
                <p>
                    Proposé des rendez-vous aux chauffeurs et accepter leur candidature
                </p>
                <a href="{{ url('/voirchauffeur') }}" class="details">Détails</a>
            </div>
        </div>
    </section>
</body>
