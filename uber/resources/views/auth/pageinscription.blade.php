@extends('layouts.header')
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" />

<div class="container">
    <div style="margin: 7% 0 7% 0;">
        <h1>Choisissez votre type d'inscription</h1>

        <div>
            <a href="{{ route('register.form') }}">
                <div>
                    <h2>inscription client <svg style="margin-left:5%;" fill="none" height="32" viewBox="0 0 24 24"
                            width="32">
                            <path d="m22.2 12-6.5 9h-3.5l5.5-7.5H2v-3h15.7L12.2 3h3.5l6.5 9Z" fill="currentColor">
                            </path>
                        </svg>
                    </h2>
                </div>
            </a>
        </div>

        <div>
            <a href="{{ route('register.formch') }}">
                <div>
                    <h2>inscription Chauffeur <svg style="margin-left:5%;" fill="none" height="32"
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
<script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
<df-messenger style="z-index: 999;" intent="WELCOME" chat-title="Uber Bot"
    agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
