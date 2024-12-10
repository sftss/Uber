@extends('layouts.header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ URL::asset('assets/style/course.css') }}" />
<div id="butRetourListCourse">
    <a href="{{ url('/') }}" class="back_button">
        <span class="back_icon">&#8592;</span> <!-- Flèche vers la gauche -->
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
                        Chauffeur : {{ $course->prenom_chauffeur }}
                        {{ $course->nom_chauffeur }}
                    @endif
                </li>
                <li class="depart">Lieu de départ : {{ $course->ville_depart }}</li>
                <li class="arrivee">Lieu d'arrivée : {{ $course->ville_arrivee }}</li>
                <li class="prix">Prix : {{ $course->prix_reservation }} €</li>
                <li class="date_prise_en_charge">Date de la course : {{ $course->date_prise_en_charge }}</li>
                <li class="duree">Durée : {{ $course->duree_course }}</li>
                <li class="temps_arrivee">Heure d'arrivée :
                    @if ($course->heure_arrivee)
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
                    @else
                        <div>
                            <p>En cours</p>
                            <div class="butCourse">
                                <form action="{{ route('courses.terminate', $course->id_course) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    <button type="submit" class="acceptButton">Terminé</button>
                                </form>
                                <button class="modifyButton"
                                    data-course-id="{{ $course->id_course }}">Modifier</button>
                            </div>
                        </div>
                    @endif
                </li>

            </ul>

            <div class="course">
            </div>

            @if (!$course->terminee)
                <form action="{{ route('courses.destroy', $course->id_course) }}" method="POST"
                    style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete_button"
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette course ?');">
                        Annuler
                    </button>
                </form>
            @endif
        </div>
    @endforeach
    <div id="reviewModal" class="modal hidden">
        <div class="modal-content">
            <span class="close-button" id="closeModal">&times;</span>
            <h3>Donnez votre avis</h3>
            <form id="reviewForm">
                @csrf
                <input type="hidden" id="courseId" name="course_id">
                <label for="note">Note :</label>
                <select id="note" name="note" required>
                    <option value="1">1 - Très mauvais</option>
                    <option value="2">2 - Mauvais</option>
                    <option value="3">3 - Moyen</option>
                    <option value="4">4 - Bon</option>
                    <option value="5">5 - Excellent</option>
                </select>
                <label for="avis">Avis :</label>
                <textarea id="avis" name="avis" rows="4" placeholder="Écrivez votre avis ici..."></textarea>
                <button type="submit" class="submit-button">Soumettre</button>

                <label for="pourboire">Pourboire (€) :</label>
                <input type="number" id="pourboire" name="pourboire" step="0.01" min="0">
            </form>
        </div>
    </div>
</ul>
<script>
    document.querySelectorAll('.modifyButton').forEach(function(button) {
        button.addEventListener('click', function() {
            var courseElement = this.closest(
                '.course_container'); // Trouve le conteneur de la course parent

            // Extraction de l'id_course
            var id = courseElement.querySelector('.course_title').textContent.split(":")[1].trim();
            console.log(id)

            // Envoie l'utilisateur vers la page de modification avec l'id_course
            window.location.href = "{{ url('/map') }}/" + encodeURIComponent(id);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('reviewModal');
        const closeModal = document.getElementById('closeModal');
        const reviewForm = document.getElementById('reviewForm');
        const courseIdInput = document.getElementById('courseId');

        document.querySelectorAll('.acceptButton').forEach(button => {
            button.addEventListener('click', function() {
                const courseId = this.closest('.course_container').querySelector(
                        '.course_title')
                    .textContent.split(":")[1].trim();
                courseIdInput.value = courseId;
                modal.classList.add('visible');
            });
        });

        closeModal.addEventListener('click', () => {
            modal.classList.remove('visible');
        });

        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent normal form submission

            const courseId = courseIdInput.value;
            const formData = new FormData(reviewForm);

            fetch(`/courses/${courseId}/review`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Avis soumis avec succès !');
                        modal.classList.remove('visible');
                        // Optional: Refresh the page or update the course display
                        location.reload(); // Reloads the page to reflect changes
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur s\'est produite lors de la soumission de l\'avis.');
                });
        });
    });
</script>
