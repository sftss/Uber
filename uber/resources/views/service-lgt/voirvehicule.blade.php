@extends('layouts.servicelogistique-header')

<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/style/servicecourse.css') }}" />


<body>
    <div class="container" style="margin: 3% 3%">
        @if (isset($vehicules) )
            @foreach ($vehicules as $vehicule)
                <div class="course_container">
                    <h3 class="course_title">Vehicule : {{ $vehicule->marque }}</h3>
                    <ul class="course_details">
                        <li class="date_naissance">Catégorie : {{ $vehicule->categorieVehicule->lib_categorie_vehicule }}</li>
                        <li class="sexe">Couleur : {{ $vehicule->couleur->lib_couleur }}</li>
                        <li class="sexe">Immatriculation : {{ $vehicule->immatriculation }}</li>
                        @if ($vehicule->valider != true)
                            <form id="formButAccept"  
                                    action="{{ route('changer-statuts-vehicule', ['vehicule_id' => $vehicule->id_vehicule]) }}" 
                                    method="POST">
                                    @csrf
                                    <button type="submit" name="statut" value="accepter" class="btn btn-success">Accepter</button>
                                    <button type="submit" name="statut" value="refuser" class="btn btn-danger">Refuser</button>
                                </form>
                        
                        @else
                        
                        <form action="{{ route('ajouter-amenagement', ['vehicule_id' => $vehicule->id_vehicule]) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="amenagement">Choisir un aménagement :</label>
                                <select name="amenagement" id="amenagement" class="form-control">
                                    <option value="" disabled selected>-- Sélectionnez un aménagement --</option>
                                    <!-- Liste d'aménagements en dur -->
                                    <option value="1">Sièges chauffants</option>
                                    <option value="2">Toit panoramique</option>
                                    <option value="3">Caméra de recul</option>
                                    <option value="4">Système de navigation</option>
                                    <option value="5">Pack audio haut de gamme</option>
                                    <option value="6">Phares LED</option>
                                    <option value="7">Jantes en alliage</option>
                                    <option value="8">Sièges en cuir</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Ajouter l'aménagement</button>
                        </form>
                        @endif

                        <!-- Affichage conditionnel de la date RH et calendrier si la date est nulle -->
                    </ul>
                </div>
            @endforeach
        @else
            <div class="no_courses_message">
                <p>Il n'y a aucun véhicule valider.</p>
            </div>
        @endif
    </div>
</body>


