//ici on utilise 3 api :
//nominatim et locationIQ pour convertir les adresses en coordonnées, nominatim complètement gratuit mais on peut pas envoyer beaucoup de requetes
//locationIQ pareil avec plus de requetes possibles mais faut créer un compte (mail temporaire possible) d'où la clé d'api en dessous
//osrm pour calculer le meilleur itinéraire en voiture entre 2 points et sa durée

// Initialisation de la carte Leaflet
const map = L.map("map").setView([45.9207646, 6.1527482], 13); // Coordonnées de l'IUT par défaut
var divcrée;

// Ajout d'un fond de carte OpenStreetMap
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);

const inputDepart = document.getElementById("inputDepart");
const inputArrivee = document.getElementById("inputArrivee");
const suggestionsDepart = document.getElementById("suggestionsDepart");
const suggestionsArrivee = document.getElementById("suggestionsArrivee");
const inputDateDepart = document.getElementById("dateDepart");
const dateToday = new Date();
const listePropositions = document.getElementById("propositionsList");

const cleAPILocationIQ = "pk.69ac2966071395cd67e8a9a5ed00d2c3"; // clé API LocationIQ

var markerDepart = null;
var markerArrivee = null;
var departementDepart = "";
let durationInSeconds;

//quand on arrive sur la page on vide les champs
window.onload = function () {
  inputDepart.value = "";
  inputArrivee.value = " ";
  inputDateDepart.value =
    dateToday.toISOString().split("T")[0] +
    "T" +
    dateToday.toLocaleTimeString("fr-FR", {
      hour: "2-digit",
      minute: "2-digit",
    });
  inputDateDepart.min =
    dateToday.toISOString().split("T")[0] +
    "T" +
    dateToday.toLocaleTimeString("fr-FR", {
      hour: "2-digit",
      minute: "2-digit",
    });
};

let isProcessing = false;
//ajout de trouverchauffeur au clic du bouton valider de la view
document.getElementById("boutonValider").addEventListener("click", function () {
  if (isProcessing) {
    return;
  }
  isProcessing = true;

  trouverChauffeurs();
});

// Appliquer la fonction pour les deux inputs
geocodeAddress(inputDepart, suggestionsDepart, markerDepart, true);
geocodeAddress(inputArrivee, suggestionsArrivee, markerArrivee, false);

function getDate(date) {
  const day = String(date.getDate()).padStart(2, "0"); //padstart pour ajouter un 0 si le jour n'a qu'un chiffre
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const year = date.getFullYear();

  return `${day}/${month}/${year}`;
}

function calculDistanceChauffeur(chauffeur, callback) {
  //on prend markerDepart vu qu'on va utiliser la fonction pour localiser les chauffeurs les plus proches du point de départ
  var start = markerDepart.getLatLng();
  const adresse = `${chauffeur.adresse.rue}, ${chauffeur.adresse.ville}, ${chauffeur.adresse.cp}, France`;

  //on utilise l'api locationiq pour convertir la position du chauffeur (qui est au format adresse) en coordonnées pour ne pas surcharger l'api nominatim de requêtes
  fetch(
    `https://us1.locationiq.com/v1/search.php?key=${cleAPILocationIQ}&q=${encodeURIComponent(adresse)}&format=json`,
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.length > 0) {
        const lat = data[0].lat;
        const lon = data[0].lon;
        const end = L.latLng(lat, lon);

        //recherche du trajet le plus court depuis la position du chauffeur jusqu'au point de départ du client avec l'api d'osrm
        var osrmUrl = `https://router.project-osrm.org/route/v1/driving/${start.lng},${start.lat};${end.lng},${end.lat}?overview=full&geometries=geojson`;

        fetch(osrmUrl)
          .then((response) => response.json())
          .then((data) => {
            var durationInSeconds = data.routes[0].duration;

            const minutes = Math.floor(durationInSeconds / 60);

            // on fait callback minutes pour être sûr que les minutes se renvoient après la réponse de l'api et son traitement
            callback(minutes);
          })
          .catch((error) => {
            console.error("Erreur OSRM:", error);
            callback(null);
          });
      }
    })
    .catch((error) => {
      console.error("Erreur géocodage:", error);
      callback(null);
    });
}

//méthode pour trouver et afficher le meilleur itinéraire entre les deux marqueurs en utilisant l'API OSRM
var currentRouteLayer = null;

function getRoute(marker1, marker2) {
  // on prend les coordonnées des marqueurs
  var start = marker1.getLatLng();
  var end = marker2.getLatLng();

  // on envoie une requête OSRM avec l'URL en prenant les coordonnées des marqueurs
  var osrmUrl = `https://router.project-osrm.org/route/v1/driving/${start.lng},${start.lat};${end.lng},${end.lat}?overview=full&geometries=geojson`;

  // on récupère le résultat avec Fetch et on l'affiche avec "L.geoJSON(route).addTo(map);" en utilisant les méthodes de leaflet
  fetch(osrmUrl)
    .then((response) => response.json())
    .then((data) => {
      var route = data.routes[0].geometry;

      // Si une route existe déjà, on la supprime
      if (currentRouteLayer) {
        map.removeLayer(currentRouteLayer);
      }

      currentRouteLayer = L.geoJSON(route).addTo(map);

      // on récupère la durée du trajet en secondes
      durationInSeconds = data.routes[0].duration;

      var minutes = Math.floor(durationInSeconds / 60);
      var heures = Math.round(durationInSeconds / 3600);

      var messageTempsDeTrajet = "";

      // Vérification du temps de trajet
      if (durationInSeconds < 0) {
      }

      if (durationInSeconds < 3600) {
        messageTempsDeTrajet = `En Uber vous devriez mettre ${minutes} minutes à atteindre votre destination`;
      } else {
        messageTempsDeTrajet = `En Uber vous devriez mettre ${heures} heures et ${minutes % 60} minutes à atteindre votre destination`;
      }

      var elementTempsTrajet = document.getElementById("tempsTrajet");
      elementTempsTrajet.textContent = messageTempsDeTrajet;

      // Zoom ou dézoom de la map pour qu'on voie le point de départ et d'arrivée
      var latLngBounds = L.latLngBounds([start, end]);
      map.fitBounds(latLngBounds, { padding: [50, 50] });
    })
    .catch((error) => console.error(error));
}

// Fonction pour mettre à jour un marqueur de façon globale
function updateMarker(marker, result, suggestionText) {
  if (marker) {
    // Si un marqueur existe déjà, on le déplace à la nouvelle position
    marker.setLatLng([result.lat, result.lon]);
    marker.bindPopup(suggestionText).openPopup();
  } else {
    // Sinon, créer un nouveau marqueur
    marker = L.marker([result.lat, result.lon])
      .addTo(map)
      .bindPopup(suggestionText)
      .openPopup();
  }
  return marker;
}

// Fonction pour géocoder l'adresse, afficher les suggestions dans une liste déroulante et placer un marqueur
function geocodeAddress(inputElement, suggestionsBox, marker, isdepart) {
  inputElement.addEventListener(
    "input",
    debounce(function () {
      const query = inputElement.value;
      suggestionsBox.innerHTML = ""; // Réinitialiser les suggestions

      if (query.length > 3) {
        // on recherche que l'utilisateur a écrit plus de 3 caractères
        fetch(
          `https://nominatim.openstreetmap.org/search?format=json&q=${query}%2C+France&addressdetails=1&limit=5`,
        )
          .then((response) => response.json())
          .then((data) => {
            if (data.length > 0) {
              // on limite le nombre de suggestions à 5 pour que ça soit plus lisible
              const limitedData = data.slice(0, 5);

              limitedData.forEach((result) => {
                // on extrait les détails de l'adresse
                const address = result.address;
                const number = address.house_number || ""; // Numéro de rue
                const street = address.road || ""; // Rue
                const city =
                  address.city || address.town || address.village || ""; // Ville
                const postalCode = address.postcode || ""; // Code postal
                const country = address.country || ""; // Pays

                const departmentCode =
                  address["ISO3166-2-lvl6"].substring(3, 5) || ""; // Code du département //0652402353

                // on construit la suggestion avec les détails pour améliorer la lisibilité
                const suggestionText = `${number} ${street}, ${postalCode} ${city}, ${country}`;

                const div = document.createElement("div");
                div.textContent = suggestionText.trim();

                div.addEventListener("click", function () {
                  // on met l'adresse sélectionnée dans le champ de saisie
                  inputElement.value = suggestionText;

                  // on centre la map sur la position
                  map.setView([result.lat, result.lon], 13);

                  marker = updateMarker(marker, result, suggestionText);
                  suggestionsBox.innerHTML = ""; // Fermer la liste après sélection

                  // Affecter les variables des marqueurs en fonction du booléen donné en paramètres
                  if (isdepart) {
                    markerDepart = marker;
                    departementDepart = departmentCode;
                  } else {
                    markerArrivee = marker;
                  }

                  // Si les deux marqueurs ont une valeur, on trace la route
                  if (markerDepart != null && markerArrivee != null) {
                    getRoute(markerDepart, markerArrivee);
                  }
                });

                suggestionsBox.appendChild(div);
              });
            } else {
              // Si aucune adresse n'est trouvée, afficher le message "L'adresse n'existe pas"
              const noResultsMessage = document.createElement("div");
              noResultsMessage.textContent = "L'adresse n'existe pas";
              suggestionsBox.appendChild(noResultsMessage);
            }
          })
          .catch((error) => {
            console.error("Erreur lors de la récupération des données", error); // Gérer les erreurs de fetch
          });
      } else {
        suggestionsBox.innerHTML = "";
      }
    }, 500),
  ); // Délai de 1 seconde
}

//si les deux marqueurs sont renseignés on lance la méthode désignée
function trouverChauffeurs() {
  let dateDepart = new Date(inputDateDepart.value);
  //on supprime les anciennes propositions
  while (propositionsList.firstChild) {
    propositionsList.removeChild(propositionsList.firstChild);
  }
  if (getDate(dateDepart) == getDate(dateToday)) {
    if (!markerDepart || !markerArrivee) {
      alert("Veuillez saisir les deux adresses (départ et arrivée).");
    } else {
      geocodeChauffeurs(chauffeurs);
    }
  } else {
    categories.forEach(AfficheCategorie);
  }
}

// Fonction de debouncing : déclenche la fonction après un délai
function debounce(func, delay) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), delay);
  };
}

// fonction pour déterminer les chauffeurs proches de l'adresse de départ
function geocodeChauffeurs(chauffeurs) {
  let chauffeursProches = [];

  // Parcourir tous les chauffeurs et filtrer ceux du département souhaité
  chauffeurs.forEach((chauffeur) => {
    if (chauffeur.adresse.departement.code_departement == departementDepart) {
      chauffeursProches.push(chauffeur);
    }
  });

  // Si aucun chauffeur n'est trouvé, afficher "null"
  if (chauffeursProches.length == 0) {
    AfficheAdresse("null");
  } else {
    // Pour chaque chauffeur proche, calculer le temps de trajet
    chauffeursProches.forEach((chauffeur) => {
      calculDistanceChauffeur(chauffeur, (trajet) => {
        if (trajet !== null && trajet <= 60) {
          // Ajouter le temps de trajet et afficher l'adresse si inférieur à 60 minutes
          AfficheAdresse(chauffeur, trajet); // Passer le chauffeur et le temps de trajet à la fonction
        }
      });
    });
  }
}

//méthode pour afficher les méthodes des chauffeurs en html
function AfficheAdresse(chauffeur, tempsDeTrajet) {
  if (chauffeur != "null") {
    const div = document.createElement("div");
    div.className = "proposition"; // Utilisez une classe, pas un ID
    let multiplicateurcourse = 1;
    var prixint = (durationInSeconds + tempsDeTrajet * 60) / 50;

    // Créer un élément de liste pour afficher le chauffeur proche
    const titrechauffeur = document.createElement("h4");
    const tempsDeTrajetElement = document.createElement("p");
    const prix = document.createElement("p");
    tempsDeTrajetElement.textContent = `Temps estimé d'arrivée du chauffeur : ${tempsDeTrajet} minutes.`;
    titrechauffeur.textContent = `${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}`;
    document.getElementById("propositionsList").appendChild(div);
    div.appendChild(titrechauffeur);
    div.appendChild(tempsDeTrajetElement);

    // Logic for the pricing multiplier
    if (
      `${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}` ==
      "Uber X"
    ) {
      multiplicateurcourse = 1.05;
    } else if (
      `${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}` ==
      "Green"
    ) {
      multiplicateurcourse = 1;
    } else if (
      `${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}` ==
      "Uber Pet"
    ) {
      multiplicateurcourse = 1.1;
    } else if (
      `${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}` ==
      "Uber XL"
    ) {
      multiplicateurcourse = 1.2;
    } else if (
      `${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}` ==
      "Berline"
    ) {
      multiplicateurcourse = 1.25;
    } else if (
      `${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}` ==
      "Confort"
    ) {
      multiplicateurcourse = 1.15;
    }
    prixint = roundToDecimals(prixint * multiplicateurcourse, 2);
    prix_reservation = prixint;
    prix.textContent = `${prixint} €`;
    div.appendChild(prix);
    div.style.padding = "10px";
    div.style.margin = "10px 0";
    div.style.border = "1px solid #ccc";
    div.style.borderRadius = "8px";
    div.style.boxShadow = "0px 4px 8px rgba(0, 0, 0, 0.1)";
    div.style.backgroundColor = "#f9f9f9";
    div.style.cursor = "pointer";
    div.style.transition = "all 0.3s ease";

    div.style.position = "relative";

    div.addEventListener("mouseover", function () {
      div.style.backgroundColor = "#e0e0e0";
      div.style.boxShadow = "0px 6px 12px rgba(0, 0, 0, 0.2)";
    });

    div.addEventListener("mouseout", function () {
      div.style.backgroundColor = "#f9f9f9";
      div.style.boxShadow = "0px 4px 8px rgba(0, 0, 0, 0.1)";
    });

    const voirDetailsBtn = document.createElement("button");
    voirDetailsBtn.textContent = "Voir détails";
    voirDetailsBtn.style.marginTop = "10px";
    voirDetailsBtn.style.padding = "5px 10px";
    voirDetailsBtn.style.border = "none";
    voirDetailsBtn.style.backgroundColor = "#4CAF50";
    voirDetailsBtn.style.color = "white";
    voirDetailsBtn.style.cursor = "pointer";
    voirDetailsBtn.style.borderRadius = "5px";
    voirDetailsBtn.style.position = "absolute";
    voirDetailsBtn.style.top = "10px";
    voirDetailsBtn.style.right = "10px";
    div.appendChild(voirDetailsBtn);

    let detailsAffiches = false;

    voirDetailsBtn.addEventListener("click", function (event) {
      event.stopPropagation(); // Empêcher le clic de propager à la div principale

      if (detailsAffiches) {
        const vehiculeInfo = div.querySelector(".vehicule-info");
        const chauffeurInfo = div.querySelector(".chauffeur-info");
        const reserverBtn = div.querySelector(".reserver-btn");

        if (vehiculeInfo) {
          div.removeChild(vehiculeInfo);
        }
        if (chauffeurInfo) {
          div.removeChild(chauffeurInfo);
        }
        if (reserverBtn) {
          div.removeChild(reserverBtn);
        }

        voirDetailsBtn.textContent = "Voir détails";
      } else {
        const vehiculeInfo = document.createElement("p");
        vehiculeInfo.classList.add("vehicule-info");
        vehiculeInfo.textContent = `Véhicule: ${chauffeur.vehicule.marque}`;
        const breakElement = document.createElement("br");
        vehiculeInfo.appendChild(breakElement);
        vehiculeInfo.appendChild(
          document.createTextNode(
            `Couleur: ${chauffeur.vehicule.couleur.lib_couleur}`,
          ),
        );

        const chauffeurInfo = document.createElement("p");
        chauffeurInfo.classList.add("chauffeur-info");
        chauffeurInfo.textContent = `Chauffeur: ${chauffeur.nom_chauffeur} ${chauffeur.prenom_chauffeur}`;

        // Ajouter un bouton Réserver avec le même style que Voir détails
        const reserverBtn = document.createElement("button");
        reserverBtn.textContent = "Réserver";
        reserverBtn.style.marginTop = "10px";
        reserverBtn.style.padding = "5px 10px";
        reserverBtn.style.border = "none";
        reserverBtn.style.backgroundColor = "black";
        reserverBtn.style.color = "white";
        reserverBtn.style.cursor = "pointer";
        reserverBtn.style.borderRadius = "5px";
        reserverBtn.style.position = "absolute";
        reserverBtn.style.top = "50px";
        reserverBtn.style.right = "10px";

        reserverBtn.addEventListener("click", function () {
          // Créer la course
          creerCourse(chauffeur, tempsDeTrajet);

          // Mettre à jour le bouton pour afficher "Course réservée"
          reserverBtn.textContent = "Course réservée";
          reserverBtn.disabled = true; // Désactiver le bouton une fois la course réservée
        });

        // Ajouter ces informations et le bouton à la div
        div.appendChild(vehiculeInfo);
        div.appendChild(chauffeurInfo);
        div.appendChild(reserverBtn);
        voirDetailsBtn.textContent = "Fermer les détails";
      }

      detailsAffiches = !detailsAffiches;
    });
  } else {
    const aucunchauffeur = document.createElement("p");
    aucunchauffeur.textContent = `Il n'y a pas de chauffeur disponible`;
    document.getElementById("propositionsList").appendChild(aucunchauffeur);
    isProcessing = false;
  }
}
var prix_reservation;
function AfficheCategorie(categorie) {
  let multiplicateurcourse = 1;
  var prixint = durationInSeconds / 50;

  const titreCategorie = document.createElement("h4");
  const prix = document.createElement("p");
  titreCategorie.textContent = `${categorie.lib_categorie_vehicule}`;
  document.getElementById("propositionsList").appendChild(div);
  div.appendChild(titreCategorie);

  // Uber X, Green, Uber XL, Uber Pet, Berline, Confort
  if (`${categorie.lib_categorie_vehicule}` == "Uber X") {
    multiplicateurcourse = 1.05;
  } else if (`${categorie.lib_categorie_vehicule}` == "Green") {
    multiplicateurcourse = 1;
  } else if (`${categorie.lib_categorie_vehicule}` == "Uber Pet") {
    multiplicateurcourse = 1.1;
  } else if (`${categorie.lib_categorie_vehicule}` == "Uber XL") {
    multiplicateurcourse = 1.2;
  } else if (`${categorie.lib_categorie_vehicule}` == "Berline") {
    multiplicateurcourse = 1.25;
  } else if (`${categorie.lib_categorie_vehicule}` == "Confort") {
    multiplicateurcourse = 1.15;
  }
  prixint = roundToDecimals(prixint * multiplicateurcourse, 2);

  prix.textContent = `${prixint} €`;

  div.appendChild(prix);
  isProcessing = false;
}

function roundToDecimals(number, decimals) {
  const factor = Math.pow(10, decimals);
  return Math.round(number * factor) / factor;
}

let courseDejaReservee = false;

function creerCourse(chauffeur, tempsDeTrajet) {
  // Vérifier si une course est déjà réservée
  if (courseDejaReservee) {
    alert("Vous avez déjà réservé une course.");
    return;
  }

  const departCoords = {
    lat: markerDepart.getLatLng().lat,
    lng: markerDepart.getLatLng().lng,
  };

  const arriveeCoords = {
    lat: markerArrivee.getLatLng().lat,
    lng: markerArrivee.getLatLng().lng,
  };

  function getLieuDetails(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`;
    return fetch(url)
      .then((response) => response.json())
      .then((data) => {
        const address = data.address || {};
        return {
          rue: address.road || "Rue non trouvée",
          code_postal: address.postcode || "Code postal non trouvé",
          ville:
            address.city ||
            address.town ||
            address.village ||
            "Ville non trouvée",
        };
      })
      .catch((error) => {
        console.error("Erreur lors du géocodage :", error);
        return {
          rue: "Erreur",
          code_postal: "Erreur",
          ville: "Erreur",
        };
      });
  }

  // Obtenir les détails des lieux de départ et d'arrivée
  Promise.all([
    getLieuDetails(departCoords.lat, departCoords.lng),
    getLieuDetails(arriveeCoords.lat, arriveeCoords.lng),
  ]).then(([lieuDepart, lieuArrivee]) => {
    // Construire la course avec les données enrichies
    console.log(prix_reservation)
    const course = {
      temps_trajet: tempsDeTrajet,
      chauffeur: {
        nom: `${chauffeur.nom_chauffeur} ${chauffeur.prenom_chauffeur}`,
      },
      lieu_depart_rue: lieuDepart.rue,
      lieu_depart_ville: lieuDepart.ville,
      lieu_depart_cp: lieuDepart.code_postal,
      lieu_arrivee: lieuArrivee,
      prix_reservation: prix_reservation,
    };

    // Envoyer les données au serveur
    fetch("/php/reserver_course.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(course),
    })
      .then((response) => response.json())
      .then((data) => {
        // Marquer la course comme réservée
        courseDejaReservee = true;
        console.log(data);

        // Désactiver tous les boutons de réservation
        const boutonReserver = document.querySelectorAll(".reserver-btn");
        boutonReserver.forEach((btn) => {
          btn.disabled = true;
          btn.textContent = "Course réservée";
        });
      })
      .catch((error) => {
        console.error("Erreur lors de l'envoi de la course :", error);
      });
  });
}
