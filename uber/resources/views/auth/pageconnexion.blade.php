@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container">
    <div>
        <h1 style="margin: 2% 1%;font-size: 2.5rem;">Choisissez votre type de connexion</h1>

        <div>
            <a href="{{ route('login') }}" class="text-decoration-none">
                <div>
                    <h2>Connexion Client <svg style="margin-left:5%;" fill="none" height="32" viewBox="0 0 24 24"
                            width="32">
                            <path d="m22.2 12-6.5 9h-3.5l5.5-7.5H2v-3h15.7L12.2 3h3.5l6.5 9Z" fill="currentColor">
                            </path>
                        </svg>
                    </h2>
                </div>
            </a>
        </div>

        <div>
            <a href="{{ route('loginch') }}" class="text-decoration-none">
                <div>
                    <h2>Connexion Chauffeur <svg style="margin-left:5%;" fill="none" height="32"
                            viewBox="0 0 24 24" width="32">
                            <path d="m22.2 12-6.5 9h-3.5l5.5-7.5H2v-3h15.7L12.2 3h3.5l6.5 9Z" fill="currentColor">
                            </path>
                        </svg>
                    </h2>
                </div>
            </a>
        </div>

        <div>
            <a href="{{ route('loginService') }}" class="text-decoration-none">
                <div>
                    <h2>Connexion Services Internes <svg style="margin-left:5%;" fill="none" height="32"
                            viewBox="0 0 24 24" width="32">
                            <path d="m22.2 12-6.5 9h-3.5l5.5-7.5H2v-3h15.7L12.2 3h3.5l6.5 9Z" fill="currentColor">
                            </path>
                        </svg>
                    </h2>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
    h1 {
        margin: 2% 1%;
        font-size: 2.5rem;
    }

    h2 {
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    h2:hover {
        transform: translateX(3%);
    }
</style>
