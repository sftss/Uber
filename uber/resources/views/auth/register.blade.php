@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container">
    <div class="card-header">S'inscrire</div>
    <div class="row"style="display:inherit">
        <div class="col-md-8">
            <div class="card nimp">
                <div class="card-body">
                    <form id="formInscription" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div>
                            <div class="form-group">
                                <label for="prenom_cp">Prénom</label>
                                <input type="text" name="prenom_cp" value="{{ old('prenom_cp') }}"
                                    placeholder="Entrer votre prénom" required>
                                @error('prenom_cp')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="nom_cp">Nom</label>
                                <input type="text" name="nom_cp" value="{{ old('nom_cp') }}"
                                    placeholder="Entrer votre nom" required>
                                @error('nom_cp')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label for="mail_client">Email</label>
                                <input type="email" name="mail_client" value="{{ old('mail_client') }}"
                                    placeholder="Entrer votre email" required>
                                @error('mail_client')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <div style="display: flex;align-items:baseline;gap:5px">
                                    <label for="mdp_client">Mot de passe</label>
                                    <div class="info-bubble" onclick="toggleInfo(this)">
                                        ?
                                        <div class="info-content">
                                            Le mot de passe doit contenir au moins 8 caractères.
                                        </div>
                                    </div>
                                </div>
                                <input type="password" name="mdp_client" placeholder="Entrer votre mot de passe"
                                    required>
                                @error('mdp_client')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <div class="form-group">
                                <label for="mdp_client_confirmation">Confirmer le mot de passe</label>
                                <input type="password" name="mdp_client_confirmation"
                                    placeholder="Confirmer votre mot de passe" required>
                                @error('mdp_client_confirmation')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <div style="display: flex;align-items:baseline;gap:5px">
                                    <label for="tel_client">Téléphone</label>
                                    <div class="info-bubble" onclick="toggleInfo(this)">
                                        ?
                                        <div class="info-content">
                                            Le téléphone doit commencer par 06 ou 07 et doit comporter 10 caractères.
                                        </div>
                                    </div>
                                </div>
                                <input type="text" name="tel_client" value="{{ old('tel_client') }}"
                                    placeholder="Entrer votre numéro de téléphone">
                                @error('tel_client')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <div style="display: flex;align-items:baseline;gap:5px">
                                    <label for="est_particulier">Type de client</label>
                                    <div class="info-bubble" onclick="toggleInfo(this)">
                                        ?
                                        <div class="info-content">
                                            Si vous souhaitez créer votre établissement au sein de notre service,
                                            veuillez
                                            sélectionner 'Professionnel'.
                                        </div>
                                    </div>
                                </div>
                                <select name="est_particulier" id="est_particulier" required
                                    onchange="toggleProfessionnelFields()">
                                    <option value="1" {{ old('est_particulier') == '1' ? 'selected' : '' }}>
                                        Particulier</option>
                                    <option value="0" {{ old('est_particulier') == '0' ? 'selected' : '' }}>
                                        Professionnel</option>
                                </select>

                                @error('est_particulier')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div id="professionnel_fields" style="display: none;">
                            <div id="num_siret_field">
                                <div class="form-group">
                                    <div style="display: flex;align-items:baseline;gap:5px">
                                        <label for="num_siret">Numéro SIRET</label>
                                        <div class="info-bubble" onclick="toggleInfo(this)">
                                            ?
                                            <div class="info-content">
                                                Le numéro de SIRET doit comporter 14 chiffres. Il est constitué du
                                                numéro
                                                SIRET (9 chiffres) suivi du code NIC (5 chiffres) pour identifier
                                                précisément l'établissement.
                                            </div>
                                        </div>
                                    </div>
                                    <input type="text" name="num_siret" id="num_siret"
                                        value="{{ old('num_siret') }}">
                                    @error('num_siret')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
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
                                    @error('secteur_activite')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

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
                                @error('sexe_cp')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="date_naissance_cp">Date de naissance</label>
                                <input type="date" name="date_naissance_cp"
                                    value="{{ old('date_naissance_cp') }}">
                                @error('date_naissance_cp')
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
                            <input type="checkbox" name="politique_confidentialite" id="politique_confidentialite"
                                required>
                            <label for="politique_confidentialite">
                                J'ai lu et j'accepte la <a class="txtPolConf"
                                    href="{{ route('politique') }}">politique
                                    de confidentialité (voir ici)</a>
                            </label>
                        </div>
                        <div style="display: flex; justify-content: center;">
                            <button class="plsGrandBut btn-submit " type="submit">S'inscrire</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(() => {
                alert.classList.add('hide');
            }, 5000);
        }
    });

    function toggleProfessionnelFields() {
        const estParticulier = document.getElementById('est_particulier');
        const professionnelFields = document.getElementById('professionnel_fields');

        if (estParticulier.value === '0') {
            professionnelFields.style.display = 'block';
        } else {
            professionnelFields.style.display = 'none';
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        toggleProfessionnelFields();
    });
</script>
<script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
<df-messenger style="z-index: 999;" chat-title="Chatbot" chat-icon="{{ asset('assets/img/chat.png') }}"
    agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
