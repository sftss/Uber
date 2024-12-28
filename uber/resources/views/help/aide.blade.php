@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">
<link href="{{ asset('assets/style/aide.css') }}" rel="stylesheet">
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">

@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">
<link href="{{ asset('assets/style/aide.css') }}" rel="stylesheet">
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">

<div class="container">
    <header class="help-header">
        <h1>Guide d'Utilisation - Uber</h1>
    </header>

    <div class="help-section">
        <h2>1. Les étapes préliminaires</h2>
        <ul>
            <li><i class="icon-user-plus"></i> Créez un compte : cliquez sur "S'inscrire", remplissez le formulaire et validez via email.</li>
            <li><i class="icon-login"></i> Connectez-vous : utilisez vos identifiants pour accéder à votre compte.</li>
        </ul>
    </div>

    <div class="help-section">
        <h2>2. Utilisation des services</h2>
        <h3><i class="icon-utensils"></i> Uber Eats</h3>
        <ul>
            <li>Recherchez des restaurants via la barre de recherche.</li>
            <li>Passez une commande et choisissez un mode de paiement.</li>
        </ul>

        <h3><i class="icon-truck"></i> Uber Course</h3>
        <ul>
            <li>Créez une course en fournissant les détails nécessaires.</li>
            <li>Suivez la livraison en direct.</li>
        </ul>

        <h3><i class="icon-bicycle"></i> Uber Vélos</h3>
        <ul>
            <li>Réservez un vélo à partir de la carte interactive.</li>
            <li>Utilisez un code de déverrouillage pour retirer le vélo.</li>
        </ul>
    </div>

    <div class="help-section">
        <h2>3. Gestion des paiements et commandes</h2>
        <ul>
            <li><i class="icon-credit-card"></i> Ajoutez un mode de paiement depuis votre profil.</li>
            <li><i class="icon-history"></i> Consultez l'historique de vos commandes.</li>
        </ul>
    </div>

    <div class="help-section">
        <h2>4. Contact avec le support</h2>
        <ul>
            <li><i class="icon-phone"></i> Contactez le support via le formulaire ou le chat en direct.</li>
            <li><i class="icon-question-circle"></i> Consultez les FAQ pour des réponses rapides.</li>
        </ul>
    </div>

    <div class="help-section">
        <h2>5. Utilisation de l'application Uber</h2>
        <ul>
            <li><i class="icon-mobile-alt"></i> Téléchargez l'application depuis l'App Store ou Google Play.</li>
            <li><i class="icon-map-marker-alt"></i> Entrez une destination et suivez votre chauffeur en direct.</li>
        </ul>
    </div>

    <footer class="help-footer">
        <p>© 2024 Uber | Assistance 24/7 disponible</p>
    </footer>
</div>