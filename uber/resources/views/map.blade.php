@extends('layouts.header')

<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin="" />

<body>

    <link rel="stylesheet" href="{{ URL::asset('assets/style/map.css') }}" />

    <div class="container">

        <div class="inputs-container">
            <h3 class="section-title">Entrez une adresse de départ :</h3>
            <input type="text" id="inputDepart" class="form-input" value="{{ request('inputDepart', '') }}" />
            <div id="suggestionsDepart"></div>

            <h3 class="section-title">Entrez une adresse d'arrivée :</h3>
            <input type="text" id="inputArrivee" class="form-input" value="{{ request('inputArrivee', '') }}" />
            <div id="suggestionsArrivee"></div>
            <label for="dateDepart" class="section-title"><br>Quand voulez-vous partir :</label>
            <input type="datetime-local" id="dateDepart" name="dateDepart" class="form-input"
                value="{{ request('dateDepart', '2024-12-02T19:30') }}" />
            <button id="boutonValider" class="btn-submitmap">Trouver les chauffeurs</button>
        </div>

        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <div id="map"></div>
    </div>

    <!-- Charger votre script après Leaflet -->
    <script>
        // On récupère les chauffeurs en JS depuis PHP
        const chauffeurs = @json($chauffeurs);
        const categories = @json($categories);

        @if (isset($coursePourModification))
            const coursePourModification = @json($coursePourModification);
        @else
            const coursePourModification = null;
        @endif
    </script>

    <div id="tempsTrajetContainer">
        <h3>Temps de trajet estimé :</h3>
        <p id="tempsTrajet"></p>
    </div>

    <div id="chauffeursProchesContainer">

        <h3>Propositions :</h3>

        <div id="propositionsList"></div>
    </div>

    </div>

    <script src="{{ URL::asset('js/map.js') }}" defer></script>

</body>

</html>
