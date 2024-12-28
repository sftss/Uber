@extends('layouts.professionnel-header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">

<div class="container">
    <h1 style="text-align: center; margin: 3% 0;">Créer votre propre restaurant dans Uber Eats 🍴</h1>
    <form id="formCreateProduit" action="{{ route('restaurants.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <h3>Adresse</h3>
        <hr>
        <div class="form-group">
            <label for="rue">Rue</label>
            <input type="text" id="rue" name="rue" class="form-control" placeholder="Entrez la rue"
                required>
        </div>
        <div class="form-group">
            <label for="cp">Code Postal</label>
            <input type="text" id="cp" name="cp" class="form-control" placeholder="Entrez le code postal"
                required>
        </div>
        <div class="form-group">
            <label for="ville">Ville</label>
            <input type="text" id="ville" name="ville" class="form-control" placeholder="Entrez la ville"
                required>
        </div>

        <h3>Informations du restaurant</h3>
        <hr>
        <div class="form-group">
            <label for="nom_etablissement">Nom du Restaurant</label>
            <input type="text" id="nom_etablissement" name="nom_etablissement" class="form-control"
                placeholder="Entrez le nom" maxlength="300" required>
        </div>
        <div class="form-group">
            <label for="description_etablissement">Description</label>
            <textarea style="font-family: Arial, sans-serif;" id="description_etablissement" name="description_etablissement"
                class="form-control" rows="3" placeholder="Entrez une description"></textarea>
        </div>
        <div class="form-group">
            <label>Options</label>
            <div>
                <input type="checkbox" id="propose_livraison" name="propose_livraison" value="1">
                <label for="propose_livraison">Livraison</label>
            </div>
            <div>
                <input type="checkbox" id="propose_retrait" name="propose_retrait" value="1">
                <label for="propose_retrait">À emporter</label>
            </div>
        </div>
        <div class="form-group">
            <label for="photo_restaurant">Photo</label>
            <input placeholder="Entrer une URL" type="text" id="photo_restaurant" name="photo_restaurant"
                class="form-control">
        </div>
        <div class="form-group">
            <label for="category">Catégorie</label>
            <select id="category" name="category" class="form-control" required>
                <option value="" disabled selected>Choisissez une catégorie</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id_categorie }}">
                        {{ $categorie->lib_categorie }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Horaires d'ouverture</label>
            <input type="time" id="horaires_ouverture" name="horaires_ouverture" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Horaires de fermeture</label>
            <input type="time" id="horaires_fermeture" name="horaires_fermeture" class="form-control" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Créer</button>
        </div>
    </form>
</div>
