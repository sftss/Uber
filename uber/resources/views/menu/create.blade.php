@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">

<div class="container">
    <h2 style="text-align: center; margin: 3% 0;">Créer un menu pour le restaurant</h2>

    <form id="formCreateProduit" action="{{ route('menu.store') }}" method="POST">
        @csrf

        <!-- Champ caché pour l'ID du restaurant -->
        <input type="hidden" name="id_restaurant" value="{{ $restaurant_id }}">

        <div class="form-group">
            <label for="libelle_menu">Libellé du menu</label>
            <input type="text" class="form-control" id="libelle_menu" name="libelle_menu" value="{{ old('libelle_menu') }}" required>
        </div>

        <div class="form-group">
            <label for="prix_menu">Prix du menu</label>
            <input style="width:15%" type="number" step="1" value=0 min=0 class="form-control" id="prix_menu" name="prix_menu" value="{{ old('prix_menu') }}" required>
        </div>

        <div class="form-group">
            <label for="photo_menu">URL de la photo du menu</label>
            <input type="text" class="form-control" id="photo_menu" name="photo_menu" value="{{ old('photo_menu') }}">
        </div>



        <h2 style="text-align: center; margin: 3% 0; border:none;">Composition</h2>

        <!-- Sélectionner une catégorie -->
        <div class="form-group">
            <label for="categorie_id">Catégorie</label>
            <select class="form-control" id="categorie_id" name="categorie_id">
                <option value="">Choisir une catégorie</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id_categorie_produit }}" {{ old('id_categorie_produit') == $category->id_categorie_produit ? 'selected' : '' }}>
                        {{ $category->libelle_categorie }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="plat1">Nom Plat 1</label>
            <input type="text" class="form-control" id="plat1" name="plat1" value="{{ old('plat1') }}">
        </div>

        <div class="form-group">
            <label for="prixplat1">Prix Plat 1</label>
            <input style="width:15%" step="1" value=0 min=0  type="number" class="form-control" id="prixplat1" name="prixplat1" value="{{ old('prixplat1') }}">
        </div>

        <div class="form-group">
            <label for="photoplat1">Photo Plat 1 (URL)</label>
            <input type="text" class="form-control" id="photoplat1" name="photoplat1" value="{{ old('photoplat1') }}">
        </div>


        <button style="font-weight:bold" type="submit" class="btn btn-primary">Créer le menu</button>
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
