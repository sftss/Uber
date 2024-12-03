@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet" />

<section class="suggestions">
    <h1>Allez où vous voulez avec Uber</h1>
    <p class="subtitle">Suggestions</p>

    <div class="card">
        <img src="{{ asset('assets/img/voiture.webp') }}" loading="lazy" alt="Course" class="icon" />
        <div class="content">
            <h3>Course</h3>
            <p>
                Allez où vous voulez avec Uber. Commandez une course en un clic et
                c'est parti !
            </p>
            <a href="#" class="details">Détails</a>
        </div>
    </div>

    <div class="card">
        <img src="{{ asset('assets/img/velo.webp') }}" loading="lazy" alt="Deux-roues" class="icon" />
        <div class="content">
            <h3>Deux-roues</h3>
            <p>
                Vous pouvez désormais trouver et louer un vélo électrique via
                l'application Uber.
            </p>
            <a href="#" class="details">Détails</a>
        </div>
    </div>

    <div class="card">
        <img src="{{ asset('assets/img/reserve.webp') }}" loading="lazy" alt="Réserver" class="icon" />
        <div class="content">
            <h3>Réserver</h3>
            <p>
                Réservez votre course à l'avance pour pouvoir vous détendre le jour
                même.
            </p>
            <a href="#" class="details">Détails</a>
        </div>
    </div>

    <div class="card">
        <img src="{{ asset('assets/img/course.webp') }}" loading="lazy" alt="Courses" class="icon" />
        <div class="content">
            <h3>Courses</h3>
            <p>Faites livrer vos courses à votre porte avec Uber Eats.</p>
            <a href="#" class="details">Détails</a>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <h2>Explorez nos options</h2>
        <div class="links-grid">
            <a href="{{ url('/restaurants/search') }}" class="feature-link">Rechercher un
                restaurant</a>
        </div>
    </div>
</section>
