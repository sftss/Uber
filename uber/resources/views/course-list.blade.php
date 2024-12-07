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
            <button id="modifyButton" class="btn btn-primary" >Modifier</button>



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
    document.querySelectorAll('#modifyButton').forEach(function(button) {
        button.addEventListener('click', function() {
            var courseElement = this.closest('.course_container');  // Trouve le conteneur de la course parent

            var heure = courseElement.querySelector('.temps_arrivee').textContent.split(":")[1].trim();
            var datePriseEnCharge = courseElement.querySelector('.date_prise_en_charge').textContent.split(":")[1].trim()+"T"+heure;
            
            var id = courseElement.querySelector('.course_title').textContent.split(":")[1].trim();  // Extraction de l'id_course

            // Envoie l'utilisateur vers la page de modification avec les paramètres requis
            window.location.href = "{{ url('/map') }}?depart=" + encodeURIComponent(courseElement.querySelector('.depart').textContent.split(":")[1].trim()) +
                "&arrivee=" + encodeURIComponent(courseElement.querySelector('.arrivee').textContent.split(":")[1].trim()) +
                "&datePriseEnCharge=" + encodeURIComponent(datePriseEnCharge) +
                "&modification=true" +
                "&id=" + encodeURIComponent(id);
        });
    });
</script>

@endsection
