<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Uber')</title>
    <link href="{{ asset('style/app.css') }}" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Uber</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
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
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <button class="btn btn-outline-light" type="button">Connexion</button>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-light" type="button">S'inscrire</button>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="container text-center">
                <h1 class="display-4 text-white">Ride with Uber</h1>
                <p class="lead text-white mb-4">Votre transport moderne et fiable à portée de clic</p>
                <a href="#order" class="btn btn-warning btn-lg">Commander Maintenant</a>
            </div>
        </section>

        <div class="container mt-5">
            <a href="{{ url ("/restaurants/search")}}"><p>Rechercher un restaurant</p></a>
            <a href="{{ url ("/restaurants/catPrestationSearch")}}"><p>Rechercher une prestation</p></a>
            <a href="{{ url ("/restaurants")}}"><p>Liste des restaurants</p></a>
            <a href="{{ url ("/clients")}}"><p>Rechercher un client</p></a>
        </div>
    </main>


    <footer class="footer bg-dark text-white text-center py-4">
        <p>© {{ date('Y') }} Uber. Tous droits réservés.</p>
        <p>Plan du site</p>
    </footer>

</body>
</html>
