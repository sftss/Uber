@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Ajouter un Restaurant</h2>

        <form action="{{ route('restaurant.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="nom_etablissement">Nom de l'établissement :</label>
                <input type="text" class="form-control" id="nom_etablissement" name="nom_etablissement" required>
            </div>

            <div class="form-group">
                <label for="id_adresse">Adresse (ID) :</label>
                <input type="number" class="form-control" id="id_adresse" name="id_adresse" required>
            </div>

            <div class="form-group">
                <label for="horaires_ouverture">Horaires d'ouverture :</label>
                <input type="time" class="form-control" id="horaires_ouverture" name="horaires_ouverture">
            </div>

            <div class="form-group">
                <label for="horaires_fermeture">Horaires de fermeture :</label>
                <input type="time" class="form-control" id="horaires_fermeture" name="horaires_fermeture">
            </div>

            <div class="form-group">
                <label for="description_etablissement">Description :</label>
                <textarea class="form-control" id="description_etablissement" name="description_etablissement" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="propose_livraison">Proposez-vous la livraison ?</label>
                <select class="form-control" id="propose_livraison" name="propose_livraison">
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </div>

            <div class="form-group">
                <label for="propose_retrait">Proposez-vous le retrait ?</label>
                <select class="form-control" id="propose_retrait" name="propose_retrait">
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </div>

            <div class="form-group">
                <label for="photo_restaurant">Photo du restaurant :</label>
                <input type="file" class="form-control" id="photo_restaurant" name="photo_restaurant">
            </div>

            <div class="form-group">
                <label for="id_proprietaire">ID Propriétaire :</label>
                <input type="number" class="form-control" id="id_proprietaire" name="id_proprietaire">
            </div>

            <div class="form-group">
                <label for="categorie">Catégorie :</label>
                <select class="form-control" id="categorie" name="categorie">
                    <option value="">Sélectionner une catégorie</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id_categorie }}">{{ $categorie->nom_categorie }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Ajouter le restaurant</button>
        </form>
    </div>
@endsection
