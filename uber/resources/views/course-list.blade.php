@extends('layouts.header')

<meta content="{{ csrf_token() }}" name="csrf-token">
<link href="{{ URL::asset('assets/style/course.css') }}" rel="stylesheet">

<div id="butRetourListCourse">
    <a class="back_button" href="{{ url('/') }}">
        <span class="back_icon">←</span>
        <p>Retour</p>
    </a>
    <h2>Les courses</h2>
</div>

<ul>
    @foreach ($courses as $course)
        <div class="course_container pageClient">
            <h3 class="course_title">Course numéro : {{ $course->id_course }}</h3>
            <ul class="course_details">
                <li class="chauffeur">
                    @if (is_null($course->prenom_chauffeur))
                        Vélo : {{ $course->id_velo }}
                    @else
                        Chauffeur : {{ $course->prenom_chauffeur }} {{ $course->nom_chauffeur }}
                    @endif
                </li>
                <li class="depart">Lieu de départ : {{ $course->ville_depart }}</li>
                <li class="arrivee">Lieu d'arrivée : {{ $course->ville_arrivee }}</li>
                <li class="prix">Prix : {{ $course->prix_reservation }} €</li>
                <li class="date_prise_en_charge">Date de la course :
                    {{ \Carbon\Carbon::parse($course->date_prise_en_charge)->format('d-m-Y') }}</li>
                <li class="duree">Durée :
                    {{ \Carbon\Carbon::parse($course->duree_course)->format('H') }}h
                    {{ \Carbon\Carbon::parse($course->duree_course)->format('i') }}min
                </li>
                <li class="temps_arrivee">Heure d'arrivée :
                    @if ($course->heure_arrivee)
                        {{ \Carbon\Carbon::parse($course->heure_arrivee)->format('H') }}h
                        {{ \Carbon\Carbon::parse($course->heure_arrivee)->format('i') }}min
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
                        <div class="review-form">
                            <form class="reviewForm" action="{{ route('courses.submitReview', $course->id_course) }}"
                                method="POST">
                                @csrf
                                <label for="note" id="txtNoteCourse">Note :</label>
                                <select id="selectNoteCourse" name="note" required>
                                    <option value=1>1 - Très mauvais</option>
                                    <option value=2>2 - Mauvais</option>
                                    <option value=3>3 - Moyen</option>
                                    <option value=4>4 - Bon</option>
                                    <option value=5 selected>5 - Excellent</option>
                                </select>
                                <label for="pourboire" id="txtPourboireCourse">Pourboire (€) :</label>
                                <input id="champPourboire" min="0" name="pourboire" step="1" type="number"
                                    value="0">
                                <button class="submitReview" type="submit">Soumettre</button>
                            </form>
                        </div>
                    @elseif ($course->acceptee === true || is_null($course->acceptee))
                        @if ($course->terminee != true && $course->acceptee === true)
                            <form action="{{ route('client.terminer', $course->id_course) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="acceptButton butTpageClient"
                                    onclick="return confirm('Êtes-vous sûr de vouloir terminer cette course ?');">

                                    Terminer
                                </button>

                            </form>
                        @endif
                        <form id="formCourseListe" action="{{ route('courses.update', ['id' => $course->id_course]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <button class="modifyButton butMpageClient" type="submit"
                                data-course-id="{{ $course->id_course }}">Modifier</button>
                        </form>
                    @endif

                    <div class="course"></div>

                    @if (!$course->terminee && ($course->acceptee === true || is_null($course->acceptee)))
                        <form action="{{ route('courses.destroy', $course->id_course) }}" method="POST"
                            style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="delete_button butDpageClient" type="submit"
                                onclick='return confirm("Êtes-vous sûr de vouloir supprimer cette course ?")'>Annuler</button>
                        </form>
                    @endif
                </li>
            </ul>
        </div>
    @endforeach
</ul>

<!-- Boutons de pagination -->
<div id="butPagination">
    {{ $courses->links('pagination::default') }}
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ 'python/facture.py' }}"></script>
<!-- <script src="{{ 'js/course.js' }}" defer></script> -->
<script>
    function generateInvoice(courseId, formData) {
        fetch(`/courses/${courseId}/generate-invoice`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
                },
                body: formData,
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("Facture générée avec succès !");
                    console.log("Facture :", data.invoice);
                    // Optionnel : Afficher ou télécharger la facture
                } else {
                    alert("Erreur lors de la génération de la facture.");
                }
            })
            .catch((error) => {
                console.error("Erreur:", error);
                alert("Une erreur s'est produite lors de la génération de la facture.");
            });
    }
</script>
