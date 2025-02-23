@extends('layouts.header')

<meta content="{{ csrf_token() }}" name="csrf-token">
<link href="{{ URL::asset('assets/style/course.css') }}" rel="stylesheet">

<div id="butRetourListCourse">
    <!-- {{-- <a class="back_button" href="{{ url('/') }}">
        <span class="back_icon">←</span>
        <p>Retour</p>
    </a> --}} -->
    <h2>Les courses</h2>
</div>
@if ($courses->isEmpty())
    <div class="no-courses-message">
        <p>Vous n'avez aucune course pour le moment.</p>
        <p id="pouvezIci"><a class="nav-link" href="{{ url('/map') }}">Vous pouvez aller réserver une course ici</a>
        </p>
    </div>
@else
    <ul>
        @foreach ($courses as $course)
            <div class="course_container pageClient">
                <h3 class="course_title">Course numéro : {{ $course->id_course }}</h3>
                <ul class="course_details">
                    <li class="chauffeur">
                        @if ($course->chauffeur)
                            Chauffeur : {{ $course->chauffeur->prenom_chauffeur }}
                            {{ $course->chauffeur->nom_chauffeur }}
                        @elseif ($course->id_velo)
                            Vélo : {{ $course->id_velo }}
                        @else
                        @endif
                    </li>
                    <li class="depart">Lieu de départ : {{ $course->lieuDepart->rue }} {{ $course->lieuDepart->cp }}
                        {{ $course->lieuDepart->ville }}</li>
                    <li class="arrivee">Lieu d'arrivée : {{ $course->lieuArrivee->rue }}
                        {{ $course->lieuArrivee->cp }}
                        {{ $course->lieuArrivee->ville }}</li>
                    <li class="prix">Prix : {{ $course->prix_reservation }} €</li>
                    <li class="date_prise_en_charge">Date de la course :
                        {{ \Carbon\Carbon::parse($course->date_prise_en_charge)->locale('fr')->isoFormat('LL') }}</li>
                    <li class="duree">Durée : {{ \Carbon\Carbon::parse($course->duree_course)->format('H') }}h
                        {{ \Carbon\Carbon::parse($course->duree_course)->format('i') }}min</li>
                    <li class="temps_arrivee">
                        Heure d'arrivée :
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
                            @if (!isset($course->est_facture))
                                <div class="review-form">
                                    <form action="{{ route('courses.submitReview', $course->id_course) }}"
                                        method="POST" class="reviewForm">
                                        @csrf
                                        <label for="note" id="txtNoteCourse">Note :</label>
                                        <select id="selectNoteCourse" name="note" required>
                                            <option value="1">1 - Très mauvais</option>
                                            <option value="2">2 - Mauvais</option>
                                            <option value="3">3 - Moyen</option>
                                            <option value="4">4 - Bon</option>
                                            <option value="5" selected>5 - Excellent</option>
                                        </select>
                                        <label for="pourboire" id="txtPourboireCourse">Pourboire (€) :</label>
                                        <input id="champPourboire" name="pourboire" type="number" min="0"
                                            step="1" max="{{ $course->prix_reservation }}" value="0">
                                        <button class="submitReview" type="submit">Soumettre</button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('courses.Facture', $course->id_course) }}" method="POST"
                                    style="display:inline" class="formGenereInvoice">
                                    @csrf
                                    <label for="langue" id="labelLangue">Langue :</label>
                                    <select id="langue" name="langue">
                                        <option value="fr" selected>Français</option>
                                        <option value="en">English</option>
                                        <option value="es">Español</option>
                                        <option value="de">Deutschland</option>
                                        <option value="it">Italiano</option>
                                    </select>
                                    <button class="generateInvoiceButton" type="submit" target="_blank">Générer ma
                                        facture</button>
                                </form>
                            @endif
                        @else
                            @if (is_null($course->acceptee))
                                <form action="{{ route('courses.update', ['id' => $course->id_course]) }}"
                                    method="POST" id="formCourseListe">
                                    @csrf
                                    @method('PUT')
                                    <button class="butMpageClient modifyButton" type="submit"
                                        data-course-id="{{ $course->id_course }}">Modifier</button>
                                </form>
                                <form action="{{ route('courses.destroy', $course->id_course) }}" method="POST"
                                    style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="butDpageClient delete_button" type="submit"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette course ?')">Annuler</button>
                                </form>
                            @elseif ($course->acceptee === true)
                                <form action="{{ route('client.terminer', $course->id_course) }}" method="POST"
                                    style="display:inline">
                                    @csrf
                                    @method('PUT')
                                    <button class="acceptButton butTpageClient" type="submit"
                                        onclick="return confirm('Êtes-vous sûr de vouloir terminer cette course ?')">Terminer</button>
                                </form>
                                <form action="{{ route('courses.update', ['id' => $course->id_course]) }}"
                                    method="POST" id="formCourseListe">
                                    @csrf
                                    @method('PUT')
                                    <button class="butMpageClient modifyButton" type="submit"
                                        data-course-id="{{ $course->id_course }}">Modifier</button>
                                </form>
                                <form action="{{ route('courses.destroy', $course->id_course) }}" method="POST"
                                    style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="butDpageClient delete_button" type="submit"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette course ?')">Annuler</button>
                                </form>
                            @endif
                        @endif
                    </li>
                </ul>
            </div>
        @endforeach
    </ul>
@endif
<div id="butPagination">
    {{ $courses->links('pagination::default') }}
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
                    console.log("Facture :", data.Facture);
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
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger style="z-index: 999;"  chat-title="Chatbot" chat-icon="{{ asset('assets/img/chat.png') }}"
        agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
