@extends('layouts.professionnel-header')

@section('content')
    <div class="container">
        <h2>Ajouter un Restaurant</h2>

        <form action="{{ route('restaurant.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="nom_etablissement">Nom de l'établissement :</label>
                <input type="text" class="form-control" id="nom_etablissement" name="nom_etablissement" required>
            </div>

            <div class="form-group">
                <label for="id_adresse">Adresse (ID) :</label>
                <input type="number" class="form-control" id="id_adresse" name="id_adresse" required>
            </div>

            @foreach ($jours as $jour)
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <label>{{ $jour->lib_jour }}</label>
                    <input name="horaires[{{ $jour->id_jour }}][ouverture]" type="time" placeholder="Ouverture"
                        class="horaires-ouverture" data-jour="{{ $jour->id_jour }}">
                    <input name="horaires[{{ $jour->id_jour }}][fermeture]" type="time" placeholder="Fermeture"
                        class="horaires-fermeture" data-jour="{{ $jour->id_jour }}">
                    <label>
                        <input name="horaires[{{ $jour->id_jour }}][ferme]" type="checkbox" value="1"
                            class="checkbox-ferme" data-jour="{{ $jour->id_jour }}"> Fermé
                    </label>
                </div>
            @endforeach

            <div class="form-group">
                <label for="description_etablissement">Description :</label>
                <textarea class="form-control" id="description_etablissement" name="description_etablissement" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="propose_livraison">Proposez-vous la livraison ?</label>
                <select class="form-control" id="propose_livraison" name="propose_livraison">
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </div>

            <div class="form-group">
                <label for="propose_retrait">Proposez-vous le retrait ?</label>
                <select class="form-control" id="propose_retrait" name="propose_retrait">
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </div>

            <div class="form-group">
                <label for="photo_restaurant">Photo du restaurant :</label>
                <input type="file" class="form-control" id="photo_restaurant" name="photo_restaurant">
            </div>

            <div class="form-group">
                <label for="id_proprietaire">ID Propriétaire :</label>
                <input type="number" class="form-control" id="id_proprietaire" name="id_proprietaire">
            </div>

            <div class="form-group">
                <label for="categorie">Catégorie :</label>
                <select class="form-control" id="categorie" name="categorie">
                    <option value="">Sélectionner une catégorie</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id_categorie }}">{{ $categorie->nom_categorie }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Ajouter le restaurant</button>
        </form>
    </div>
@endsection

<script>
    document.querySelectorAll('.checkbox-ferme').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const jour = this.dataset.jour;
            const ouvertInput = document.querySelector(`.horaires-ouverture[data-jour="${jour}"]`);
            const fermeInput = document.querySelector(`.horaires-fermeture[data-jour="${jour}"]`);

            const isClosed = this.checked;
            ouvertInput.disabled = isClosed;
            fermeInput.disabled = isClosed;
            if (isClosed) {
                ouvertInput.value = '';
                fermeInput.value = '';
            }
        });
    });

    const departements = [
        "01", "02", "03", "04", "05", "06", "07", "08", "09", "10",
        "11", "12", "13", "14", "15", "16", "17", "18", "19", "2A", "2B",
        "21", "22", "23", "24", "25", "26", "27", "28", "29", "30",
        "31", "32", "33", "34", "35", "36", "37", "38", "39", "40",
        "41", "42", "43", "44", "45", "46", "47", "48", "49", "50",
        "51", "52", "53", "54", "55", "56", "57", "58", "59", "60",
        "61", "62", "63", "64", "65", "66", "67", "68", "69", "70",
        "71", "72", "73", "74", "75", "76", "77", "78", "79", "80",
        "81", "82", "83", "84", "85", "86", "87", "88", "89", "90",
        "91", "92", "93", "94", "95",
        "971", "972", "973", "974", "975", "976", "977", "978", "984", "986", "987", "988", "989"
    ];

    document.addEventListener('DOMContentLoaded', function() {
        const cpInput = document.getElementById('cp');
        const urlInput = document.getElementById('photo_lieu');

        function handleValidation(element, errorMsg) {
            if (errorMsg) {
                element.setCustomValidity(errorMsg);
                element.classList.add('is-invalid');
                element.classList.remove('is-valid');
            } else {
                element.setCustomValidity('');
                element.classList.remove('is-invalid');
                element.classList.add('is-valid');
            }
        }

        //CP
        if (cpInput) {
            cpInput.setAttribute('pattern', '^[0-9]{5}$');
            cpInput.setAttribute('minlength', '5');
            cpInput.setAttribute('maxlength', '5');

            function validateCP(value) {
                if (!/^\d{5}$/.test(value)) {
                    return "Le code postal doit contenir exactement 5 chiffres";
                }

                const dept = value.substring(0, 2);
                if (value.startsWith('20')) {
                    if (!['2A', '2B'].includes(dept) && !departements.includes(dept)) {
                        return "Code postal invalide pour ce département";
                    }
                } else if (value.startsWith('97') || value.startsWith('98')) {
                    const deptDom = value.substring(0, 3);
                    if (!departements.includes(deptDom)) {
                        return "Code postal invalide pour ce département";
                    }
                } else if (!departements.includes(dept)) {
                    return "Code postal invalide pour ce département";
                }
                return "";
            }

            cpInput.addEventListener('input', function() {
                const errorMsg = this.value.trim() ? validateCP(this.value) : '';
                handleValidation(this, errorMsg);
            });
        }

        // URL
        if (urlInput) {
            urlInput.setAttribute('type', 'url');
            urlInput.setAttribute('required', 'true');

            function validateURL(url) {
                try {
                    new URL(url);
                    if (!/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/i.test(url)) {
                        return "L'URL n'est pas dans un format valide";
                    }
                    return "";
                } catch (error) {
                    return "URL invalide";
                }
            }

            urlInput.addEventListener('input', function() {
                const value = this.value.trim();
                const errorMsg = value ? validateURL(value) : '';
                handleValidation(this, errorMsg);
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
<script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
<df-messenger style="z-index: 999;" intent="WELCOME" chat-title="Uber Bot"
    agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745" language-code="fr"></df-messenger>
