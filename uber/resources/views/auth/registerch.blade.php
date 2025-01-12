@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container">
    <div class="card-header">S'inscrire en tant que chauffeur</div>
    <div class="row" style="display:inherit">
        <div class="col-md-8">
            <div class="card nimp">
                <div class="card-body">
                    <form id="formInscription" method="POST" action="{{ route('registerch') }}">
                        @csrf
                        <div>
                            <div class="form-group">
                                <label for="prenom_chauffeur">Prénom</label>
                                <input type="text" name="prenom_chauffeur" value="{{ old('prenom_chauffeur') }}"
                                    placeholder="Entrer votre prénom" required>
                                @error('prenom_chauffeur')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="nom_chauffeur">Nom</label>
                                <input placeholder="Entrer votre nom" type="text" name="nom_chauffeur"
                                    value="{{ old('nom_chauffeur') }}" required>
                                @error('nom_chauffeur')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="mail_chauffeur">Email</label>
                                <input type="email" name="mail_chauffeur" value="{{ old('mail_chauffeur') }}"
                                    placeholder="Entrer votre email" required>
                                @error('mail_chauffeur')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="mdp_chauffeur">Mot de passe</label>
                                <input type="password" placeholder="Entrer votre mot de passe" name="mdp_chauffeur"
                                    required>
                                @error('mdp_chauffeur')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label for="mdp_chauffeur_confirmation">Confirmer le mot de passe</label>
                                <input type="password" placeholder="Confirmer votre mot de passe"
                                    name="mdp_chauffeur_confirmation" required>
                                @error('mdp_chauffeur_confirmation')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="tel_chauffeur">Téléphone</label>
                                <input placeholder="Entrer votre numéro de téléphone" type="text"
                                    name="tel_chauffeur" value="{{ old('tel_chauffeur') }}">
                                @error('tel_chauffeur')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label for="rue">Rue</label>
                                <input placeholder="Entrer le numéro de votre rue" type="text" name="rue"
                                    required value="{{ old('rue') }}">
                                @error('rue')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="ville">Ville</label>
                                <input placeholder="Entrer votre ville" type="text" name="ville"
                                    value="{{ old('ville') }}">
                                @error('ville')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="cp">Code Postal</label>
                                <input placeholder="Entrer votre code postal" type="text" name="cp"
                                    value="{{ old('cp') }}">
                                @error('cp')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div id="professionnel_fields">
                            <div id="num_siret_field">
                                <div class="form-group">
                                    <label for="num_siret">Numéro SIRET</label>
                                    <input placeholder="Entrer votre numéro de SIRET" type="text" name="num_siret"
                                        id="num_siret" value="{{ old('num_siret') }}">
                                    @error('num_siret')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
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
                                    @error('secteur_activite')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <div class="form-group">
                                        <label for="rib">Relevé d'Identité Bancaire (RIB)</label>
                                        <input placeholder="Entrer votre RIB" type="text" name="rib"
                                            id="rib" value="{{ old('rib') }}" required>
                                    </div>
                                    @error('rib')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="type_service">Type de service</label>
                                    <select name="type_service" id="type_service" required>
                                        <option value="" disabled selected>Choisir...</option>
                                        <option value="VTC" {{ old('type_service') == 'VTC' ? 'selected' : '' }}>
                                            VTC
                                        </option>
                                        <option value="Livraison"
                                            {{ old('type_service') == 'Livraison' ? 'selected' : '' }}>Livraison
                                        </option>
                                    </select>
                                    @error('type_service')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="sexe_chauffeur">Sexe</label>
                                    <select name="sexe_chauffeur" id="sexe_chauffeur" required>
                                        <option value="" disabled selected>Choisir...</option>
                                        <option value="H" {{ old('sexe_chauffeur') == 'H' ? 'selected' : '' }}>
                                            Homme
                                        </option>
                                        <option value="F" {{ old('sexe_chauffeur') == 'F' ? 'selected' : '' }}>
                                            Femme
                                        </option>
                                        <option value="A" {{ old('sexe_chauffeur') == 'A' ? 'selected' : '' }}>
                                            Autre
                                        </option>
                                    </select>
                                    @error('sexe_chauffeur')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="date_naissance_chauffeur">Date de naissance</label>
                                    <input type="date" name="date_naissance_chauffeur"
                                        value="{{ old('date_naissance_chauffeur') }}">
                                    @error('date_naissance_chauffeur')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 form-group d-flex align-items-center ButFormInsciption">
                                <input type="checkbox" name="newsletter" id="newsletter"
                                    {{ old('newsletter') ? 'checked' : '' }}>
                                <label for="newsletter" class="ml-2">
                                    Recevoir la newsletter
                                </label>
                            </div>
                            <div class="col-12 form-group d-flex align-items-center ButFormInsciption">
                                <input type="checkbox" name="politique_confidentialite"
                                    id="politique_confidentialite" required>
                                <label for="politique_confidentialite" class="ml-2">
                                    J'ai lu et j'accepte la politique de confidentialité <a class="txtPolConf"
                                        href="{{ route('politique') }}"> (voir ici)</a>
                                </label>
                            </div>
                            <div style="display: flex; justify-content: center;">
                                <button class="btn-submit" type="submit">S'inscrire</button>
                            </div>
                    </form>
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
    document.addEventListener('DOMContentLoaded', toggleProfessionnelFields);

    document.addEventListener("DOMContentLoaded", function() {
        const alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(() => {
                alert.classList.add('hide');
            }, 5000);
        }
    });
</script>
