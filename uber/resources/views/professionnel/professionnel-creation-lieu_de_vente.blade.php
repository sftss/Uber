@extends('layouts.professionnel-header')
<link rel="icon" href="{{ URL::asset('assets/img/uber-logo.svg') }}" type="image/svg+xml">
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container">
    <h1 style="text-align: center; margin: 3% 0;">Créer votre lieu de vente 🏪</h1>
    <form id="formCreateProduit" action="{{ route('lieux.store') }}" method="POST" enctype="multipart/form-data">
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

        <h3>Informations de l'établissement</h3>
        <hr>
        <div class="form-group">
            <label for="nom_etablissement" class="form-label">Nom de l'établissement</label>
            <input type="text" name="nom_etablissement" id="nom_etablissement" class="form-control" maxlength="50">
        </div>

        <div class="form-group">
            <label for="description_etablissement" class="form-label">Description</label>
            <textarea style="font-family: Arial, sans-serif;" name="description_etablissement" id="description_etablissement"
                class="form-control" placeholder="Entrez le nom" maxlength="300" required></textarea>
        </div>

        <div class="form-group">
            <label for="horaires_ouverture" class="form-label">Horaires d'ouverture</label>
            <input type="time" name="horaires_ouverture" id="horaires_ouverture" class="form-control">
        </div>

        <div class="form-group">
            <label for="horaires_fermeture" class="form-label">Horaires de fermeture</label>
            <input type="time" name="horaires_fermeture" id="horaires_fermeture" class="form-control">
        </div>

        <div class="form-group">
            <label for="propose_livraison" class="form-label">Propose livraison</label>
            <select name="propose_livraison" id="propose_livraison" class="form-select">
                <option value="1">Oui</option>
                <option value="0">Non</option>
            </select>
        </div>

        <div class="form-group">
            <label for="photo_lieu">Photo</label>
            <input placeholder="Entrer une URL" type="text" id="photo_lieu" name="photo_lieu" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
