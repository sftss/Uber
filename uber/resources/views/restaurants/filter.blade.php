<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">

<header class="header" id="header">
    <nav class="navbar">
        <a class="navbar-brand" href="../#header">Uber</a>
        <div class="navbar-links">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="https://www.uber.com/fr/fr/">Déplacez-vous avec Uber</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://www.uber.com/fr/fr/drive/">Conduire</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://www.uber.com/fr/fr/business/">Professionnel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://www.ubereats.com/">Uber Eats</a>
                </li>
            </ul>
        </div>
        <div class="navbar-connect">
            <button class="btn btn-outline-light">Connexion</button>
            <button class="btn btn-light">S'inscrire</button>
        </div>
    </nav>
</header>

<form action="{{ route('restaurants.filter') }}" method="GET" class="filter-form">
    <div class="form-group">
        <label for="lieu" class="form-label">Rechercher par ville ou par nom :</label>
        <input type="text" id="lieu" name="lieu" class="form-input"
            placeholder="Rechercher par Nom ou par Ville" value="{{ $lieu ?? '' }}">
    </div>

    <div class="form-group checkboxes">
        <p> Mode de livraison</p>
        <div>
            <input type="checkbox" id="livre" name="livre" {{ request('livre') ? 'checked' : '' }}>
            <label for="livre">Livraison</label>
        </div>
        <div>
            <input type="checkbox" id="emporter" name="emporter" {{ request('emporter') ? 'checked' : '' }}>
            <label for="emporter">À emporter</label>
        </div>

    </div>

    <div class="form-group">
        <label for="categorie" class="form-label">Catégorie de restaurant :</label>
        <select id="categorie" name="categorie" class="form-input">
            <option value="">Toutes les catégories</option>
            @foreach ($categories as $categorie)
                <option value="{{ $categorie->id_categorie }}"
                    {{ request('categorie') == $categorie->id_categorie ? 'selected' : '' }}>
                    {{ $categorie->lib_categorie }}
                </option>
            @endforeach
        </select>

        <!-- <div class="planifier">
            <h2>Planifier</h2>
            <div class="interface-planif">
                <div class="jours">
                </div>
                <div class="horraires">
                </div>
            </div>
        </div> -->

        <div class="planifier">
            <h2>Planifier
                <span class="toggle-arrow">➤</span>
            </h2>
            <div class="interface-planif" style="display: none;">
                <div class="jours">
                    <!-- Les jours seront générés ici -->
                </div>
                <div class="horraires">
                    <!-- Les horaires seront générés ici -->
                </div>
            </div>
        </div>

    </div>
    <input type="hidden" id="horaire-selected" name="horaire-selected" value="">
    <button type="submit" class="btn-submit">Rechercher</button>
</form>

<section class="restaurants-list">
    @if (isset($restaurants) && $restaurants->isNotEmpty())
        @foreach ($restaurants as $restaurant)
            <div class="restaurant-card">
                <img src="{{ $restaurant->photo_restaurant }}" alt="Image de {{ $restaurant->nom_etablissement }}"
                    class="restaurant-image">
                <div class="restaurant-details">
                    <h3>{{ $restaurant->nom_etablissement }}</h3>
                    <p><strong>Ville :</strong> {{ $restaurant->ville }}</p>
                    <p><strong>Livraison :</strong> {{ $restaurant->propose_livraison ? 'Oui' : 'Non' }}</p>
                    <p><strong>À emporter :</strong> {{ $restaurant->propose_retrait ? 'Oui' : 'Non' }}</p>
                    <p><strong>Catégorie :</strong> {{ $restaurant->lib_categorie ?? 'Non spécifiée' }}</p>
                </div>
            </div>
        @endforeach
    @elseif(isset($restaurants))
        <p class="no-results">Aucun restaurant ne correspond à vos critères.</p>
    @endif
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

<script src="{{ asset('js/main.js') }}"></script>
