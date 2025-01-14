@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">

<div class="container">

    <div href="{{ route('restaurants.filter') }}" class="card">
        <img src="{{ asset('assets/img/course.webp') }}" loading="lazy" alt="Deux-roues" class="icon" />

        <a href="{{ route('restaurants.filter') }}">Nos Restaurants</a>
    </div>

    <div href="{{ route('restaurants.filter') }}" class="card">
        <img src="{{ asset('assets/img/course.webp') }}" loading="lazy" alt="Deux-roues" class="icon" />

        <a href="{{ route('lieux.search') }}">Nos magasins</a>
    </div>
</div>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger style="z-index: 999;"  chat-title="Chatbot" chat-icon="{{ asset('assets/img/chat.png') }}"
        agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>

<style>
    a {
        font-weight: bold;
    }

    .card:first-child {
        margin-top: 3%;
    }

    .card {
        max-width: 70%;
    }
</style>
