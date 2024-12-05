<link rel="stylesheet" href="{{ URL::asset('assets/style/course.css') }}" />

<div class="navbar">
    <a href="{{ url('/') }}" class="back_button">
    <img src="chemin/vers/ton-logo.svg" alt="Retour" class="back_icon" />
    <p>Retour</p>
</a>

        <p>Retour</p>
    </a>
    <a href="{{ route('login') }}" class="login">Login</a>
    <a href="{{ route('register') }}" class="register">Register</a>
</div>


@extends('layouts.app')


@section('content')
    <h2>Les courses</h2>
    <ul>
        @foreach ($courses as $course)
        <div class="course_container">
            <h3 class="course_title">Course numéro : {{ $course->id_course }}</h3>
            <ul class="course_details">
                <li class="chauffeur">Chauffeur : {{ $course->id_chauffeur }}</li>
                <li class="depart">Lieu de départ : {{ $course->id_lieu_depart }}</li>
                <li class="arrivee">Lieu d'arrivée : {{ $course->id_lieu_arrivee }}</li>
                <li class="prix">Prix : {{ $course->prix_reservation }} €</li>
                <li class="date_prise_en_charge">Date de la course : {{ $course->date_prise_en_charge }}</li>
                <li class="duree">Durée : {{ $course->duree_course }}</li>
                <li class="temps_arrivee">Heure d'arrivée : {{ $course->temps_arrivee }}</li>
            </ul>
        </div>
        @endforeach
    </ul>
@endsection

