@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">
<link href="{{ asset('assets/style/aide.css') }}" rel="stylesheet">
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">

<div class="container1">
    <header class="help-header">
        <h1>Guide d'Utilisation - Uber</h1>
        <p>Bienvenue sur votre guide d'utilisation Uber ! En toute simplicité, apprenez à vous inscrire, réserver une
            course, passer commande ou utiliser nos services efficacement.</p>
    </header>

    <div class="help-section">
        <h2>1. Les étapes préliminaires</h2>
        <ul>
            <li><i class="icon-user-plus"></i>Créez un compte : cliquez sur "S'inscrire", remplissez le formulaire et
                validez via email.</li>
            <li><i class="icon-login"></i> Connectez-vous : utilisez vos identifiants pour accéder à votre compte.</li>
        </ul>
        <div class="help-illustration">
            <img src="{{ asset('assets/img/aideConnection.webp') }}" alt="Ajout de la photo" class="help-image">
        </div>
    </div>

    <div class="help-section">
        <h2>2. Utilisation des services</h2>
        <h3><i class="icon-utensils"></i> Uber Eats</h3>
        <ul class="guide-list">
            <li class="guide-item">
                <strong>Recherche de restaurants :</strong>
                <p>Recherchez des restaurants via la barre de recherche.</p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideRechercher.webp') }}" alt="Exemple de recherche de restaurant"
                        class="help-image">
                </div>
            </li>

            <li class="guide-item">
                <strong>Filtrage des résultats :</strong>
                <p>Filtrez vos recherches en fonction de vos préférences (type de cuisine, prix, note, etc.).</p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/images/ubereats_filters.png') }}" alt="Filtrage des restaurants"
                        class="help-image">
                </div>
            </li>

            <li class="guide-item">
                <strong>Passer une commande :</strong>
                <p>Passez une commande en sélectionnant vos plats et choisissez un mode de paiement adapté.</p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aidePasserCommande.webp') }}" alt="Passage de commande Uber Eats"
                        class="help-image">
                </div>
            </li>
        </ul>

        <h3><i class="icon-truck"></i> Uber Course</h3>
        <ul class="guide-list">
            <li class="guide-item">
                <strong>Saisie du point de départ et du point d'arrivée :</strong>
                <p>Indiquez votre adresse de départ et votre destination. Vous pouvez également ajuster l'horaire selon
                    vos préférences.</p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideCourse.webp') }}" alt="Saisie du point de départ et de l'arrivée"
                        class="help-image">
                </div>
            </li>

            <li class="guide-item">
                <strong>Choix des prestations disponibles :</strong>
                <p>En fonction des informations saisies, vous verrez tous les types de prestations disponibles (course
                    standard, premium, etc.) avec les détails sur chaque option.</p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideDetailCourse.webp') }}" alt="Choix des prestations disponibles"
                        class="help-image">
                </div>
            </li>

            <li class="guide-item">
                <strong>Récapitulatif de la réservation :</strong>
                <p>Un récapitulatif de votre réservation apparaîtra, avec toutes les informations liées à votre course.
                    Vous pourrez ensuite valider ou annuler la réservation selon votre choix.</p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideDetailCourse.webp') }}" alt="Récapitulatif de la réservation"
                        class="help-image">
                </div>
            </li>

            <li class="guide-item">
                <strong>Fin de course et validation :</strong>
                <p>Une fois la course terminée, un petit bandeau s'affichera pour vous permettre de valider la fin de la
                    course.</p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideTerminerCourse.webp') }}" alt="Validation de la fin de course"
                        class="help-image">
                </div>
            </li>

            <li class="guide-item">
                <strong>Page de facture et évaluation :</strong>
                <p>Vous aurez accès à une page de facture détaillée, où vous pourrez évaluer la course en donnant une
                    note et un pourboire. Une facture vous sera également envoyée pour mieux comprendre la prestation.
                </p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideFactureCourse.webp') }}" alt="Page de facture"
                        class="help-image">
                </div>
            </li>
        </ul>

        <h3><i class="icon-bicycle"></i> Uber Vélos</h3>
        <ul class="guide-list">
            <li class="guide-item">
                <strong>Réservation de vélo :</strong>
                <p>Réserver un vélo à partir de la carte interactive. Pour cela, vous utilisez un code de déverrouillage
                    pour retirer le vélo.</p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/images/ride_start.png') }}" alt="Réservation de vélo"
                        class="help-image">
                </div>
            </li>
        </ul>
    </div>

    <div class="help-section">
        <h2>3. Gestion des paiements et commandes</h2>
        <ul class="guide-list">
            <li class="guide-item">
                <i class="icon-credit-card"></i> Ajoutez un mode de paiement depuis votre profil.
                <div class="help-illustration">
                    <img src="{{ asset('assets/images/payment.png') }}" alt="Exemple de gestion des paiements"
                        class="help-image">
                </div>
            </li>
            <li class="guide-item">
                <i class="icon-history"></i> Consultez l'historique de vos commandes.
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideVoirCommande.webp') }}" alt="Exemple de gestion des paiements"
                        class="help-image">
                </div>
            </li>
        </ul>
    </div>

    <div class="help-section">
        <h2>4. Contact avec le support</h2>
        <ul class="guide-list">
            <li class="guide-item">
                <i class="icon-phone"></i> Contactez le support via le formulaire ou le chat en direct.
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideVoirAide.webp') }}" alt="Exemple de contact avec le support"
                        class="help-image">
                </div>
            </li>
            <li class="guide-item">
                <i class="icon-question-circle"></i> Consultez les FAQ pour des réponses rapides.
                <div class="help-illustration">
                    <img src="{{ asset('assets/images/support.png') }}" alt="Exemple de contact avec le support"
                        class="help-image">
                </div>
            </li>
        </ul>
    </div>
    <p style="text-align: center;font-weight: bold;margin-top: 2%;">Ce site utilise des cookies afin d'améliorer
        l'expérience
        utilisateur. <a class="txtPolConf" href="{{ route('politique') }}">En savoir plus ici.
    </p>
</div>
