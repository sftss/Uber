<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">
</head>

<body>
    <header class="header" id="header">
        <nav class="navbar">
            <a class="navbar-brand" href="{{ url('/') }}">Uber</a>
            <div class="navbar-links">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/map') }}">Propositions de course</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.uber.com/fr/fr/drive/">Mes Courses</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/') }}" class="feature-link">Affichage Client</a>
                    </li>
                </ul>
            </div>

            <div class="navbar-connect">
                @auth
                    <!-- Si l'utilisateur est connecté -->
                    <span class="navbar-text">Bonjour, <a href="{{ url('/profil/' . auth()->user()->id_client) }}">{{ auth()->user()->prenom_cp }}</a> !</span>


                    <a href="{{ url('/info-compte') }}" 
                    class="btn btn-outline-light">Informations du compte</a>


                    <!-- Lien de déconnexion avec JavaScript -->
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="btn btn-outline-light">Déconnexion</a>

                    <!-- Formulaire de déconnexion -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf <!-- Protection CSRF -->
                    </form>
                @else
                    <!-- Si l'utilisateur n'est pas connecté -->
                    <a href="{{ route('login') }}" class="btn btn-outline-light">Connexion</a>
                    <a href="{{ route('register.form') }}" class="btn btn-light">S'inscrire</a>
                @endauth
            </div>

        </nav>
    </header>

    <footer class="footer">
        <div class="container">
            <div class="footer-section">
                <h4>Entreprise</h4>
                <ul>
                    <li><a href="#">À propos</a></li>
                    <li><a href="#">Nos services</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Produits</h4>
                <ul>
                    <li><a href="#">Déplacez-vous avec Uber</a></li>
                    <li><a href="#">Conduire</a></li>
                    <li><a href="#">Livrez</a></li>
                    <li><a href="#">Commandez un repas</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Déplacements</h4>
                <ul>
                    <li><a href="#">Réservez</a></li>
                    <li><a href="#">Villes</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
            <div class="app-links">
                <a href="#"><img
                        src="https://d1a3f4spazzrp4.cloudfront.net/uber-com/1.3.8/d1a3f4spazzrp4.cloudfront.net/illustrations/app-store-google-4d63c31a3e.svg"
                        alt="Google Play"></a>
                <a href="#"><img
                        src="https://d1a3f4spazzrp4.cloudfront.net/uber-com/1.3.8/d1a3f4spazzrp4.cloudfront.net/illustrations/app-store-apple-f1f919205b.svg"
                        alt="App Store"></a>
            </div>
            <p>© {{ date('Y') }} Uber Technologies Inc.</p>
            <div class="footer-meta">
                <a href="{{ route('politique') }}">Confidentialité</a> |
                <a href="#">Accessibilité</a> |
                <a href="#">Conditions</a>
            </div>
        </div>
    </footer>

</body>

</html>
