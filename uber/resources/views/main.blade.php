<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@extends('layouts.header')

<section class="suggestions">
    <h1>Allez où vous voulez avec Uber</h1>
    <p class="subtitle">Suggestions</p>

    <div class="card">
        <img src="{{ asset('assets/img/voiture.webp') }}" loading="lazy" alt="Course" class="icon" />
        <div class="content">
            <h3>Course</h3>
            <p>
                Allez où vous voulez avec Uber. Commandez une course en un clic et
                c'est parti !
            </p>
            <a href="{{ url('/map') }}" class="details">Détails</a>
        </div>
    </div>

    <div class="card">
        <img src="{{ asset('assets/img/velo.webp') }}" loading="lazy" alt="Deux-roues" class="icon" />
        <div class="content">
            <h3>Deux-roues</h3>
            <p>
                Vous pouvez désormais trouver et louer un vélo électrique via
                l'application Uber.
            </p>
            <a href="#" class="details">Détails</a>
        </div>
    </div>

    <div class="card">
        <img src="{{ asset('assets/img/reserve.webp') }}" loading="lazy" alt="Réserver" class="icon" />
        <div class="content">
            <h3>Réserver</h3>
            <p>
                Réservez votre course à l'avance pour pouvoir vous détendre le jour
                même.
            </p>
            <a href="#" class="details">Détails</a>
        </div>
    </div>

    <div class="card">
        <img src="{{ asset('assets/img/course.webp') }}" loading="lazy" alt="Courses" class="icon" />
        <div class="content">
            <h3>Courses</h3>
            <p>Faites livrer vos courses à votre porte avec Uber Eats.</p>
            <a href="{{ route('lieux.search') }}" class="details">Détails</a>
        </div>
    </div>

    <a href="{{url('/panierclient')}}">voir le panier</a>
</section>

<footer class="footer">
    <div class="container">
        <div class="footer-section">
            <h4>Entreprise</h4>
            <ul>
                <li><a href="#">À propos</a></li>
                <li><a href="#">Nos services</a></li>
                <li><a href="#">Espace presse</a></li>
                <li><a href="#">Investisseurs</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Offres d'emploi</a></li>
                <li><a href="#">Uber AI</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Produits</h4>
            <ul>
                <li><a href="#">Déplacez-vous avec Uber</a></li>
                <li><a href="#">Conduire</a></li>
                <li><a href="#">Livrez</a></li>
                <li><a href="#">Commandez un repas</a></li>
                <li><a href="#">Uber for Business</a></li>
                <li><a href="#">Uber Freight</a></li>
                <li><a href="#">Cartes-cadeaux</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Citoyens du monde</h4>
            <ul>
                <li><a href="#">Sécurité</a></li>
                <li><a href="#">Développement durable</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Déplacements</h4>
            <ul>
                <li><a href="#">Réservez</a></li>
                <li><a href="#">Villes</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
        <div class="app-links">
            <a href="#"><img
                    src="https://d1a3f4spazzrp4.cloudfront.net/uber-com/1.3.8/d1a3f4spazzrp4.cloudfront.net/illustrations/app-store-google-4d63c31a3e.svg"
                    alt="Google Play"></a>
            <a href="#"><img
                    src="https://d1a3f4spazzrp4.cloudfront.net/uber-com/1.3.8/d1a3f4spazzrp4.cloudfront.net/illustrations/app-store-apple-f1f919205b.svg"
                    alt="App Store"></a>
        </div>
        <p>© {{ date('Y') }} Uber Technologies Inc.</p>
        <div class="footer-meta">
            <a href="#">Confidentialité</a> |
            <a href="#">Accessibilité</a> |
            <a href="#">Conditions</a>
        </div>
    </div>
</footer>

<script src="{{ asset('js/main.js') }}" defer></script>
</body>

</html>
