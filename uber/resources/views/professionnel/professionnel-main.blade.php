@extends('layouts.professionnel-header')
<link rel="icon" href="{{ URL::asset('assets/img/uber-logo.svg') }}" type="image/svg+xml">
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<body>
    <section class="suggestions">

        <h1>Prêt à partir ? 🚓</h1>
        <div class="card">
            <img src="{{ asset('assets/img/reserve.webp') }}" loading="lazy" alt="Course" class="icon" />
            <div class="content">
                <h3>Mes restaurants</h3>
                <p>
                    Visualiser mes restaurants
                </p>
                <a href="{{ url('/restaurants/search/mesrestaurants') }}" class="details">Détails</a>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('assets/img/voiture.webp') }}" loading="lazy" alt="Deux-roues" class="icon" />
            <div class="content">
                <h3>Créez un restaurant</h3>
                <p>
                    Etendez la portée de vos restaurants
                </p>
                <a href="{{ url('/creer-restaurant') }}" class="details">Détails</a>
            </div>
        </div>

        <div class="card">
            <img src="{{ asset('assets/img/voiture.webp') }}" loading="lazy" alt="Deux-roues" class="icon" />
            <div class="content">
                <h3>Créez un lieu de vente</h3>
                <p>
                    Élargissez vos ambitions
                </p>
                <a href="{{ route('lieux.create') }}" class="details">Détails</a>
            </div>
        </div>
    </section>

</body>
<script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
<df-messenger style="z-index: 999;" intent="WELCOME" chat-title="Uber Bot"
    agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
