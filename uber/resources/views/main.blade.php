@extends('layouts.header')

<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<body style="background-color: white;">

    <!-- Section affichée uniquement pour les clients authentifiés, et non les chauffeurs -->
    @auth('clients')
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
                    <a href="{{ url('/map') }}" class="details">Détails</a>
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
                    <a href="{{ route('lieux.search') }}" class="details">Détails</a>
                </div>
            </div>

            <div class="card">
                <img src="{{ asset('assets/img/course.webp') }}" loading="lazy" alt="Courses" class="icon" />
                <div class="content">
                    <h3>Commandes</h3>
                    <p>Visualisez vos anciennes commandes.</p>
                    <a href="{{ route('voircommande') }}" class="details">Voir</a>
                </div>
            </div>

        </section>
    @endauth

    <!-- Section affichée uniquement pour les chauffeurs authentifiés, et non les clients -->
    @auth('chauffeurs')
        <section class="suggestions">

            <h1>Prêt à partir ? 🚓</h1>
            <div class="card">
                <img src="{{ asset('assets/img/reserve.webp') }}" loading="lazy" alt="Course" class="icon" />
                <div class="content">
                    <h3>Les propositions de courses</h3>
                    <p>
                        Visualiser les courses à prendre en charge
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
    @endauth

    <!-- Section affichée pour les utilisateurs non authentifiés (client) -->
    @guest('clients')
    @guest('chauffeurs')
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
                    <a href="{{ url('/map') }}" class="details">Détails</a>
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
                    <a href="{{ route('lieux.search') }}" class="details">Détails</a>
                </div>
            </div>

            <div class="card">
                <img src="{{ asset('assets/img/course.webp') }}" loading="lazy" alt="Courses" class="icon" />
                <div class="content">
                    <h3>Commandes</h3>
                    <p>Visualisez vos anciennes commandes.</p>
                    <a href="{{ route('voircommande') }}" class="details">Voir</a>
                </div>
            </div>

        </section>
        @endguest
        @endguest

    <!-- Section panier -->
    <a href="{{ url('/panier') }}" id="panier">🛒</a>

    <script src="{{ asset('js/main.js') }}" defer></script>

</body>
</html>
