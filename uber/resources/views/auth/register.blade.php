@extends('layouts.header')

<div class="container">
    <div class="card-header">Register</div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Première ligne avec deux champs côte à côte -->
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

                        <!-- Deuxième ligne avec deux champs côte à côte -->
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

                        <!-- Troisième ligne avec deux champs côte à côte -->
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

                        <!-- Autres champs -->
                        <div class="row">
                            <div>
                                <div class="form-group">
                                    <label for="num_siret">Numéro SIRET</label>
                                    <input type="text" name="num_siret" value="{{ old('num_siret') }}">
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="sexe_cp">Sexe</label>
                                    <input type="text" name="sexe_cp" value="{{ old('sexe_cp') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div>
                                <div class="form-group">
                                    <label for="date_naissance_cp">Date de naissance</label>
                                    <input type="date" name="date_naissance_cp"
                                        value="{{ old('date_naissance_cp') }}">
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label for="est_particulier">Particulier</label>
                                    <input type="checkbox" name="est_particulier"
                                        {{ old('est_particulier') ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div>
                                <div class="form-group">
                                    <label for="newsletter">Abonnement à la newsletter</label>
                                    <input type="checkbox" name="newsletter" {{ old('newsletter') ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <input type="checkbox" name="politique_confidentialite"
                                        id="politique_confidentialite" required>
                                    <label for="politique_confidentialite">
                                        J'ai lu et j'accepte la <a href="{{ route('politique') }}"
                                            target="_blank">politique de confidentialité</a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit">S'inscrire</button>
                    </form>

                    @if ($errors->any())
                        <div class="alert alert-danger">
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
