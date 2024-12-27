@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container">
    <div class="justify-content-center row mt-5">
        <h1 class="text-center mb-4">Choisissez votre type de connexion</h1>

        <div class="col-12 col-sm-6 interface ">
            <a href="{{ route('login') }}" class="text-decoration-none">
                <div class="bloc-interface">
                    <h2>Connexion Client</h2>
                    <svg style="margin-left:5%;" fill="none" height="32" viewBox="0 0 24 24" width="32">
                        <path d="m22.2 12-6.5 9h-3.5l5.5-7.5H2v-3h15.7L12.2 3h3.5l6.5 9Z" fill="currentColor"></path>
                    </svg>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 interface">
            <a href="{{ route('loginch') }}" class="text-decoration-none">
                <div class="bloc-interface">
                    <h2>Connexion Chauffeur</h2>
                    <svg style="margin-left:5%;" fill="none" height="32" viewBox="0 0 24 24" width="32">
                        <path d="m22.2 12-6.5 9h-3.5l5.5-7.5H2v-3h15.7L12.2 3h3.5l6.5 9Z" fill="currentColor"></path>
                    </svg>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 interface mt-4">
            <a href="{{ route('loginService') }}" class="text-decoration-none">
                <div class="bloc-interface">
                    <h2>Connexion Services Internes</h2>
                    <svg style="margin: 0 0 3% 5%;" fill="none" height="32" viewBox="0 0 24 24" width="32">
                        <path d="m22.2 12-6.5 9h-3.5l5.5-7.5H2v-3h15.7L12.2 3h3.5l6.5 9Z" fill="currentColor"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>
</div>
