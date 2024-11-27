<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uber</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header" id="header">
        <div class="header_content">
            <a href="#" class="header_logo">Uber</a>
            <nav>
                <ul>
                    <li><a href="#ride">Ride</a></li>
                    <li><a href="#drive">Drive</a></li>
                    <li><a href="#eat">Eat</a></li>
                    <li><a href="#help">Help</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero" id="home">
            <div class="hero_content">
                <h1>Ride with Uber</h1>
                <p>Votre transport moderne et fiable à portée de clic</p>
                <a href="#" class="btn">Commander Maintenant</a>
            </div>
        </section>

        {{var restaurants}}
        @section('content')
            <h2>Les restaurants</h2>
                <ul>
                    @foreach ($restaurants as $restaurant)
                        <li>{{ $restaurant->nom_etablissement }}</li>
                    @endforeach
                </ul>
        @endsection


        <section class="section" id="why-uber">
            <h2 class="section-title">Pourquoi Uber ?</h2>
            <div class="why-uber_content">
                <div class="feature">
                    <i class="fas fa-clock"></i>
                    <h3>Rapide et Pratique</h3>
                    <p>Commandez un trajet en quelques secondes</p>
                </div>
                <div class="feature">
                    <i class="fas fa-car"></i>
                    <h3>Transport Fiable</h3>
                    <p>Des chauffeurs professionnels et vérifiés</p>
                </div>
                <div class="feature">
                    <i class="fas fa-wallet"></i>
                    <h3>Prix Compétitifs</h3>
                    <p>Tarifs transparents sans frais cachés</p>
                </div>
            </div>
        </section> 
    </main>

    <footer class="footer">
        <div class="footer-content">
            <p>&copy; {{ date('Y') }} Uber. Tous droits réservés.</p>
              <div class="footer-social">
                <p>on met ici le plan du site</p>            
            </div> 
        </div>
    </footer>
</body>
</html>