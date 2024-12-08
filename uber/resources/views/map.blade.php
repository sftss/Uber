@extends('layouts.header')

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uber</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

</head>

<body>

    <link rel="stylesheet" href="{{ URL::asset('assets/style/map.css') }}" />

    <div class="container">

        <div class="inputs-container">
            <h3 class="section-title">Entrez une adresse de départ :</h3>
            <input type="text" id="inputDepart" class="form-input" />
            <div id="suggestionsDepart"></div>

            <h3 class="section-title">Entrez une adresse d'arrivée :</h3>
            <input type="text" id="inputArrivee" class="form-input" />
            <div id="suggestionsArrivee"></div>
            <label for="dateDepart" class="section-title"><br>Quand voulez-vous partir :</label>
            <input type="datetime-local" id="dateDepart" name="dateDepart" class="form-input"
                value="2024-12-02T19:30" />
            <button id="boutonValider" class="btn-submitmap">Trouver les chauffeurs</button>

        </div>
        <script src="https://unpkg.com/leaflet/dist/leaflet.js" ></script>
        <div id="map"></div>
    </div>

    

    <!-- Charger votre script après Leaflet -->
    <script>
    // On récupère les chauffeurs en JS depuis PHP
    const chauffeurs = @json($chauffeurs);
    const categories = @json($categories);

    @if(isset($coursePourModification))
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
