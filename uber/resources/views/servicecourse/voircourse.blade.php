@extends('layouts.service-course-header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/style/servicecourse.css') }}" />

<body>
    
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container" style="margin: 3% 0 3%">

        <h1>Entrez un numéro de département</h1>
        <form action="{{ route('traitement') }}" method="POST">
            @csrf
            <label for="departement">Numéro de département (1 à 95 ou 2A/2B) :</label>
            <input type="text" id="departement" name="departement" pattern="^(0[1-9]|[1-8][0-9]|9[0-5]|2A|2B)$"
                required placeholder="Exemple : 75 ou 2A">
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
                            {{ \Carbon\Carbon::parse($course->date_prise_en_charge)->locale('fr')->isoFormat('LL') }}
                        </li>
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
                    <button class="envoi" data-id="{{ $course->id_course }}"
                        data-course="{{ json_encode($course, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS) }}">
                        Envoyez les propositions à tous les chauffeurs
                    </button>

                </div>
            @endforeach
            <script>
    const filteredChauffeurs = @json($filteredChauffeurs);
    console.log(filteredChauffeurs);

    // Créer une liste distincte des IDs des chauffeurs
    const chauffeurIds = [...new Set(filteredChauffeurs.map(chauffeur => chauffeur.id_chauffeur))];
    console.log(chauffeurIds);

    let isRequestInProgress = false;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    document.querySelectorAll(".envoi").forEach(button => {
        button.addEventListener("click", function(event) {
            // Vérifier si une requête est déjà en cours
            if (isRequestInProgress) return;  // Si une requête est en cours, on ne fait rien

            // Marquer qu'une requête est en cours
            isRequestInProgress = true;

            // Récupération des données spécifiques à la course à partir des attributs du bouton
            const courseId = this.getAttribute("data-id");
            const courseData = JSON.parse(this.getAttribute("data-course"));

            console.log("Course ID :", courseId);
            console.log("Course Data :", courseData);

            // Construire l'objet `envoi` avec les données de la course
            const envoi = {
                acceptee: courseData.acceptee,
                id_velo: courseData.id_velo,
                terminee: courseData.terminee,
                prix_reservation: courseData.prix_reservation,
                duree_course: courseData.duree_course,
                date_prise_en_charge: courseData.date_prise_en_charge,
                id_lieu_depart: courseData.id_lieu_depart,
                id_lieu_arrivee: courseData.id_lieu_arrivee,
                id_client: courseData.id_client,
                chauffeurtab: filteredChauffeurs, // Les chauffeurs filtrés restent inchangés
                id_course: courseId,
            };

            // Première requête : Créer une nouvelle course temporaire
            fetch("/create-temp-courses", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify(envoi),
            })
            .then((response) => {
                if (!response.ok) {
                    return response.text().then((text) => {
                        throw new Error(`Erreur serveur : ${response.status} - ${text}`);
                    });
                }
                return response.json();
            })
            .then((data) => {
                console.log("Réponse du serveur :", data);
                if (data.status === "success") {
                    alert(data.message);
                    
                    // Si la création de la course est réussie, appeler l'envoi des notifications
                    sendNotifChauffeur(chauffeurIds);  // Appel de la méthode pour envoyer la notification
                } else {
                    console.error("Erreur dans les données :", data.message);
                    alert(`Erreur : ${data.message}`);
                }
            })
            .catch((error) => {
                console.error("Erreur lors de la requête :", error);
                alert(`Une erreur est survenue : ${error.message}`);
            })
            .finally(() => {
                isRequestInProgress = false;
            });
        });
    });

    // Fonction pour appeler l'API qui enverra la notification aux chauffeurs
function sendNotifChauffeur(chauffeurIds) {
    fetch("/send-notifications", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({ chauffeurs: chauffeurIds }), // Changer 'chauffeur_ids' en 'chauffeurs'
    })
    .then((response) => {
        // Vérifier si la réponse est au format JSON
        if (!response.ok) {
            // Si la réponse n'est pas OK, afficher le contenu de la réponse pour aider au débogage
            return response.text().then((text) => {
                console.error("Réponse du serveur (non-OK) : ", text);
                throw new Error(`Erreur serveur : ${response.status} - ${text}`);
            });
        }

        // Tenter de parser la réponse en JSON
        return response.json();
    })
    .then((data) => {
        console.log("Réponse de l'envoi de notification :", data);
        if (data.status === "success") {
            alert('Notifications envoyées avec succès aux chauffeurs.');
        } else {
            alert(`Erreur lors de l'envoi des notifications : ${data.message}`);
        }
    })
    .catch((error) => {
        console.error("Erreur lors de l'envoi des notifications :", error);
        alert(`Une erreur est survenue : ${error.message}`);
    });
}

</script>

        @else
            <div class="no_courses_message">
                <p>Aucune course n'est disponible pour ce département.</p>
            </div>
        @endif
        <div>

</body>
