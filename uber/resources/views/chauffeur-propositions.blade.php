@extends('layouts.chauffeur-header')
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

            <!-- Boutons d'action -->
            <div class="actions">
                @if (is_null($course->acceptee))
                    <!-- Boutons Accepter et Refuser -->
                    <form action="{{ route('courses.accepter', $course->id_course) }}" method="POST"
                        style="display:inline;">
                        @csrf @method('PUT')
                        <button class="boutonAccepter"
                            onclick="return confirm('Êtes-vous sûr de vouloir accepter cette course ?')" type="submit">
                            Accepter
                        </button>
                    </form>
                    <form action="{{ route('courses.refuser', $course->id_course) }}" method="POST"
                        style="display:inline;">
                        @csrf @method('PUT')
                        <button class="boutonRefuser"
                            onclick="return confirm('Êtes-vous sûr de vouloir refuser cette course ?')" type="submit">
                            Refuser
                        </button>
                    </form>
                @endif

                @if ($course->acceptee === true && !$course->terminee)
                    <!-- Bouton Terminer si acceptée et non terminée -->
                    <form action="{{ route('chauffeur.terminer', $course->id_course) }}" method="POST"
                        style="display:inline;">
                        @csrf @method('PUT')
                        <button class="boutonTerminer"
                            onclick="return confirm('Êtes-vous sûr de vouloir terminer cette course ?')" type="submit">
                            Terminer
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</ul>
