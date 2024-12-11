@extends('layouts.header')

<meta content="{{ csrf_token() }}" name="csrf-token">
<link href="{{ URL::asset('assets/style/course.css') }}" rel="stylesheet">

<div id="butRetourListCourse">
    <a class="back_button" href="{{ url('/') }}"><span class="back_icon">←</span>
        <p>Retour</p>
    </a>
    <h2>Les courses</h2>
</div>

<ul>
    @foreach ($courses as $course)
        <div class="course_container">
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
                <li class="date_prise_en_charge">Date de la course : {{ $course->date_prise_en_charge }}</li>
                <li class="duree">Durée : {{ $course->duree_course }}</li>
                <li class="temps_arrivee">Heure d'arrivée : @if ($course->heure_arrivee)
                        {{ $course->heure_arrivee }}
                    @else
                        Non spécifiée
                    @endif
                </li>
                <li class="acceptee">
                    @if ($course->acceptee)
                        Acceptée
                    @else
                        En attente de réponse chauffeur
                    @endif
                </li>
                <li class="terminee">
                    @if ($course->terminee)
                        Terminée
                        <div class="review-form">
                            <form class="reviewForm" data-course-id="{{ $course->id_course }}">
                                @csrf
                                <label for="note">Note :</label>
                                <select name="note" required>
                                    <option value="1">1 - Très mauvais</option>
                                    <option value="2">2 - Mauvais</option>
                                    <option value="3">3 - Moyen</option>
                                    <option value="4">4 - Bon</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                                <label for="pourboire">Pourboire (€) :</label>
                                <input name="pourboire" type="number" min="0" step="1">
                                <button type="button" class="submitReview">Soumettre</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('courses.terminate', $course->id_course) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button class="acceptButton" type="submit">Terminé</button>
                        </form>
                        <button class=modifyButton data-course-id="{{ $course->id_course }}">Modifier</button>
                    @endif
                    <div class=course></div>
                    @if (!$course->terminee)
                        <form action="{{ route('courses.destroy', $course->id_course) }}"method=POST
                            style=display:inline>@csrf
                            @method('DELETE') <button class=delete_button type=submit
                                onclick='return confirm("Êtes-vous sûr de vouloir supprimer cette course ?")'>Annuler</button>
                        </form>
                    @endif
                </div>
            </div>
    </li>
</ul>
</div>
@endforeach
</ul>

</ul>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Écouter les soumissions de formulaire pour l'avis
        document.querySelectorAll('.submitReview').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.reviewForm');
                const courseId = form.getAttribute('data-course-id');
                const formData = new FormData(form);

                fetch(`/courses/${courseId}/review`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Avis soumis avec succès !');
                            // Optionnel : Mettre à jour l'interface sans recharger la page
                            form.innerHTML =
                                '<p>Merci pour votre avis !</p>'; // Remplacer le formulaire par un message de confirmation
                        } else {
                            alert('Erreur lors de la soumission de l\'avis.');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert(
                            'Une erreur s\'est produite lors de la soumission de l\'avis.'
                        );
                    });
            });
        });
    });
</script>
