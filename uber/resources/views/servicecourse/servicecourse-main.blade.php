@extends('layouts.service-course-header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<body style="background-color: white;">
    <section class="suggestions">
        <h1>Allez où vous voulez avec Uber</h1>
        <p class="subtitle">Suggestions</p>

        <div class="card">
            <img src="{{ asset('assets/img/voiture.webp') }}" loading="lazy" alt="Course" class="icon" />
            <div class="content">
                <h3>Course</h3>
                <p>
                    Envoyé les demandes de course aux chauffeurs
                </p>
                <a href="{{ url('/voircourse') }}" class="details">Détails</a>
            </div>
        </div>
    </section>
</body>
