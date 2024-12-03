<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />
</head>

<body>
    <header class="header" id="header">
        <nav class="navbar">
            <a class="navbar-brand">Uber</a>
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

</body>

</html>
