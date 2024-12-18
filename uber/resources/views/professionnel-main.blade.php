@extends('layouts.professionnel-header')

<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<body>
    <section class="suggestions">

        <h1>Prêt à partir ? 🚓</h1>
        <div class="card">
            <img src="{{ asset('assets/img/reserve.webp') }}" loading="lazy" alt="Course" class="icon" />
            <div class="content">
                <h3>Les restaurants</h3>
                <p>
                    Visualiser vos restaurants
                </p>
                <a href="{{ url('/map') }}" class="details">Détails</a>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('assets/img/voiture.webp') }}" loading="lazy" alt="Deux-roues" class="icon" />
            <div class="content">
                <h3>Créez un restaurant</h3>
                <p>
                    Etendez la portée de vos restaurants
                </p>
                <a href="#" class="details">Détails</a>
            </div>
        </div>
    </section>

</body>
