@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Ajouter un Restaurant</h2>
        
        <form action="{{ route('restaurant.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="NOM_ETABLISSEMENT">Nom de l'établissement :</label>
                <input type="text" class="form-control" id="NOM_ETABLISSEMENT" name="NOM_ETABLISSEMENT" required>
            </div>
            
            <div class="form-group">
                <label for="ID_ADRESSE">Adresse (ID) :</label>
                <input type="number" class="form-control" id="ID_ADRESSE" name="ID_ADRESSE" required>
            </div>
            
            <div class="form-group">
                <label for="HORAIRES_OUVERTURE">Horaires d'ouverture :</label>
                <input type="time" class="form-control" id="HORAIRES_OUVERTURE" name="HORAIRES_OUVERTURE">
            </div>
            
            <div class="form-group">
                <label for="HORAIRES_FERMETURE">Horaires de fermeture :</label>
                <input type="time" class="form-control" id="HORAIRES_FERMETURE" name="HORAIRES_FERMETURE">
            </div>
            
            <div class="form-group">
                <label for="DESCRIPTION_ETABLISSEMENT">Description :</label>
                <textarea class="form-control" id="DESCRIPTION_ETABLISSEMENT" name="DESCRIPTION_ETABLISSEMENT" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label for="PROPOSE_LIVRAISON">Proposez-vous la livraison ?</label>
                <select class="form-control" id="PROPOSE_LIVRAISON" name="PROPOSE_LIVRAISON">
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="PROPOSE_RETRAIT">Proposez-vous le retrait ?</label>
                <select class="form-control" id="PROPOSE_RETRAIT" name="PROPOSE_RETRAIT">
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="PHOTO_RESTAURANT">Photo du restaurant :</label>
                <input type="file" class="form-control" id="PHOTO_RESTAURANT" name="PHOTO_RESTAURANT">
            </div>
            
            <div class="form-group">
                <label for="ID_PROPRIETAIRE">ID Propriétaire :</label>
                <input type="number" class="form-control" id="ID_PROPRIETAIRE" name="ID_PROPRIETAIRE">
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
