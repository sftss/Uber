@extends('layouts.header')

@section('content')
    <div class="rate-container">
        <h2>Noter la course #{{ $course->id_course }}</h2>
        <p>Chauffeur : {{ $course->prenom_chauffeur }} {{ $course->nom_chauffeur }}</p>

        <form action="{{ route('courses.storeRating', $course->id_course) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="rating">Note (1 à 5) :</label>
                <select id="rating" name="rating" required>
                    <option value="">Sélectionnez une note</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="comment">Commentaire (facultatif) :</label>
                <textarea id="comment" name="comment" rows="4" placeholder="Partagez votre expérience..."></textarea>
            </div>

            <button type="submit" class="btn-submit">Soumettre</button>
        </form>
    </div>
@endsection
