@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">

<div class="container">
    <h2 style="text-align: center; margin: 3% 0;">Créer un produit pour le restaurant</h2>

    <form id="formCreateProduit" action="{{ route('produit.store') }}" method="POST">
        @csrf

        <input type="hidden" name="id_restaurant" value="{{ $restaurant_id }}">


        <div class="form-group">
            <label for="libelle_produit">Libellé du produit</label>
            <input type="text" class="form-control" id="libelle_produit" name="libelle_produit" value="{{ old('libelle_produit') }}" required>

        </div>

        <div class="form-group">
            <label for="prix_produit">Prix du produit</label>
            <input style="width:15%" min=0 value=0 type="number" step="1" class="form-control" id="prix_produit" name="prix_produit" value="{{ old('prix_produit') }}" required>
        </div>

        <div class="form-group">
            <label for="photo_produit">URL de la photo du produit</label>
            <input type="text" class="form-control" id="photo_produit" name="photo_produit" value="{{ old('photo_produit') }}">
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

        <button style="font-weight:bold" type="submit" class="btn btn-primary">Créer le produit</button>
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
