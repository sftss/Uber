<html lang="{{ app()->getLocale() }}">

<head>
    <!-- Script Cookie-Script (gestion des cookies) -->
    <script type="text/javascript" charset="UTF-8" src="//cdn.cookie-script.com/s/740c938fe35e9b4ecefe1e3459a48f4b.js">
    </script>

    <!-- Google Analytics (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-KK9GZC2LN3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-KK9GZC2LN3');
        gtag('consent', 'default', {
            'ad_storage': 'denied',
            'ad_user_data': 'denied',
            'ad_personalization': 'denied',
            'analytics_storage': 'denied',
            'regions': ['FR', 'DE', 'IT', 'ES', 'PT', 'NL', 'BE', 'LU', 'CH', 'AT', 'SE', 'NO', 'DK', 'FI', 'PL',
                'CZ', 'SK', 'HU', 'RO', 'BG', 'GR', 'IE', 'GB', 'EE', 'LV', 'LT', 'MT', 'CY', 'HR', 'SI', 'IS',
                'LI'
            ]
        });
    </script>

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PT3GRQ9T');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Uber</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="icon" href="{{ URL::asset('assets/img/Uber-logo.webp') }}" type="image/svg+xml">
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PT3GRQ9T" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <header class="header" id="header">
        <nav class="navbar">
            <a class="navbar-brand" href="{{ url('/') }}">Uber</a>
            <div class="navbar-links">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/restaurants/search/mesrestaurants') }}">Mes restaurants</a>
                    </li>
                    <li class="nav-item">
                        <!-- {{-- <a class="nav-link" href="{{ url('/professionnel-creation/restaurant/12') }}">Créer un restaurant</a> --}}     -->
                        <a class="nav-link" href="{{ url('/creer-restaurant') }}">Créer un restaurant</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('lieux.create') }}">Créer un lieu de vente</a>
                    </li>
                    <!-- {{-- <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link">Affichage Client</a>
                    </li> --}}     -->
                </ul>
            </div>

            <div class="navbar-connect">
                @auth
                    <span class="navbar-text">Bonjour, <a
                            href="{{ url('/profil/' . auth()->user()->id_client) }}">{{ auth()->user()->prenom_cp }}</a>
                        !</span>

                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="btn btn-outline-light">Déconnexion</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
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
                    <li><a href="https://www.uber.com/fr/fr/about/?uclick_id=dad98e94-ee51-496b-9dc6-4f7a52496398">À
                            propos</a></li>
                    <li><a href="https://www.uber.com/fr/fr/about/uber-offerings/">Nos services</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Produits</h4>
                <ul>
                    <li><a href="{{ route('map') }}">Déplacez-vous avec Uber</a></li>
                    <li><a href="{{ url('eats/choix-type') }}">Commandez un repas</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Déplacements</h4>
                <ul>
                    <li><a href="{{ route('map') }}">Réservez</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="app-links">
                <a href="https://play.google.com/store/apps/details?id=com.ubercab&hl=fr"><img
                        src="https://d1a3f4spazzrp4.cloudfront.net/uber-com/1.3.8/d1a3f4spazzrp4.cloudfront.net/illustrations/app-store-google-4d63c31a3e.svg"
                        alt="Google Play"></a>
                <a href="https://apps.apple.com/fr/app/uber-commandez-un-trajet/id368677368"><img
                        src="https://d1a3f4spazzrp4.cloudfront.net/uber-com/1.3.8/d1a3f4spazzrp4.cloudfront.net/illustrations/app-store-apple-f1f919205b.svg"
                        alt="App Store"></a>
            </div>
            <p>© {{ date('Y') }} Uber Technologies Inc.</p>
            <div class="footer-meta">
                <a href="{{ route('politique') }}">| Confidentialité</a> |
                <a href="{{ route('politique') }}">Accessibilité</a> |
            </div>
        </div>
    </footer>
    <script src="{{ URL::asset('js/bulleinformation.js') }}" defer></script>

</body>

</html>
