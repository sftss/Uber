<link href="{{ URL::asset('assets/style/app.css') }}" rel="stylesheet">
@auth
    @php
        $role = auth()->user()->role->lib_role ?? 'guest';
    @endphp
@else
    @php
        $role = 'guest';
    @endphp
@endauth

@if ($role === 'Client particulier')
    @include('layouts.client')
@elseif ($role === 'Client professionnel')
    @include('layouts.professionnel-header') {{-- c le responsable d'enseigne --}}
@elseif ($role === 'Chauffeur')
    @include('layouts.chauffeur-header')
@elseif ($role === 'Livreur')
    @include('layouts.livreur-header')
@elseif ($role === 'Logistique')
    @include('layouts.logistique-header')
@elseif ($role === 'Facturation')
    @include('layouts.facturation-header')
@elseif ($role === 'RH')
    @include('layouts.rh-header')
@elseif ($role === 'Juridique')
    @include('layouts.juridique-header')
@elseif ($role === 'Course')
    @include('layouts.service-course-header')
@else
    @include('layouts.header') {{-- header défaut --}}
@endif

<div class="container mt-5">
    <h1 style="margin-top: 5%; display: flex; flex-direction: column; align-items: center;">
        Connexion Services</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Entrez votre email" value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Entrez votre mot de passe" required>
                        </div>

                        <div class="form-group">
                            <label for="role">Rôle</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="" disabled selected>Choisir un rôle</option>
                                <option value="logistique">Logistique</option>
                                <option value="facturation">Facturation</option>
                                <option value="administratif">Administratif</option>
                                <option value="rh">RH</option>
                                <option value="support">Support</option>
                            </select>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Connexion</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
