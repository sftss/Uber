@extends('layouts.header')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div>
                            <label for="prenom_cp">Prénom</label>
                            <input type="text" name="prenom_cp" value="{{ old('prenom_cp') }}" required>
                        </div>

                        <div>
                            <label for="nom_cp">Nom</label>
                            <input type="text" name="nom_cp" value="{{ old('nom_cp') }}" required>
                        </div>

                        <div>
                            <label for="mail_client">Email</label>
                            <input type="email" name="mail_client" value="{{ old('mail_client') }}" required>
                        </div>

                        <div>
                            <label for="mdp_client">Mot de passe</label>
                            <input type="password" name="mdp_client" required>
                        </div>

                        <div>
                            <label for="mdp_client_confirmation">Confirmer le mot de passe</label>
                            <input type="password" name="mdp_client_confirmation" required>
                        </div>

                        <div>
                            <label for="tel_client">Téléphone</label>
                            <input type="text" name="tel_client" value="{{ old('tel_client') }}">
                        </div>

                        <div>
                            <label for="num_siret">Numéro SIRET</label>
                            <input type="text" name="num_siret" value="{{ old('num_siret') }}">
                        </div>

                        <div>
                            <label for="sexe_cp">Sexe</label>
                            <input type="text" name="sexe_cp" value="{{ old('sexe_cp') }}">
                        </div>

                        <div>
                            <label for="date_naissance_cp">Date de naissance</label>
                            <input type="date" name="date_naissance_cp" value="{{ old('date_naissance_cp') }}">
                        </div>

                        <div>
                            <label for="est_particulier">Particulier</label>
                            <input type="checkbox" name="est_particulier" {{ old('est_particulier') ? 'checked' : '' }}>
                        </div>

                        <div>
                            <label for="newsletter">Abonnement à la newsletter</label>
                            <input type="checkbox" name="newsletter" {{ old('newsletter') ? 'checked' : '' }}>
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