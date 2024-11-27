<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Uber')</title>
    <link href="{{ asset('style/app.css') }}" rel="stylesheet" />
</head>

<body>
    <header>
        <nav class="navbar">
            <a class="navbar-brand" href="#">Uber</a>
            <div class="navbar-links">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.uber.com/fr/fr/">Déplacez-vous avec Uber</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.uber.com/fr/fr/drive/">Conduire</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.uber.com/fr/fr/business/">Professionnel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.ubereats.com/">Uber Eats</a>
                    </li>
                </ul>
            </div>
            <div class="navbar-connect">
                <button class="btn btn-outline-light">Connexion</button>
                <button class="btn btn-light">S'inscrire</button>
            </div>
        </nav>
    </header>

    <section class="suggestions">
        <h1>Allez où vous voulez avec Uber</h1>
        <p class="subtitle">Suggestions</p>

        <div class="card">
            <img src="{{ asset('img/voiture.png') }}" alt="Course" class="icon" />
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
            <img src="{{ asset('img/velo.png') }}" alt="Deux-roues" class="icon" />
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
            <img src="{{ asset('img/reserve.png') }}" alt="Réserver" class="icon" />
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
            <img src="{{ asset('img/course.png') }}" alt="Courses" class="icon" />
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
                <a href="{{ url('/restaurants/search') }}" class="feature-link">Rechercher un restaurant</a>
                <a href="{{ url('/restaurants/catPrestationSearch') }}" class="feature-link">Rechercher une
                    prestation</a>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>© {{ date('Y') }} Uber. Tous droits réservés.</p>
        <a href="#">Plan du site</a>
    </footer>
</body>

</html>
