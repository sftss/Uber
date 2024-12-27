<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}

</head>

<body>
    @php
        $role = auth()->check() ? auth()->user()->role->lib_role : null;
    @endphp

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
    <div id="app">

        <main class="py-4">
            @yield('content')
        </main>

    </div>
</body>

</html>
