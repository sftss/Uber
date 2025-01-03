@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container">
    <div class="card-header">S'inscrire</div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">

                    <div style="background-color: #f3f3f3; margin: 0 0;">
                        <a style="display:flex;transform: translate(140%, -100%)" id="creerCptChauffeur"
                            href="{{ route('register.formch') }}" class="btn btn-outline-light">S'inscrire en tant que
                            chauffeur 🚗</a>
                    </div>
                    <form id="formInscription" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row">
                            <div>
                                <div class="form-group">
                                    <label for="prenom_cp">Prénom</label>
                                    <input type="text" name="prenom_cp" value="{{ old('prenom_cp') }}" required>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="nom_cp">Nom</label>
                                    <input type="text" name="nom_cp" value="{{ old('nom_cp') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div>
                                <div class="form-group">
                                    <label for="mail_client">Email</label>
                                    <input type="email" name="mail_client" value="{{ old('mail_client') }}" required>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="mdp_client">Mot de passe</label>
                                    <input type="password" name="mdp_client" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div>
                                <div class="form-group">
                                    <label for="mdp_client_confirmation">Confirmer le mot de passe</label>
                                    <input type="password" name="mdp_client_confirmation" required>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="tel_client">Téléphone</label>
                                    <input type="text" name="tel_client" value="{{ old('tel_client') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div>
                                <div class="form-group">
                                    <label for="est_particulier">Type de client</label>
                                    <select name="est_particulier" id="est_particulier" required
                                        onchange="toggleProfessionnelFields()">
                                        <option value="1" {{ old('est_particulier') == '1' ? 'selected' : '' }}>
                                            Particulier</option>
                                        <option value="0" {{ old('est_particulier') == '0' ? 'selected' : '' }}>
                                            Professionnel</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="professionnel_fields" style="display: none;">
                            <div class="row">
                                <div id="num_siret_field">
                                    <div class="form-group">
                                        <label for="num_siret">Numéro SIRET</label>
                                        <input type="text" name="num_siret" id="num_siret"
                                            value="{{ old('num_siret') }}">
                                    </div>
                                </div>
                                <div>
                                    <div class="form-group">
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

                        <div class="row">
                            <div>
                                <div class="form-group">
                                    <label for="sexe_cp">Sexe</label>
                                    <select name="sexe_cp" id="sexe_cp" required>
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
                                    <label for="date_naissance_cp">Date de naissance</label>
                                    <input type="date" name="date_naissance_cp"
                                        value="{{ old('date_naissance_cp') }}">
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
                                    J'ai lu et j'accepte la <a class="txtPolConf"
                                        href="{{ route('politique') }}">politique de confidentialité (voir ici)</a>
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
