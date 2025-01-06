@extends('layouts.chauffeur-header')
<link href="{{ URL::asset('assets/style/coursechauffeur.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" /> 


@if ($courses->isEmpty())
    <div class="no-courses-message">
        <p>Vous n'avez aucune course pour le moment.</p>
    </div>
@else
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
                    <li class="date_prise_en_charge">Date de la course :
                        {{ \Carbon\Carbon::parse($course->date_prise_en_charge)->locale('fr')->isoFormat('LL') }}</li>
                    <li class="duree">Durée : {{ $course->duree_course }}</li>
                    <li class="temps_arrivee">
                        Heure d'arrivée :
                        @if ($course->heure_arrivee)
                            {{ $course->heure_arrivee }}
                        @else
                            Non spécifiée
                        @endif
                    </li>
                    <li class="terminee">
                        @if ($course->acceptee === true && !$course->terminee && $course->validationchauffeur != true)
                            <!-- Bouton Terminer si acceptée et non terminée -->
                            <form action="{{ route('chauffeur.terminer', $course->id_course) }}" method="POST"
                                style="display:inline;">
                                @csrf @method('PUT')
                                <button class="boutonTerminer"
                                    onclick="return confirm('Êtes-vous sûr de vouloir terminer cette course ?')"
                                    type="submit">
                                    Terminer
                                </button>
                            </form>
                        @else 
                            Terminée
                        @endif
                    </li>
                </ul>

                
            </div>
        @endforeach
    </ul>
@endif
