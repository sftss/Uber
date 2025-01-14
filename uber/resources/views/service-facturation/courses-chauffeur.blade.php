@extends('layouts.service-facturation-header')

<link rel="stylesheet" href="{{ URL::asset('assets/style/course-facturation.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div style="display: flex;flex-direction: column;align-items: center; margin: 5% auto;">
    <form style="margin: auto auto;" action="{{ route('courses-chauffeur', ['id' => $id]) }}" method="get"
        class="date-filter-form">
        <label for="start_date">Date de début :</label>
        <input type="date" name="start_date" id="start_date" value="{{ request()->get('start_date') }}">

        <label for="end_date">Date de fin :</label>
        <input type="date" name="end_date" id="end_date" value="{{ request()->get('end_date') }}">

        <button type="submit" style="margin: 2% 2%;" class="btn filter-but-eat">Filtrer</button>
    </form>

    <form action="{{ route('courses.Factures') }}" method="POST" class="formGenereInvoice">
        @csrf
        @foreach ($courses as $course)
            <input type="hidden" name="id_courses[]" value="{{ $course->id_course }}">
        @endforeach
        <button class="generateInvoiceButton" type="submit" target="_blank">Générer la
            facture</button>
    </form>
</div>

@if ($courses->isEmpty())
    <div style="font-weight: bold;font-size: 1.3rem;" class="no-courses-message">
        <p>Vous n'avez aucune course pour le moment.</p>
    </div>
@else
    <div class="courses-count">
        <p style="margin-left: 7%;font-weight: bold;">Nombre de courses affichées : {{ $courses->count() }}</p>
    </div>
    <div style="margin: 5% 5%">
        <ul>
            @foreach ($courses as $course)
                <div class="course_container" style="height: 45%;">
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
                        <li class="arrivee">Lieu d'arrivé : {{ $course->ville_arrivee }}</li>
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
                    </ul>

                    <div class="course">
                    </div>

                </div>
            @endforeach
        </ul>
    </div>
@endif
