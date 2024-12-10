@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/compte.css') }}" />

<a href="{{ route('profil', ['id_client' => auth()->user()->id_client]) }}" class="btn btn-primary">Annuler</a>

<div class="container info-compte">
    <h1>Ajouter une carte bancaire</h1>

    <form method="POST" action="{{ route('card.store', ['id_client' => auth()->user()->id_client]) }}">
        @csrf

        

        <div class="form-group">
            <label for="num_cb">Numéro de la carte</label>
            <input type="text" id="num_cb" name="num_cb" class="form-control" value="{{ old('num_cb') }}" required>
            @error('num_cb')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="nom_cb">Nom du titulaire</label>
            <input type="text" id="nom_cb" name="nom_cb" class="form-control" value="{{ old('nom_cb') }}" required>
            @error('nom_cb')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="date_fin_validite">Date d’expiration</label>
            <input type="month" id="date_fin_validite" name="date_fin_validite" class="form-control" value="{{ old('date_fin_validite') }}" required>
            @error('date_fin_validite')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Ajouter la carte</button>
    </form>

</div>

@if (session('error'))
    <script>
        // Utilisez json_encode pour échapper correctement les caractères spéciaux
        const errorMessage = @json(session('error'));
        console.error('Erreur lors de l\'ajout de la carte :', errorMessage);
    </script>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
