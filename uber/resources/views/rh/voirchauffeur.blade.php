@extends('layouts.rh-header')

<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/style/servicecourse.css') }}" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/flatpickr.min.css">

<!-- Lien vers la locale française pour Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/l10n/fr.js"></script>

<!-- Lien vers le script Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/flatpickr.min.js"></script>

<body>
    <div class="container" style="margin: 3% 3%">
        <!-- Plus besoin du formulaire pour le département -->
        <h2>Chauffeurs en attente</h2>

        @if (isset($chauffeurs) && count($chauffeurs) > 0)
            <script>
                const chauffeurs = @json($chauffeurs);
                console.log(chauffeurs);
            </script>

            @foreach ($chauffeurs as $chauffeur)
                <div class="course_container">
                    <h3 class="course_title">Chauffeur : {{ $chauffeur->prenom_chauffeur }} {{ $chauffeur->nom_chauffeur }}</h3>
                    <ul class="course_details">
                        <li class="date_naissance">Date de naissance : 
                            {{ \Carbon\Carbon::parse($chauffeur->date_naissance_chauffeur)->locale('fr')->isoFormat('LL') }}
                        </li>
                        <li class="sexe">Sexe : 
                            @if ($chauffeur->sexe_chauffeur == 'H') Homme
                            @elseif($chauffeur->sexe_chauffeur == 'F') Femme
                            @elseif($chauffeur->sexe_chauffeur == 'A') Autre
                            @else Non défini
                            @endif
                        </li>
                        <li class="tel">Téléphone : {{ $chauffeur->tel_chauffeur }}</li>
                        <li class="email">Email : {{ $chauffeur->mail_chauffeur }}</li>
                        <li class="entreprise">Nom entreprise : {{ $chauffeur->nom_entreprise }}</li>
                        <li class="siret">Numéro SIRET : {{ $chauffeur->num_siret }}</li>

                        <!-- Affichage conditionnel de la date RH et calendrier si la date est nulle -->
                        <li class="siret">
                            <strong>Date RH :</strong>
                            @if ($chauffeur->daterdvrh)
                                {{ \Carbon\Carbon::parse($chauffeur->daterdvrh)->format('d/m/Y') }}

                                <!-- Affichage des boutons "Accepter" et "Refuser" si la date est renseignée -->
                                <form id="formButAccept"
                                    action="{{ route('changer-statuts-rdv', ['chauffeur_id' => $chauffeur->id_chauffeur]) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" name="statut" value="accepter" class="btn btn-success">Accepter</button>
                                    <button type="submit" name="statut" value="refuser" class="btn btn-danger">Refuser</button>
                                </form>
                            @else
                                <!-- Si la date n'est pas renseignée, afficher le formulaire pour planifier un RDV -->
                                <form action="{{ route('planifier-rdv', ['chauffeur_id' => $chauffeur->id_chauffeur]) }}" method="POST">
                                    @csrf
                                    <label for="rdv_date">Choisir une date pour le rendez-vous :</label>
                                    <input type="date" id="rdv_date" name="rdv_date" required>
                                    <button type="submit">Planifier le rendez-vous</button>
                                </form>
                            @endif
                        </li>
                    </ul>
                </div>
                <hr>
            @endforeach
        @else
            <div class="no_courses_message">
                <p>Il n'y a aucun chauffeur en attente.</p>
            </div>
        @endif
    </div>
</body>

<script defer>
document.addEventListener('DOMContentLoaded', function () {
        // Initialisation de Flatpickr sur les champs de type "date"
        flatpickr("input[type='date']", {
            dateFormat: "Y-m-d", // Format d'affichage de la date
            minDate: new Date() , // Empêche la sélection de dates antérieures à aujourd'hui
            locale: "fr", // Définit la locale en français
        });
    });
</script>
