@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">

<div class="container">
    <h2 style="text-align: center; margin: 3% 0;">Créer un plat pour le restaurant</h2>

    <form id="formCreateProduit" action="{{ route('plat.store') }}" method="POST">
        @csrf

        <!-- Champ caché pour l'ID du restaurant -->
        <input type="hidden" name="id_restaurant" value="{{ $restaurant_id }}">

        <div class="form-group">
            <label for="libelle_plat">Libellé du plat</label>
            <input type="text" class="form-control" id="libelle_plat" name="libelle_plat" value="{{ old('libelle_plat') }}" required>
        </div>

        <div class="form-group">
            <label for="prix_plat">Prix du plat</label>
            <input type="number" style="width:15%" step="1" value=0 min=0  class="form-control" id="prix_plat" name="prix_plat" value="{{ old('prix_plat') }}" required>
        </div>

        <div class="form-group">
            <label for="photo_plat">URL de la photo du plat</label>
            <input type="text" class="form-control" id="photo_plat" name="photo_plat" value="{{ old('photo_plat') }}">
        </div>

        <div class="form-group">
            <label for="categorie_id">Catégorie</label>
            <select class="form-control" id="categorie_id" name="categorie_id">
                <option value="">Choisir une catégorie</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id_categorie_produit }}" {{ old('categorie_id') == $category->id_categorie_produit ? 'selected' : '' }}>
                        {{ $category->libelle_categorie }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="font-weight:bold">Créer le plat</button>
    </form>
</div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
