<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Script Cookie-Script (gestion des cookies) -->
    <script type="text/javascript" charset="UTF-8" src="//cdn.cookie-script.com/s/740c938fe35e9b4ecefe1e3459a48f4b.js"></script>

    <!-- Google Analytics (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-KK9GZC2LN3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-KK9GZC2LN3');
        gtag('consent', 'default', {
            'ad_storage': 'denied',
            'ad_user_data': 'denied',
            'ad_personalization': 'denied',
            'analytics_storage': 'denied',
            'regions': ['FR', 'DE', 'IT', 'ES', 'PT', 'NL', 'BE', 'LU', 'CH', 'AT', 'SE', 'NO', 'DK', 'FI', 'PL', 'CZ', 'SK', 'HU', 'RO', 'BG', 'GR', 'IE', 'GB', 'EE', 'LV', 'LT', 'MT', 'CY', 'HR', 'SI', 'IS', 'LI']
        });
    </script>

    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-PT3GRQ9T');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="icon" href="{{ URL::asset('assets/img/Uber-logo.webp') }}" type="image/svg+xml">
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
</head>


<body>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PT3GRQ9T"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
{{-- 
@php
        $role = auth()->check() ? auth()->user()->role->lib_role : null;
    @endphp

    @if ($role === 'Client particulier')
        @include('layouts.client')
    @elseif ($role === 'Client professionnel')
        @include('layouts.professionnel-header') 
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
        @include('layouts.header')
    @endif
    --}}
    <div id="app">

        <main class="py-4">
            @yield('content')
        </main>

    </div>
</body>

</html>
