@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">
<link href="{{ asset('assets/style/aide.css') }}" rel="stylesheet">
<link rel="icon" href="{{ URL::asset('assets/svg/uber-logo.svg') }}" type="image/svg+xml">

<div style="margin:5% auto;" class="container1">
    <header class="help-header">
        <h1>Guide d'Utilisation - Uber</h1>
        <p>Bienvenue sur votre guide d'utilisation Uber ! En toute simplicité, apprenez à vous inscrire, réserver une
            course, passer commande ou utiliser nos services efficacement.</p>
    </header>

    <div class="help-section">
        <h2>1. Les étapes préliminaires</h2>
        <ul>
            <li><i class="icon-user-plus"></i><strong>Créez un compte : </strong> Cliquez sur "S'inscrire", remplissez
                le
                formulaire et
                validez via email.</li>
            <li><i class="icon-login"></i><strong>Connectez-vous : </strong> Utilisez vos identifiants pour accéder à
                votre compte.</li>
        </ul>
        <div class="help-illustration">
            <img src="{{ asset('assets/img/aideConnection.webp') }}" alt="Ajout de la photo" class="help-image">
        </div>
        <p>Grâce à la barre de menu, explorez facilement tous les services qu'Uber met à votre disposition.</p>
        <div class="help-illustration">
            <img src="{{ asset('assets/img/aideVoirHeader.png') }}" alt="Ajout de la photo" class="help-image">
        </div>
    </div>


    <div class="help-section">
        <h2>2. Utilisation des services</h2>
        <h3><i class="icon-utensils"></i> Uber Eats</h3>
        <ul class="guide-list">
            <li class="guide-item">
                <strong>Recherche de restaurants : </strong>
                <p>Recherchez des restaurants via la barre de recherche.</p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideRechercher.png') }}" alt="Exemple de recherche de restaurant"
                        class="help-image">
                </div>
            </li>

            <li class="guide-item">
                <strong>Passer une commande : </strong>
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
        <!-- Partie : Saisie du point de départ et d'arrivée -->
        <strong>Saisie du point de départ et du point d'arrivée :</strong>
        <p>Indiquez votre adresse de départ et votre destination. Vous pouvez également ajuster l'horaire selon vos préférences.</p>
        <div class="help-illustration">
            <img src="{{ asset('assets/img/aideCourse.png') }}" alt="Saisie du point de départ et de l'arrivée avec toutes les infos nécessaires sur Teams" class="help-image">
        </div>
        </li>
        <li class="guide-item">
        <!-- Partie : Vos préférences -->
        <strong>Vos préférences, nos propositions :</strong>
        <p>Selon vos paramètres de course, découvrez toutes les options disponibles adaptées à vos besoins.</p>
        <div class="help-illustration">
            <img src="{{ asset('assets/img/aideVoirCourse.png') }}" alt="Les options disponibles" class="help-image">
        </div>
        </li>
        <li class="guide-item">
        <!-- Partie : Détail de la course -->
        <strong>Détail de la course :</strong>
        <p>Après avoir choisi votre type de prestation, vous aurez la possibilité de consulter une page détaillant tous les aspects de votre réservation, si vous le souhaitez.</p>
        <div class="help-illustration">
            <img src="{{ asset('assets/img/CourseNum.png') }}" alt="Détail de la course" class="help-image">
        </div>
    </li>
</ul>
<ul class="guide-list">

            <li class="guide-item">
                <strong>Choix des prestations disponibles : </strong>
                <p>En fonction des informations que vous avez saisies, découvrez toutes les prestations disponibles (standard, premium, etc.), accompagnées des détails pour chaque option. Grâce au bouton 'Mes courses', accédez facilement à toutes les options que vous avez sélectionnées</p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideDetailCourse.webp') }}" alt="Choix des prestations disponibles"
                        class="help-image">
                </div>
            </li>

            <li class="guide-item">
            <strong>Gérer votre Réservation : </strong>
                <p>Vous pourrez ensuite modifier ou annuler la réservation selon votre choix.</p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideCourseBouttonpng.png') }}" alt="Récapitulatif de la réservation"
                        class="help-image">
                </div>
            </li>

            <li class="guide-item">
                <strong>Fin de course et validation : </strong>
                <p>Une fois la course terminée, un petit bandeau s'affichera pour vous permettre de valider la fin de la
                    course.</p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideTerminerCourse.webp') }}" alt="Validation de la fin de course"
                        class="help-image">
                </div>
            </li>

            <li class="guide-item">
            <strong>Page de facture et évaluation : </strong>
                <p>Pour terminer, vous aurez l'occasion d'évaluer votre course en attribuant une note et, si vous le souhaitez, de laisser un pourboire.
                </p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideDonnerNoteCourse.webp') }}" alt="Page de facture"
                        class="help-image">
                </div>
                </li>

                <li class="guide-item">
                <strong>Génération de la Facture : </strong>
                <p> Ensuite, vous pourrez obtenir votre facture pour mieux comprendre les détails de la prestation en cliquant sur le bouton 'Générer ma facture', où toutes les informations relatives à votre course seront affichées. </p>
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideFactureCourse.webp') }}" alt="Page de facture"
                        class="help-image">
                </div>
            </li>
        </ul>
    </div>

    <div class="help-section">
        <h2>3. Gestion des paiements et commandes</h2>
        <ul class="guide-list">
            <li class="guide-item">
                <i class="icon-credit-card"></i><strong>Ajoutez un mode de paiement depuis votre profil : </strong>
                <div class="help-illustration">
                <img src="{{ asset('assets/img/aideAjouterCB.webp') }}" alt="Page de facture"
                class="help-image">
                </div>
            </li>
            <li class="guide-item">
                <i class="icon-history"></i><strong>Consultez l'historique de vos commandes : </strong>
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
                <i class="icon-phone"></i>Vous retrouverez toutes les aides possible sur cette page à l'aide de ce
                bouton :
                <div class="help-illustration">
                    <img src="{{ asset('assets/img/aideVoirAide.webp') }}" alt="Exemple de contact avec le support"
                        class="help-image">
                </div>
            </li>
            <li class="guide-item">
                <i class="icon-question-circle"></i>Vous pouvez aussi poser vos questions à l'assitant virtuel :
                <div class="help-illustration">
                    <img src="{{ asset('assets/images/support.png') }}" alt=""
                        class="help-image">
                </div>
            </li>
        </ul>
    </div>
    <p style="text-align: center; font-weight: bold; margin-top: 2%;">Ce site utilise des cookies afin d'améliorer
    l'expérience
    utilisateur. <a class="txtPolConf" href="{{ route('politique') }}" style="color: green;">En savoir plus ici.</a>
</p>

</div>
