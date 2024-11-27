<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Uber')</title>
    <link href="{{ asset('style/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                <a href="#order" class="btn">Commander Maintenant</a>
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
        @endsection -->
