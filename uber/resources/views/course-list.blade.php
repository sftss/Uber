<link rel="stylesheet" href="{{ URL::asset('assets/style/course.css') }}" />

<div class="navbar">
<a href="{{ url('/') }}" class="back_button">
    <span class="back_icon">&#8592;</span> <!-- Flèche vers la gauche -->
    <p>Retour</p>
</a>

</a>

    </a>
    <a href="{{ route('login') }}" class="login">Login</a>
    <a href="{{ route('register') }}" class="register">Register</a>
</div>


@extends('layouts.app')


@section('content')
    <h2>Les courses</h2>
    <ul>
        @foreach ($courses->reverse() as $course)
        <div class="course_container">
            <h3 class="course_title">Course numéro : {{ $course->id_course }}</h3>
            <ul class="course_details">
                <li class="chauffeur">
                    @if(is_null($course->id_chauffeur))
                    Vélo : {{ $course->id_velo }}
                        @else
                        Chauffeur : {{ $course->id_chauffeur }}
                        @endif
                </li>
                <li class="depart">Lieu de départ : {{ $course->id_lieu_depart }}</li>
                <li class="arrivee">Lieu d'arrivée : {{ $course->id_lieu_arrivee }}</li>
                <li class="prix">Prix : {{ $course->prix_reservation }} €</li>
                <li class="date_prise_en_charge">Date de la course : {{ $course->date_prise_en_charge }}</li>
                <li class="duree">Durée : {{ $course->duree_course }}</li>
                <li class="temps_arrivee">Heure d'arrivée : {{ $course->heure_arrivee }}</li>
                <li class="terminee"> 
                    @if($course->terminee)
                        Terminée
                    @else
                        En cours
                    @endif
                </li>
            </ul>

            <!-- Bouton Modifier -->
            <button class="btn btn-modifier" data-toggle="modal" data-target="#editCourseModal" 
                    data-id="{{ $course->id_course }}"
                    data-chauffeur="{{ $course->id_chauffeur }}"
                    data-depart="{{ $course->id_lieu_depart }}"
                    data-arrivee="{{ $course->id_lieu_arrivee }}"
                    data-prix="{{ $course->prix_reservation }}"
                    data-date="{{ $course->date_prise_en_charge }}"
                    data-duree="{{ $course->duree_course }}"
                    data-temps="{{ $course->temps_arrivee }}">
                Modifier
            </button>

            @if(!$course->terminee)
            <form action="{{ route('courses.destroy', $course->id_course) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete_button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette course ?');">
                    Annuler
                </button>
            </form>
            @endif
        </div>
        @endforeach
    </ul>

<!-- Modale de modification -->
<div class="modal fade" id="editCourseModal" tabindex="-1" role="dialog" aria-labelledby="editCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCourseModalLabel">Modifier la course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCourseForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="course_id" name="course_id">

                    <div class="form-group">
                        <label for="chauffeur">Chauffeur</label>
                        <input type="text" class="form-control" id="chauffeur" name="chauffeur">
                    </div>
                    <div class="form-group">
                        <label for="depart">Lieu de départ</label>
                        <input type="text" class="form-control" id="depart" name="depart">
                    </div>
                    <div class="form-group">
                        <label for="arrivee">Lieu d'arrivée</label>
                        <input type="text" class="form-control" id="arrivee" name="arrivee">
                    </div>
                    <div class="form-group">
                        <label for="prix">Prix</label>
                        <input type="number" class="form-control" id="prix" name="prix">
                    </div>
                    <div class="form-group">
                        <label for="date">Date de prise en charge</label>
                        <input type="date" class="form-control" id="date" name="date">
                    </div>
                    <div class="form-group">
                        <label for="duree">Durée</label>
                        <input type="text" class="form-control" id="duree" name="duree">
                    </div>
                    <div class="form-group">
                        <label for="temps">Heure d'arrivée</label>
                        <input type="time" class="form-control" id="temps" name="temps">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="saveChangesBtn">Enregistrer les modifications</button>
            </div>
        </div>
    </div>
</div>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- CDN pour jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- CDN pour Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $('#editCourseModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        
        // Récupération des données de la course depuis les attributs data-* du bouton
        var courseId = button.data('id');
        var chauffeur = button.data('chauffeur');
        var depart = button.data('depart');
        var arrivee = button.data('arrivee');
        var prix = button.data('prix');
        var date = button.data('date');
        var duree = button.data('duree');
        var temps = button.data('temps');
        
        // Remplir les champs du formulaire avec les valeurs existantes
        $('#course_id').val(courseId);

        // Si le chauffeur est null ou vide, afficher "Vélo" dans le champ "chauffeur"
        if (!chauffeur || chauffeur === '') {
            $('#chauffeur').val('Vélo');
        } else {
            $('#chauffeur').val(chauffeur);
        }

        // Remplir les autres champs du formulaire avec les données de la course
        $('#depart').val(depart);
        $('#arrivee').val(arrivee);
        $('#prix').val(prix);
        $('#date').val(date);
        $('#duree').val(duree);
        
        // Initialiser le champ "temps" (heure d'arrivée) avec la valeur existante
        if (temps) {
            $('#temps').val(temps);  // Remplir avec la valeur de "temps"
        } else {
            $('#temps').val('');  // Si pas de valeur, laisser vide
        }
    });

    // Action lors de la sauvegarde des modifications
    $('#saveChangesBtn').click(function() {
        var courseId = $('#course_id').val();
        var formData = $('#editCourseForm').serialize(); // Sérialiser les données du formulaire

        $.ajax({
            url: '/courses/' + courseId,  // Assurez-vous que l'URL est correcte
            type: 'PUT',
            data: formData,
            success: function(response) {
                alert(response.success);
                $('#editCourseModal').modal('hide');
                location.reload(); // Recharger la page après la mise à jour
            },
            error: function(xhr, status, error) {
                var response = xhr.responseJSON;
                console.error(response);  // Afficher la réponse d'erreur dans la console
                alert('Erreur lors de la modification: ' + response.message);
            }
        });
    });
</script>


@endsection