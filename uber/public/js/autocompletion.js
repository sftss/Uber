const rue = document.getElementById("rue");
const cp = document.getElementById("cp");
const ville = document.getElementById("ville");

const suggestionBox = document.getElementById("suggestionsDepart");

function debounce(func, delay) {
  let timer;
  return function (...args) {
    clearTimeout(timer);
    timer = setTimeout(() => func.apply(this, args), delay);
  };
}

function buildQuery(rueValue, cpValue, villeValue) {
  let query = "";
  if (rueValue.length >= 2) query += `${rueValue}`;
  if (cpValue.length >= 2) query += `${query ? ", " : ""}${cpValue}`;
  if (villeValue.length >= 2) query += `${query ? ", " : ""}${villeValue}`;
  return query ? `${query}, France` : ""; // Ajouter "France" pour limiter la recherche.
}

function geocodeAddress() {
  const rueValue = rue.value.trim();
  const cpValue = cp.value.trim();
  const villeValue = ville.value.trim();

  // Ne pas lancer la recherche si moins de 2 caractères dans tous les champs
  if (rueValue.length < 2 && cpValue.length < 2 && villeValue.length < 2) {
    suggestionBox.innerHTML = ""; // Réinitialiser les suggestions
    return;
  }

  const query = buildQuery(rueValue, cpValue, villeValue);

  if (!query) {
    suggestionBox.innerHTML = "<div>Veuillez remplir au moins un champ avec 2 caractères ou plus.</div>";
    return;
  }

  suggestionBox.innerHTML = ""; // Réinitialiser les suggestions

  fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&limit=5`)
    .then((response) => response.json())
    .then((data) => {
      if (data.length > 0) {
        data.forEach((result) => {
          const address = result.address;
          const number = address.house_number || ""; // Numéro de rue
          const street = address.road || ""; // Rue
          const city = address.town || address.village || "";
          const agglo = address.city || "";
          const postalCode = address.postcode || "";

          // Construire le texte de suggestion avec numéro + rue
          const suggestionText = `${number ? number + " " : ""}${street} ${postalCode ? "(" + postalCode + ")" : ""} ${city || agglo}`;

          const div = document.createElement("div");
          div.textContent = suggestionText.trim();

          div.addEventListener("click", function () {
            // Mettre à jour les champs avec les informations sélectionnées
            rue.value = `${number ? number + " " : ""}${street}`; // Inclut le numéro de rue
            cp.value = postalCode;
            ville.value = city || agglo;

            // Centrer une éventuelle carte sur la position (si une carte est utilisée)
            if (typeof map !== "undefined") {
              map.setView([result.lat, result.lon], 13);
            }

            suggestionBox.innerHTML = ""; // Fermer les suggestions
          });

          suggestionBox.appendChild(div);
        });
      } else {
        suggestionBox.innerHTML = "<div>Aucune adresse trouvée.</div>";
      }
    })
    .catch((error) => {
      console.error("Erreur lors de la récupération des données", error);
      suggestionBox.innerHTML = "<div>Une erreur s'est produite. Veuillez réessayer.</div>";
    });
}

// Ajouter les écouteurs d'événements pour tous les champs
[rue, cp, ville].forEach((input) => {
  input.addEventListener(
    "input",
    debounce(() => geocodeAddress(), 500) // Limite les requêtes à une toutes les 500ms
  );
});
