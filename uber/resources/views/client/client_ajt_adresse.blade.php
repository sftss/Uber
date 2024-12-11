@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<form action="{{ route('ajouter.adresse') }}" method="POST">
    @csrf
    <div>
        <label for="rue">Rue :</label>
        <input type="text" id="rue" name="rue" required maxlength="255">
        @error('rue')
        <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="cp">Code postal :</label>
        <input type="text" id="cp" name="cp" required pattern="\d{5}" title="Entrez un code postal valide à 5 chiffres">
        @error('cp')
        <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    
    <div>
        <label for="ville">Ville :</label>
        <input type="text" id="ville" name="ville" required maxlength="100">
        @error('ville')
        <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit">Soumettre</button>
</form>
