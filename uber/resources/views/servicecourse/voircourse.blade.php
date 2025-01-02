@extends('layouts.service-course-header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/style/servicecourse.css') }}" />

<body>
    <h1>Entrez un numéro de département</h1>
    <form action="{{ route('traitement') }}" method="POST">
        @csrf
        <label for="departement">Numéro de département (1 à 95 ou 2A/2B) :</label>
        <input type="text" id="departement" name="departement" pattern="^(0[1-9]|[1-8][0-9]|9[0-5]|2A|2B)$" required
            placeholder="Exemple : 75 ou 2A">
        <br><br>
        <button type="submit">Envoyer</button>
    </form>

    @if (isset($courses))
        @foreach ($courses as $course)
            <div class="course_container">
                <h3 class="course_title">Course numéro : {{ $course->id_course }}</h3>
                <ul class="course_details">
                    <li class="depart">Département : {{ $course->code_dep }}</li>
                    <li class="depart">Lieu de départ : {{ $course->ville_depart }}</li>
                    <li class="arrivee">Lieu d'arrivée : {{ $course->ville_arrivee }}</li>

                    <li class="prix">Prix : {{ $course->prix_reservation }} €</li>
                    <li class="date_prise_en_charge">Date de la course :
                        {{ \Carbon\Carbon::parse($course->date_prise_en_charge)->locale('fr')->isoFormat('LL') }}</li>
                    <li class="duree">Durée : {{ $course->duree_course }}</li>
                    <li class="temps_arrivee">
                        Heure d'arrivée :
                        @if ($course->heure_arrivee)
                            {{ $course->heure_arrivee }}
                        @else
                            Non spécifiée
                        @endif
                    </li>
                    <li class="acceptee">
                        @if ($course->acceptee === true)
                            Acceptée
                        @elseif ($course->acceptee === false)
                            Refusée
                        @else
                            En attente de réponse chauffeur
                        @endif
                    </li>
                    <li class="terminee">
                        @if ($course->terminee)
                            Terminée
                        @endif
                    </li>
                </ul>
                <button class='envoi'>Envoyez les propositions a tous les chauffeurs</button>
            </div>
        @endforeach
    @else
        <div class="no_courses_message">
            <p>Aucune course n'est disponible pour ce département.</p>
        </div>
    @endif

    <script>
        const courses = @json($courses);
        console.log(courses)
        /*let buttonEnvoi = document.querySelectorAll(".envoi")

        buttonEnvoi.addEventListener('click', (e) => {

        });*/
    </script>

</body>
