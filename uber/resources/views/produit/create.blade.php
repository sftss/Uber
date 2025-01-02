@extends('layouts.header')

<link href="{{ asset('assets/style/app.css') }}" rel="stylesheet">

<div class="container">
    <h2 style="text-align: center; margin: 3% 0;">Ajouter un produit pour le restaurant</h2>

    <form id="formCreateProduit" action="{{ route('produit.store') }}" method="POST">
        @csrf

        <input type="hidden" name="id_restaurant" value="{{ $restaurant_id }}">

        <div class="form-group">
            <label for="libelle_produit">Libellé du produit :</label>
            <input placeholder="Entrer le nom de votre produit" type="text" class="form-control" id="libelle_produit"
                name="libelle_produit" value="{{ old('libelle_produit') }}" required>

        </div>

        <div class="form-group">
            <label for="prix_produit">Prix du produit (€) :</label>
            <input style="width:15%" min=0 value=0 type="number" step="1" class="form-control" id="prix_produit"
                name="prix_produit" value="{{ old('prix_produit') }}" required>
        </div>

        <div class="form-group">
            <label for="photo_produit">URL de la photo du produit :</label>
            <input class="form-control"
                style="font-family: Arial, sans-serif; border: 1px solid #ccc; border-radius: 5px; padding: 0.5rem; font-size: 1rem; outline: none; transition: border-color 0.3s;"
                type="text" class="form-control" id="photo_produit" name="photo_produit" value="http"
                placeholder="Entrer une URL" required>
        </div>

        <div class="form-group">
            <label for="categorie_id">Catégorie :</label>
            <select id="categorie_id" name="categorie_id" required>
                <option value="">Choisir une catégorie</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id_categorie_produit }}">{{ $categorie->libelle_categorie }}</option>
                @endforeach
            </select>
        </div>

        <button style="font-weight:bold" type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formCreateProduit');
        const categorieSelect = document.getElementById('categorie_id');

        form.addEventListener('submit', function(event) {
            if (categorieSelect.value === "") {
                event.preventDefault();
                alert("Veuillez sélectionner une catégorie valide.");
                categorieSelect.classList.add('is-invalid');
                categorieSelect.focus();
            } else {
                categorieSelect.classList.remove('is-invalid');
            }
        });

        const urlInput = document.getElementById('photo_produit');

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

        if (urlInput) {
            urlInput.setAttribute('type', 'url');
            urlInput.setAttribute('required', 'true');

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
    });

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
