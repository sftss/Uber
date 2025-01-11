@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">

<div class="container">
    <h2 style="text-align: center; margin: 3% 0;">Créer un menu pour le restaurant</h2>

    <form id="formCreateProduit" action="{{ route('menu.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id_restaurant" value="{{ $restaurant_id }}">

        <!-- Libellé du menu -->
        <div class="mb-3">
            <label for="libelle_menu">Libellé du menu</label>
            <input type="text" placeholder="Entrer le nom de votre menu" class="form-control" id="libelle_menu"
                name="libelle_menu" value="{{ old('libelle_menu') }}" required>
            @error('libelle_menu')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Prix du menu -->
        <div class="mb-3">
            <label for="prix_menu">Prix du menu</label>
            <input style="width:15%" type="number" value="0" step="0.01" min="0" class="form-control" id="prix_menu"
                name="prix_menu" value="{{ old('prix_menu') }}" required>
            @error('prix_menu')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Photo du menu -->
        <div class="mb-3">
            <label for="photo_menu">URL de la photo du menu</label>
            <input type="text" class="form-control" id="photo_menu" name="photo_menu" value="{{ old('photo_menu') }}"
                placeholder="Entrer une URL" required>
            @error('photo_menu')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <h2 style="text-align: center; margin: 3% 0; border:none;">Composition</h2>

        <div class="row">
            <!-- Colonne Plat -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="plat_selection">Pour le plat :</label>
                    <select id="plat_selection" name="plat_selection" class="form-control" onchange="togglePlatFields(this)">
                        <option value="nouveau">Créer un nouveau plat</option>
                        <option value="existant">Choisir un plat existant</option>
                    </select>
                    @error('plat_selection')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Conteneur pour les champs de plat -->
                <div class="plat-fields-container">
                    <!-- Champs pour un nouveau plat -->
                    <div id="nouveau_plat_fields" class="mb-3" style="display: none;">
                        <div class="mb-3">
                            <label for="plat_nom">Nom du plat</label>
                            <input type="text" name="plat_nom" id="plat_nom" class="form-control" placeholder="Entrer le nom du plat">
                            @error('plat_nom')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="plat_prix">Prix du plat</label>
                            <input type="number" name="plat_prix" id="plat_prix" class="form-control" placeholder="Entrer le prix du plat" step="0.01" min="0">
                            @error('plat_prix')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="categorie_id">Catégorie du plat :</label>
                            <select name="categorie_id" id="categorie_id" class="form-control">
                                <option value="">-- Sélectionnez une catégorie --</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id_categorie_produit }}">
                                        {{ $categorie->libelle_categorie }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categorie_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="plat_photo">Photo du plat (URL)</label>
                            <input type="text" name="plat_photo" id="plat_photo" class="form-control" placeholder="Entrer une URL de la photo">
                            @error('plat_photo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Sélection d'un plat existant -->
                    <div id="plat_existant_fields" class="mb-3" style="display: none;">
                        <div class="mb-3">
                            <label for="plat_existant">Plat existant :</label>
                            <select name="plat_existant" id="plat_existant" class="form-control">
                                <option value="">Choisir un plat existant</option>
                                @foreach ($plats as $plat)
                                    <option value="{{ $plat->id_plat }}">{{ $plat->libelle_plat }}</option>
                                @endforeach
                            </select>
                            @error('plat_existant')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne Produit -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="produit_selection">Pour le produit :</label>
                    <select id="produit_selection" name="produit_selection" class="form-control" onchange="toggleProduitFields(this)">
                        <option value="nouveau">Créer un nouveau produit</option>
                        <option value="existant">Choisir un produit existant</option>
                    </select>
                    @error('produit_selection')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Conteneur pour les champs de produit -->
                <div class="produit-fields-container">
                    <!-- Champs pour un nouveau produit -->
                    <div id="nouveau_produit_fields" class="mb-3" style="display: none;">
                        <div class="mb-3">
                            <label for="produit_nom">Nom du produit</label>
                            <input type="text" name="produit_nom" id="produit_nom" class="form-control" placeholder="Entrer le nom du produit">
                            @error('produit_nom')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="produit_prix">Prix du produit</label>
                            <input type="number" name="produit_prix" id="produit_prix" class="form-control" placeholder="Entrer le prix du produit" step="0.01" min="0">
                            @error('produit_prix')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="categorie_id2">Catégorie du produit :</label>
                            <select id="categorie_id2" name="categorie_id2" class="form-control">
                                <option value="">Choisir une catégorie</option>
                                @foreach ($categories as $categorie)
                                    <option value="{{ $categorie->id_categorie_produit }}">{{ $categorie->libelle_categorie }}</option>
                                @endforeach
                            </select>
                            @error('categorie_id2')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="produit_photo">Photo du produit (URL)</label>
                            <input type="text" name="produit_photo" id="produit_photo" class="form-control" placeholder="Entrer une URL de la photo">
                            @error('produit_photo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Sélection d'un produit existant -->
                    <div id="produit_existant_fields" class="mb-3" style="display: none;">
                        <div class="mb-3">
                            <label for="produit_existant">Produit existant :</label>
                            <select name="produit_existant" id="produit_existant" class="form-control">
                                <option value="">Choisir un produit existant</option>
                                @foreach ($produits as $produit)
                                    <option value="{{ $produit->id_produit }}">{{ $produit->nom_produit }}</option>
                                @endforeach
                            </select>
                            @error('produit_existant')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton de soumission -->
        <button style="font-weight:bold" type="submit" class="btn btn-primary mt-3">Créer le menu</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formCreateProduit');
    const platSelection = document.getElementById('plat_selection');
    const produitSelection = document.getElementById('produit_selection');
    
    const nouveauPlatFields = document.getElementById('nouveau_plat_fields');
    const nouveauProduitFields = document.getElementById('nouveau_produit_fields');
    const platExistantFields = document.getElementById('plat_existant_fields');
    const produitExistantFields = document.getElementById('produit_existant_fields');

    function toggleFieldsRequired(container, required) {
        const inputs = container.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (required) {
                input.setAttribute('required', 'required');
            } else {
                input.removeAttribute('required');
                input.value = '';
            }
        });
    }

    function updateFields() {
        // Plat fields
        const isPlatNouveau = platSelection.value === 'nouveau';
        nouveauPlatFields.style.display = isPlatNouveau ? 'block' : 'none';
        platExistantFields.style.display = isPlatNouveau ? 'none' : 'block';
        toggleFieldsRequired(nouveauPlatFields, isPlatNouveau);
        toggleFieldsRequired(platExistantFields, !isPlatNouveau);

        // Produit fields
        const isProduitNouveau = produitSelection.value === 'nouveau';
        nouveauProduitFields.style.display = isProduitNouveau ? 'block' : 'none';
        produitExistantFields.style.display = isProduitNouveau ? 'none' : 'block';
        toggleFieldsRequired(nouveauProduitFields, isProduitNouveau);
        toggleFieldsRequired(produitExistantFields, !isProduitNouveau);
    }

    // Initial setup
    updateFields();

    // Listen for changes
    platSelection.addEventListener('change', updateFields);
    produitSelection.addEventListener('change', updateFields);

    // Form validation
    form.addEventListener('submit', function(event) {
        const isPlatNouveau = platSelection.value === 'nouveau';
        const isProduitNouveau = produitSelection.value === 'nouveau';
        let isValid = true;

        if (isPlatNouveau) {
            const platFields = ['plat_nom', 'plat_prix', 'categorie_id', 'plat_photo'];
            platFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
        } else {
            const platExistant = document.getElementById('plat_existant');
            if (!platExistant.value) {
                isValid = false;
                platExistant.classList.add('is-invalid');
            }
        }

        if (isProduitNouveau) {
            const produitFields = ['produit_nom', 'produit_prix', 'categorie_id2', 'produit_photo'];
            produitFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
        } else {
            const produitExistant = document.getElementById('produit_existant');
            if (!produitExistant.value) {
                isValid = false;
                produitExistant.classList.add('is-invalid');
            }
        }

        if (!isValid) {
            event.preventDefault();
            alert('Veuillez remplir tous les champs requis.');
        }
    });
});
</script>