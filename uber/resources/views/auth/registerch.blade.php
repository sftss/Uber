@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container">
    <div class="card-header">S'inscrire en tant que chauffeur</div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div style="margin-bottom: 0%;" class="card">
                <div class="card-body">
                    <form id="formInscription" method="POST" action="{{ route('registerch') }}">
                        @csrf

                        <!-- Première ligne avec deux champs côte à côte -->
                        <div class="row">
                            <div>
                                <div class="form-group">
                                    <label for="prenom_chauffeur">Prénom</label>
                                    <input type="text" name="prenom_chauffeur" value="{{ old('prenom_chauffeur') }}" required>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="nom_chauffeur">Nom</label>
                                    <input type="text" name="nom_chauffeur" value="{{ old('nom_chauffeur') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Deuxième ligne avec deux champs côte à côte -->
                        <div class="row">
                            <div>
                                <div class="form-group">
                                    <label for="mail_chauffeur">Email</label>
                                    <input type="email" name="mail_chauffeur" value="{{ old('mail_chauffeur') }}" required>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="mdp_chauffeur">Mot de passe</label>
                                    <input type="password" name="mdp_chauffeur" required>
                                </div>
                            </div>
                        </div>

                        <!-- Troisième ligne avec deux champs côte à côte -->
                        <div class="row">
                            <div>
                                <div class="form-group">
                                    <label for="mdp_chauffeur_confirmation">Confirmer le mot de passe</label>
                                    <input type="password" name="mdp_chauffeur_confirmation" required>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="tel_chauffeur">Téléphone</label>
                                    <input type="text" name="tel_chauffeur" value="{{ old('tel_chauffeur') }}">
                                </div>
                            </div>
                        </div>

         
                        <div class="row">
                            <div>
                                <div class="form-group">
                                    <label for="rue">Rue</label>
                                    <input type="text" name="rue" required>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="cp">Code Postal</label>
                                    <input type="text" name="cp" >
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="ville">Ville</label>
                                    <input type="text" name="ville">
                                </div>
                            </div>
                        </div>

                        <!-- Champs spécifiques pour les professionnels -->
                        <div id="professionnel_fields" >
                            <div class="row">
                                <div id="num_siret_field">
                                    <div class="form-group">
                                        <label for="num_siret">Numéro SIRET</label>
                                        <input type="text" name="num_siret" id="num_siret"
                                            value="{{ old('num_siret') }}">
                                    </div>
                                </div>
                                <div>
                                    <div class="form-group formInscriptionChauffeurInfo">
                                        <label for="secteur_activite">Secteur d'activité</label>
                                        <select name="secteur_activite" id="secteur_activite">
                                            <option value="" disabled selected>Choisir...</option>
                                            @foreach ($secteurs as $secteur)
                                                <option value="{{ $secteur->id_sd }}"
                                                    {{ old('secteur_activite') == $secteur->id_sd ? 'selected' : '' }}>
                                                    {{ $secteur->lib_sd }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sexe et date de naissance -->
                        <div class="row">
                            <div>
                                <div class="form-group">
                                    <label for="sexe_chauffeur">Sexe</label>
                                    <select name="sexe_chauffeur" id="sexe_cp" required>
                                        <option value="" disabled selected>Choisir...</option>
                                        <option value="H" {{ old('sexe_cp') == 'H' ? 'selected' : '' }}>Homme
                                        </option>
                                        <option value="F" {{ old('sexe_cp') == 'F' ? 'selected' : '' }}>Femme
                                        </option>
                                        <option value="A" {{ old('sexe_cp') == 'A' ? 'selected' : '' }}>Autre
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="date_naissance_chauffeur">Date de naissance</label>
                                    <input type="date" name="date_naissance_chauffeur"
                                        value="{{ old('date_naissance_chauffeur') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 form-group d-flex align-items-center ButFormInsciption">
                                <input type="checkbox" name="newsletter" id="newsletter"
                                    {{ old('newsletter') ? 'checked' : '' }}>
                                <label for="newsletter" class="ml-2">
                                    Recevoir la newsletter
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 form-group d-flex align-items-center ButFormInsciption">
                                <input type="checkbox" name="politique_confidentialite" id="politique_confidentialite"
                                    required>
                                <label for="politique_confidentialite" class="ml-2">
                                    J'ai lu et j'accepte la politique de confidentialité <a class="txtPolConf" href="{{ route('politique') }}"
                                        target="_blank"> (voir ici)</a>
                                </label>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: center;">
                            <button class="btn-submit" type="submit">S'inscrire</button>
                        </div>
                    </form>

                    @if ($errors->any())
                        <div class="alert alert-danger mechantAlert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</div>



<script>
    function toggleProfessionnelFields() {
        const clientType = document.getElementById('est_particulier').value;
        const professionnelFields = document.getElementById('professionnel_fields');
        professionnelFields.style.display = clientType === '0' ? 'block' : 'none';
    }

    // Appel initial pour gérer l'affichage correct au chargement
    document.addEventListener('DOMContentLoaded', toggleProfessionnelFields);
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Sélectionner l'alerte
        const alert = document.querySelector('.alert');
        if (alert) {
            // Attendre 5 secondes (5000 ms) avant de masquer l'alerte
            setTimeout(() => {
                alert.classList.add('hide');
            }, 5000);
        }
    });
</script>
