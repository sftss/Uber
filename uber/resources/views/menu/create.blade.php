@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">

<div class="container">
    <h2 style="text-align: center; margin: 3% 0;">Créer un menu pour le restaurant</h2>

    <form id="formCreateProduit" action="{{ route('menu.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id_restaurant" value="{{ $restaurant_id }}">

        <!-- Libellé du menu -->
        <div class="form-group">
            <label for="libelle_menu">Libellé du menu</label>
            <input type="text" placeholder="Entrer le nom de votre menu" class="form-control" id="libelle_menu"
                name="libelle_menu" value="{{ old('libelle_menu') }}" required>
                @error('libelle_menu')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

        </div>

        <!-- Prix du menu -->
        <div class="form-group">
            <label for="prix_menu">Prix du menu</label>
            <input style="width:15%" type="number" value="0" step="0.01" min="0" class="form-control" id="prix_menu"
                name="prix_menu" value="{{ old('prix_menu') }}" required>
                @error('prix_menu')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

        </div>

        <!-- Photo du menu -->
        <div class="form-group">
            <label for="photo_menu">URL de la photo du menu</label>
            <input
                style="font-family: Arial, sans-serif; border: 1px solid #ccc; border-radius: 5px; padding: 0.5rem; font-size: 1rem; outline: none; transition: border-color 0.3s;"
                type="text" class="form-control" id="photo_menu" name="photo_menu" value="{{ old('photo_menu') }}"
                placeholder="Entrer une URL" required>
                @error('photo_menu')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
        </div>

        <h2 style="text-align: center; margin: 3% 0; border:none;">Composition</h2>

       

        <!-- Choisir ou créer un plat -->
        <div class="form-group">
            <label for="plat_selection">Choisir ou créer une composition :</label>
            <select id="plat_selection" name="plat_selection" class="form-control" onchange="togglePlatFields(this)">
                <option value="nouveau">Créer une nouvelle composition</option>
                <option value="existant">Choisir une composition existante</option>
            </select>
            @error('plat_selection')
                    <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        


        <div id="nouveau">


        <!-- Champs pour un nouveau plat -->
        <div id="nouveau_plat_fields" style="display: block;">
            <div class="form-group">
                <label for="plat_nom">Nom du plat</label>
                <input type="text" name="plat_nom" id="plat_nom" class="form-control" placeholder="Entrer le nom du plat">
                @error('plat_nom')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="plat_prix">Prix du plat</label>
                <input type="number" name="plat_prix" id="plat_prix" class="form-control" placeholder="Entrer le prix du plat" step="0.01" min="0" >
                @error('plat_prix')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

             <!-- Catégorie du menu -->
            <div class="form-group">
                <label for="categorie_id">Catégorie :</label>
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

            <div class="form-group">
                <label for="plat_photo">Photo du plat (URL)</label>
                <input type="text" name="plat_photo" id="plat_photo" class="form-control" placeholder="Entrer une URL de la photo" >
                @error('plat_photo')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Champs pour un nouveau produit -->
        <div id="nouveau_produit_fields" style="display: block;">
            <div class="form-group">
                <label for="produit_nom">Nom du produit</label>
                <input type="text" name="produit_nom" id="produit_nom" class="form-control" placeholder="Entrer le nom du produit">
                @error('produit_nom')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="produit_prix">Prix du produit</label>
                <input type="number" name="produit_prix" id="produit_prix" class="form-control" placeholder="Entrer le prix du produit" step="0.01" min="0">
                @error('produit_prix')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

             <!-- Catégorie du menu -->
            <div class="form-group">
                <label for="categorie_id2">Catégorie :</label>
                <select id="categorie_id2" name="categorie_id2" >
                    <option value="">Choisir une catégorie</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id_categorie_produit }}">{{ $categorie->libelle_categorie }}</option>
                    @endforeach
                </select>
                @error('categorie_id2')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="produit_photo">Photo du produit (URL)</label>
                <input type="text" name="produit_photo" id="produit_photo" class="form-control" placeholder="Entrer une URL de la photo">
                @error('produit_photo')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        

        <!-- Sélection d'un plat existant -->
        <div id="plat_existant_fields" style="display: none;">
            <div class="form-group">
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

        <!-- Sélection d'un plat existant -->
        <div id="produit_existant_fields" style="display: none;">
            <div class="form-group">
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

        <!-- Bouton de soumission -->
        <button style="font-weight:bold" type="submit" class="btn btn-primary">Créer le menu</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formCreateProduit');
    const platSelection = document.getElementById('plat_selection');
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
                input.value = ''; // Reset values when not required
            }
        });
    }

    function handleSelectionChange() {
        const isNouveau = platSelection.value === 'nouveau';

        // Toggle visibility
        nouveauPlatFields.style.display = isNouveau ? 'block' : 'none';
        nouveauProduitFields.style.display = isNouveau ? 'block' : 'none';
        platExistantFields.style.display = isNouveau ? 'none' : 'block';
        produitExistantFields.style.display = isNouveau ? 'none' : 'block';

        // Toggle required fields
        toggleFieldsRequired(nouveauPlatFields, isNouveau);
        toggleFieldsRequired(nouveauProduitFields, isNouveau);
        
        const platExistantSelect = platExistantFields.querySelector('select');
        const produitExistantSelect = produitExistantFields.querySelector('select');
        
        if (platExistantSelect) {
            if (!isNouveau) {
                platExistantSelect.setAttribute('required', 'required');
            } else {
                platExistantSelect.removeAttribute('required');
                platExistantSelect.value = '';
            }
        }
        
        if (produitExistantSelect) {
            if (!isNouveau) {
                produitExistantSelect.setAttribute('required', 'required');
            } else {
                produitExistantSelect.removeAttribute('required');
                produitExistantSelect.value = '';
            }
        }
    }

    // Initial setup
    handleSelectionChange();

    // Listen for changes
    platSelection.addEventListener('change', handleSelectionChange);

    // Form validation
    form.addEventListener('submit', function(event) {
        const isNouveau = platSelection.value === 'nouveau';
        let isValid = true;

        if (isNouveau) {
            // Validate new plat/produit fields
            const requiredFields = [
                'plat_nom', 'plat_prix', 'categorie_id',
                'produit_nom', 'produit_prix', 'categorie_id2'
            ];

            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
        } else {
            // Validate existing plat/produit selection
            const platExistant = document.getElementById('plat_existant');
            const produitExistant = document.getElementById('produit_existant');

            if (!platExistant.value) {
                isValid = false;
                platExistant.classList.add('is-invalid');
            }

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

<script>

        const urlInput = document.getElementById('photo_menu');
        const urlInput2 = document.getElementById('plat_photo');
        const urlInput3 = document.getElementById('produit_photo');

        function handleValidation(element, errorMsg) {
            const isValid = !errorMsg;
            if (isValid && !element.classList.contains('is-valid')) {
                element.setCustomValidity('');
                element.classList.remove('is-invalid');
                element.classList.add('is-valid');
            } else if (!isValid && !element.classList.contains('is-invalid')) {
                element.setCustomValidity(errorMsg);
                element.classList.remove('is-valid');
                element.classList.add('is-invalid');
            }
        }

        function validateURL(url) {
            try {
                new URL(url);
                return "";
            } catch (error) {
                return "URL invalide";
            }
        }

        if (urlInput || urlInput2 || urlInput3) {
            urlInput.setAttribute('type', 'url');
            urlInput.setAttribute('required', 'true');

            urlInput2.setAttribute('type', 'url');
            urlInput2.setAttribute('required', 'true');

            urlInput3.setAttribute('type', 'url');
            urlInput3.setAttribute('required', 'true');

            let timeout;
            urlInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    const value = this.value.trim();
                    const errorMsg = value ? validateURL(value) : '';
                    handleValidation(this, errorMsg);
                }, 300);
            });
        }

    const style = document.createElement('style');
    style.textContent = `
    .form-control.is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    .form-control.is-valid {
        border-color: #198754;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }`;
    document.head.appendChild(style);
</script>