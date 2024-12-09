<link rel="stylesheet" href="{{ URL::asset('assets/style/course.css') }}" />



@extends('layouts.header')

<a href="{{ url('/') }}" class="back_button">
    <span class="back_icon">&#8592;</span> <!-- Flèche vers la gauche -->
    <p>Retour</p>
</a>
    <h2>Les courses</h2>
    <ul>
        @foreach ($courses->reverse() as $course)
        <div class="course_container">
            <h3 class="course_title">Course numéro : {{ $course->id_course }}</h3>
            <ul class="course_details">
                <li class="chauffeur">
                    @if(is_null($course->prenom_chauffeur))
                    Vélo : {{ $course->id_velo }}
                        @else
                        Chauffeur : {{ $course->prenom_chauffeur }}
                        @endif
                </li>
                <li class="depart">Lieu de départ : {{ $course->ville_depart }}</li>
                <li class="arrivee">Lieu d'arrivée : {{ $course->ville_arrivee }}</li>
                <li class="prix">Prix : {{ $course->prix_reservation }} €</li>
                <li class="date_prise_en_charge">Date de la course : {{ $course->date_prise_en_charge }}</li>
                <li class="duree">Durée : {{ $course->duree_course }}</li>
                <li class="temps_arrivee">Heure d'arrivée : 
                    @if($course->heure_arrivee)
                        {{ $course->heure_arrivee }}
                    @else
                        Non spécifiée
                    @endif
                </li>
                <li class="terminee"> 
                    @if($course->terminee)
                        Terminée
                    @else
                        <div>
                            <p>En cours</p>
                            <button class="modifyButton" data-course-id="{{ $course->id_course }}">Modifier</button>
                        </div>
                    @endif
                </li>
            </ul>

            <div class="course">
    </div>



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
<script>
     document.querySelectorAll('.modifyButton').forEach(function(button) {
        button.addEventListener('click', function() {
            var courseElement = this.closest('.course_container');  // Trouve le conteneur de la course parent

            // Extraction de l'id_course
            var id = courseElement.querySelector('.course_title').textContent.split(":")[1].trim();  
            console.log(id)

            // Envoie l'utilisateur vers la page de modification avec l'id_course
            window.location.href = "{{ url('/map') }}/" + encodeURIComponent(id);
        });
    });
</script>
