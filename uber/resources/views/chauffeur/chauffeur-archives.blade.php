@extends('layouts.chauffeur-header')

<link rel="stylesheet" href="{{ URL::asset('assets/style/course.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" /> 

@if ($courses->isEmpty())
    <div class="no-courses-message">
        <p>Vous n'avez aucune course pour le moment.</p>
    </div>
@else
    <div style="margin: 5% 5%">
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
                            @endif
                        </li>
                    </ul>

                    <div class="course">
                    </div>

                    @if (!$course->acceptee)
                        <form action="{{ route('courses.accepter', $course->id_course) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="boutonAccepter"
                                onclick="return confirm('Êtes-vous sûr de vouloir accepter cette course ?');">

                                Accepter
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </ul>
    </div>
@endif
