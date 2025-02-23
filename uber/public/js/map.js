//ici on utilise 3 api :
//nominatim et locationIQ pour convertir les adresses en coordonnées, nominatim complètement gratuit mais on peut pas envoyer beaucoup de requetes
//locationIQ pareil avec plus de requetes possibles mais faut créer un compte (mail temporaire possible) d'où la clé d'api en dessous
//osrm pour calculer le meilleur itinéraire en voiture entre 2 points et sa durée

let map;
// Initialisation de la carte Leaflet
if (typeof L === "undefined") {
  // Charger Leaflet si ce n'est pas déjà fait
  var script = document.createElement("script");
  script.src = "https://unpkg.com/leaflet/dist/leaflet.js";

  var scriptcss = document.createElement("script");
  scriptcss.src = "https://unpkg.com/leaflet/dist/leaflet.css";

  script.onload = function () {
    // Une fois que Leaflet est chargé, exécuter votre code
    map = L.map("map").setView([45.9207646, 6.1527482], 13); // Coordonnées de l'IUT par défaut

    // Ajout d'un fond de carte OpenStreetMap
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(
      map,
    ); // Appeler la fonction d'initialisation de la carte
  };
  document.head.appendChild(script);
  document.head.appendChild(scriptcss);
} else {
  // Si Leaflet est déjà chargé, appeler directement la fonction d'initialisation de la carte
  map = L.map("map").setView([45.9207646, 6.1527482], 13); // Coordonnées de l'IUT par défaut

  // Ajout d'un fond de carte OpenStreetMap
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);
}

var divcrée;

const inputDepart = document.getElementById("inputDepart");
const inputArrivee = document.getElementById("inputArrivee");
const suggestionsDepart = document.getElementById("suggestionsDepart");
const suggestionsArrivee = document.getElementById("suggestionsArrivee");
const inputDateDepart = document.getElementById("dateDepart");
const dateToday = new Date();
const listePropositions = document.getElementById("propositionsList");
const modification = getUrlParameter("modification");

inputDateDepart.value = dateToday.toISOString().slice(0, 16);

const cleAPILocationIQ = "pk.69ac2966071395cd67e8a9a5ed00d2c3"; // clé API LocationIQ

var markerDepart = null;
var markerArrivee = null;
var departementDepart = "";
let durationInSeconds;

geocodeAddress(inputDepart, suggestionsDepart, markerDepart, true);
geocodeAddress(inputArrivee, suggestionsArrivee, markerArrivee, false);
// Fonction pour obtenir les paramètres d'URL lors de la modification d'une course
function getUrlParameter(name) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(name);
}

document.addEventListener("DOMContentLoaded", function () {
  if (coursePourModification) {
    const inputDepart = document.getElementById("inputDepart");
    inputDepart.value = coursePourModification.lieu_depart
      ? `${coursePourModification.lieu_depart.rue},${coursePourModification.lieu_depart.cp}, ${coursePourModification.lieu_depart.ville}, France `
      : "";

    const inputArrivee = document.getElementById("inputArrivee");
    inputArrivee.value = coursePourModification.lieu_arrivee
      ? `${coursePourModification.lieu_arrivee.rue}, ${coursePourModification.lieu_arrivee.cp}, ${coursePourModification.lieu_arrivee.ville}, France `
      : "";

    const dateDepart = document.getElementById("dateDepart");
    dateDepart.value = formatDateForInput(
      coursePourModification.date_prise_en_charge,
    );

    setupAddressModification();
  }
});

function setupAddressModification() {
  const inputDepart = document.getElementById("inputDepart");
  const inputArrivee = document.getElementById("inputArrivee");
  const suggestionsDepart = document.getElementById("suggestionsDepart");
  const suggestionsArrivee = document.getElementById("suggestionsArrivee");

  // Configurer la géocodification pour les inputs de départ et d'arrivée
  geocodeAddressModif(inputDepart, suggestionsDepart, markerDepart, true);
  geocodeAddressModif(inputArrivee, suggestionsArrivee, markerArrivee, false);
}

function geocodeAddressModif(inputElement, suggestionsBox, marker, isdepart) {
  const query = inputElement.value;

  if (query.length > 3) {
    // Effectuer la requête uniquement si la saisie a plus de 3 caractères
    fetch(
      `https://nominatim.openstreetmap.org/search?format=json&q=${query}&addressdetails=1&limit=1`,
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.length > 0) {
          // Récupérer uniquement le premier résultat
          const result = data[0];

          // Extraire les coordonnées et les informations de l'adresse
          const latitude = result.lat;
          const longitude = result.lon;
          const addressDetails = result.display_name; // Affichage complet de l'adresse

          // Mettre à jour le champ de saisie avec l'adresse trouvée
          inputElement.value = addressDetails;

          // Centrer la carte sur la position trouvée
          map.setView([latitude, longitude], 13);

          // Mettre à jour le marqueur avec les informations trouvées
          marker = updateMarker(
            marker,
            { lat: latitude, lon: longitude },
            addressDetails,
          );

          // Mettre à jour les variables selon le type de marqueur (départ ou arrivée)
          if (isdepart) {
            markerDepart = marker;
          } else {
            markerArrivee = marker;
          }

          // Tracer la route si les deux marqueurs sont définis
          if (markerDepart != null && markerArrivee != null) {
            getRoute(markerDepart, markerArrivee);
          }
        } else {
          console.log("Pas d'adresse trouvée.");
        }
      })
      .catch((error) => {
        console.error("Erreur lors de la récupération des données :", error);
      });
  } else {
    console.log("Saisie insuffisante pour effectuer une recherche.");
    suggestionsBox.innerHTML = ""; // Vider les suggestions si la saisie est insuffisante
  }
}

function formatDateForInput(dateString) {
  const date = new Date(dateString);
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");
  const hours = String(date.getHours()).padStart(2, "0");
  const minutes = String(date.getMinutes()).padStart(2, "0");

  return `${year}-${month}-${day}T${hours}:${minutes}`;
}

let isProcessing = false;
//ajout de trouver chauffeur au clic du bouton valider de la view
document.getElementById("boutonValider").addEventListener("click", function () {
  if (isProcessing) {
    return;
  }
  isProcessing = true;

  trouverChauffeurs();

  setTimeout(function () {
    isProcessing = false;
  }, 1000);
});

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
        messageTempsDeTrajet = `En Uber, vous devriez mettre ${minutes} minutes à atteindre votre destination`;
      } else {
        messageTempsDeTrajet = `En Uber, vous devriez mettre ${heures} heures et ${minutes % 60} minutes à atteindre votre destination`;
      }

      var elementTempsTrajet = document.getElementById("tempsTrajet");
      elementTempsTrajet.textContent = messageTempsDeTrajet;

      // Zoom ou dézoom de la map pour qu'on voie le point de départ et d'arrivée
      var latLngBounds = L.latLngBounds([start, end]);
      map.fitBounds(latLngBounds, { padding: [50, 50] });
    })
    .catch((error) => console.error(error));
}

// maj marqueur
function updateMarker(marker, result, suggestionText) {
  if (marker) {
    // Si existe, déplace à la nouvelle position
    marker.setLatLng([result.lat, result.lon]);
    marker.bindPopup(suggestionText).openPopup();
  } else {
    // Sinon, créer nouveau
    marker = L.marker([result.lat, result.lon])
      .addTo(map)
      .bindPopup(suggestionText)
      .openPopup();
  }
  return marker;
}

// géocoder l'adresse, afficher suggestions dans une liste, placer un marqueur
function geocodeAddress(inputElement, suggestionsBox, marker, isdepart) {

  inputElement.addEventListener(
    "input",
    debounce(function () {
      const query = inputElement.value;
      suggestionsBox.innerHTML = ""; // Réinitialiser suggestions

      if (query.length > 3) {
        // recherche à partir de 3 caractères
        fetch(
          `https://nominatim.openstreetmap.org/search?format=json&q=${query}%2C+France&addressdetails=1&limit=5`,
        )
          .then((response) => response.json())
          .then((data) => {
            if (data.length > 0) {
              // limite nb suggestions à 5
              const limitedData = data.slice(0, 5);

              limitedData.forEach((result) => {
                // détails adresse
                const address = result.address;
                const number = address.house_number || ""; // Numéro de rue
                const street = address.road || ""; // Rue
                const city = address.town || address.village || ""; // Ville
                let agglo = address.city || "";
                let postalCode = address.postcode || ""; // Code postal
                const dep = address.county || "";
                const country = address.country || ""; // Pays
                console.log(limitedData)
                const departmentCode = address["ISO3166-2-lvl6"].substring(3, 5) || ""; // Code du département //0652402353
                if (postalCode == "")
                {
                  postalCode = departmentCode + "000";
                }
                if (city != "" && agglo !="")
                  {
                    agglo = "";
                  }
                // suggestion avec détails pour lisibilité
                const suggestionText = [
                  number && number,
                  street && street,
                  postalCode && postalCode,
                  city && city,
                  agglo && agglo,
                  dep && dep,
                  country && country,
                ]
                  .filter(Boolean)
                  .join(", ");
                const div = document.createElement("div");
                div.textContent = suggestionText.trim();

                div.addEventListener("click", function () {
                  // adresse sélectionnée dans le champ de saisie
                  inputElement.value = suggestionText;

                  // on centre la map sur la position
                  map.setView([result.lat, result.lon], 13);

                  marker = updateMarker(marker, result, suggestionText);
                  suggestionsBox.innerHTML = ""; // Fermer liste après sélection

                  // Affecter variables des marqueurs en fonction du booléen paramètres
                  if (isdepart) {
                    markerDepart = marker;
                    departementDepart = departmentCode;
                  } else {
                    markerArrivee = marker;
                  }

                  // Si deux marqueurs ont une valeur, trace la route
                  if (markerDepart != null && markerArrivee != null) {
                    getRoute(markerDepart, markerArrivee);
                  }
                });
                suggestionsBox.appendChild(div);
              });
            } else {
              // Si aucune adresse trouvée, afficher message "L'adresse n'existe pas"
              const noResultsMessage = document.createElement("div");
              noResultsMessage.textContent = "L'adresse n'existe pas";
              suggestionsBox.appendChild(noResultsMessage);
            }
          })
          .catch((error) => {
            console.error("Erreur lors de la récupération des données", error); // Gérer erreurs fetch
          });
      } else {
        suggestionsBox.innerHTML = "";
      }
    }, 500),
  ); // Délai 1s
}
let dateDepart;

function trouverChauffeurs() {
  isProcessing = false;

  dateDepart = new Date(inputDateDepart.value);

  // nettoyer les propositions d'avant
  while (propositionsList.firstChild) {
    propositionsList.removeChild(propositionsList.firstChild);
  }

  if (isProcessing) {
    return;
  }

  isProcessing = true;

  if (getDate(dateDepart) == getDate(dateToday)) {
    if (!markerDepart || !markerArrivee) {
      alert("Veuillez saisir les deux adresses (départ et arrivée).");
      isProcessing = false;
    } else {
      geocodeChauffeurs(chauffeurs);
    }
  } else {
    categories.forEach(AfficheCategorie);
    isProcessing = false;
  }
}

function debounce(func, delay) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), delay);
  };
}

// déterminer chauffeurs proches de l'adresse de départ
function geocodeChauffeurs(chauffeurs) {
  let chauffeursProches = [];
  let index = 0;

 /* function traiterChauffeur() {
    if (index < chauffeurs.length) {
      let chauffeur = chauffeurs[index];
      index++; // Incrémenter index pour prochain chauffeur
      calculDistanceChauffeur(chauffeur, (trajet) => {
        console.log(chauffeur);
        if (trajet !== null && trajet <= 60) {
          // Ajouter temps de trajet et afficher l'adresse si inférieur à 60 minutes
          AfficheAdresse(chauffeur, trajet); // Passer le chauffeur et le temps de trajet à la fonction
          console.log("inséré");
        }
      });

      // Attendre une seconde avant de traiter le prochain chauffeur
      setTimeout(traiterChauffeur, 650); // 1000 ms = 1 seconde
    }
  }

  // Démarrer le traitement du premier chauffeur
  traiterChauffeur();*/
  
  // Ajouter les chauffeurs proches dans le tableau
chauffeurs.forEach((chauffeur) => {
  if (chauffeur.adresse.departement.code_departement == departementDepart) {
    chauffeursProches.push(chauffeur);
  }
});

// Si aucun chauffeur n'est trouvé, afficher "null"
if (chauffeursProches.length == 0) {
  AfficheAdresse("null");
} else {
  // Démarrer l'intervalle pour traiter chaque chauffeur un par un
  const intervalId = setInterval(() => {
    if (index < chauffeursProches.length) {
      const chauffeur = chauffeursProches[index];
      calculDistanceChauffeur(chauffeur, (trajet) => {
        if (trajet !== null && trajet <= 60) {
          // Ajouter le temps de trajet et afficher l'adresse si inférieur à 60 minutes
          AfficheAdresse(chauffeur, trajet); // Passer le chauffeur et le temps de trajet à la fonction
        }
      }); // Calculer la distance pour le chauffeur en cours
      index++; // Passer au chauffeur suivant
    } else {
      clearInterval(intervalId); // Arrêter l'intervalle quand tous les chauffeurs ont été traités
    }
  }, 650); // Espacer chaque appel de 0.650 seconde
}
}

var prixcourse;
let courseDejaReservee = false;
//méthode pour afficher les méthodes des chauffeurs en html
function AfficheAdresse(chauffeur, tempsDeTrajet) {
  if (chauffeur !== "null") {
    const div = document.createElement("div");
    div.className = "proposition"; 
    let multiplicateurcourse = 1;
    var prixint = (durationInSeconds + tempsDeTrajet * 60) / 50;

    const titrechauffeur = document.createElement("h4");
    const tempsDeTrajetElement = document.createElement("p");
    const prix = document.createElement("p");
    tempsDeTrajetElement.textContent = `Temps estimé d'arrivée du chauffeur : ${tempsDeTrajet} minutes.`;
    titrechauffeur.textContent = `${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}`;
    document.getElementById("propositionsList").appendChild(div);
    div.appendChild(titrechauffeur);
    div.appendChild(tempsDeTrajetElement);

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
    prixcourse = prixint;
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
      event.stopPropagation(); 

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
            `Couleur: ${chauffeur.vehicule.couleur.lib_couleur}`
          )
        );

        const chauffeurInfo = document.createElement("p");
        chauffeurInfo.classList.add("chauffeur-info");
        chauffeurInfo.textContent = `Chauffeur: ${chauffeur.nom_chauffeur} ${chauffeur.prenom_chauffeur}`;


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

        const infoBubble = document.createElement("div");
        infoBubble.className = "info-bubble";
        infoBubble.textContent = "?";

      const infoContent = document.createElement("div");
      infoContent.className = "info-content";
      infoContent.textContent = "Vous ne serez débité que si le chauffeur accepte la course. En cas de refus aucun prélèvement ne sera effectué.";

    infoBubble.appendChild(infoContent);
    
    infoBubble.addEventListener("click", function () {
      toggleInfo(infoBubble);
  });

        reserverBtn.addEventListener("click", function () {
          // Créer la course
          console.log(dateDepart);

          const departCoords = {
            lat: markerDepart.getLatLng().lat,
            lng: markerDepart.getLatLng().lng,
          };
        
          const arriveeCoords = {
            lat: markerArrivee.getLatLng().lat,
            lng: markerArrivee.getLatLng().lng,
          };
          Promise.all([
            getLieuDetails(departCoords.lat, departCoords.lng),
            getLieuDetails(arriveeCoords.lat, arriveeCoords.lng),
          ]).then(([lieuDepart, lieuArrivee]) => {
            const course = {
              id_chauffeur: chauffeur.id_chauffeur,
              chauffeur_nom: chauffeur.nom_chauffeur,
              chauffeur_prenom: chauffeur.prenom_chauffeur,
              lieu_depart_rue: lieuDepart.rue,
              lieu_depart_ville: lieuDepart.ville,
              lieu_depart_cp: lieuDepart.code_postal,
              lieu_arrivee_rue: lieuArrivee.rue,
              lieu_arrivee_ville: lieuArrivee.ville,
              lieu_arrivee_cp: lieuArrivee.code_postal,
              prix_reservation: prixcourse,
              tempscourse: durationInSeconds,
              date_trajet: dateDepart,
              id_course: coursePourModification
                ? coursePourModification.id_course
                : null,
              id_client: id,
            };
          

          if (coursePourModification) {
            fetch("/modifier-course", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
              },
              body: JSON.stringify(course),
            })
              .then((response) => response.json())
              .then((data) => {
                if (data.status === "success") {
                  console.log("Course réservée avec succès", data);
                } else {
                  console.error("Erreur lors de la réservation", data);
                }
              })
              .catch((error) => {
                console.error("Erreur de communication avec le serveur", error);
              });
          } else {
            fetch("/reserver-course", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
              },
              body: JSON.stringify(course),
            })
              .then((response) => response.json())
              .then((data) => {
                if (data.status === "success") {
                  console.log("Course réservée avec succès", data);
                } else {
                  console.error("Erreur lors de la réservation", data);
                }
              })
              .catch((error) => {
                console.error("Erreur de communication avec le serveur", error);
              });
          }
        });
          courseDejaReservee = true;
          reserverBtn.textContent = "Course réservée";
          reserverBtn.disabled = true;
        });


        var paypalUrl = `${paypalBaseRoute}?prix=${prixcourse}`;

        var paypalBtn = document.createElement('a');
        paypalBtn.href = paypalUrl;
        paypalBtn.textContent = "Payer avec PayPal";
        paypalBtn.classList.add('paypal-button');


        div.appendChild(vehiculeInfo);
        div.appendChild(chauffeurInfo);
        div.appendChild(reserverBtn);
        voirDetailsBtn.textContent = "Fermer les détails";

        
        div.appendChild(paypalBtn);
        div.appendChild(infoBubble);
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


function AfficheCategorie(categorie) {
  const div = document.createElement("div");
  div.className = "proposition"; 
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


  prixcourse = prixint;

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
  reserverBtn.style.top = "50%";
  reserverBtn.style.right = "10px";
  reserverBtn.style.transform = "translateY(-50%)"; 
  reserverBtn.classList.add("reserver-btn");
  reserverBtn.dataset.prix = prixint;

  reserverBtn.addEventListener("click", function () {

    console.log(dateDepart);
    const departCoords = {
      lat: markerDepart.getLatLng().lat,
      lng: markerDepart.getLatLng().lng,
    };
  
    const arriveeCoords = {
      lat: markerArrivee.getLatLng().lat,
      lng: markerArrivee.getLatLng().lng,
    };
    Promise.all([
      getLieuDetails(departCoords.lat, departCoords.lng),
      getLieuDetails(arriveeCoords.lat, arriveeCoords.lng),
    ]).then(([lieuDepart, lieuArrivee]) => {
      const coursecat = {
        categorie: categorie.lib_categorie_vehicule, 
      lieu_depart_rue: lieuDepart.rue,
      lieu_depart_ville: lieuDepart.ville,
      lieu_depart_cp: lieuDepart.code_postal,
      lieu_arrivee_rue: lieuArrivee.rue,
      lieu_arrivee_ville: lieuArrivee.ville,
      lieu_arrivee_cp: lieuArrivee.code_postal,
      prix_reservation: prixcourse,
      tempscourse: durationInSeconds,
      date_trajet: dateDepart,
      id_course: coursePourModification
        ? coursePourModification.id_course
        : null,
        id_client: id,
      };
    
    if (coursePourModification) {
      fetch("/modifier-course", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify(coursecat),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            console.log("Course réservée avec succès", data);
          } else {
            console.error("Erreur lors de la réservation", data);
          }
        })
        .catch((error) => {
          console.error("Erreur de communication avec le serveur", error);
        });
    } else {
      fetch("/creercat-course", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify(coursecat),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            console.log("Course réservée avec succès", data);
          } else {
            console.error("Erreur lors de la réservation", data);
          }
        })
        .catch((error) => {
          console.error("Erreur de communication avec le serveur", error);
        });
    }
  });

    reserverBtn.classList.replace("reserver-btn", "courseclique");


    if (coursePourModification) {
      const boutonReserver = document.querySelectorAll(".reserver-btn");
      boutonReserver.forEach((btn) => {
        btn.disabled = true;
        btn.textContent = "Une autre course a deja été modifiée";
      });
      const boutonclique = document.querySelector(".courseclique");
      boutonclique.textContent = "Course Modifiée";
      boutonclique.disabled = true;
    } else {
      const boutonReserver = document.querySelectorAll(".reserver-btn");
      boutonReserver.forEach((btn) => {
        btn.disabled = true;
        btn.textContent = "Une autre course a deja été réservée";
      });
      const boutonclique = document.querySelector(".courseclique");
      boutonclique.textContent = "Course Réservée";
      boutonclique.disabled = true;
    }
  });
  const infoBubble = document.createElement("div");
        infoBubble.className = "info-bubble";
        infoBubble.textContent = "?";

      const infoContent = document.createElement("div");
      infoContent.className = "info-content";
      infoContent.textContent = "Vous ne serez débité que si le chauffeur accepte la course. En cas de refus aucun prélèvement ne sera effectué.";

    infoBubble.appendChild(infoContent);
    
    infoBubble.addEventListener("click", function () {
      toggleInfo(infoBubble);
  });

  div.appendChild(reserverBtn);
  div.appendChild(infoBubble);
  isProcessing = false;
}

function roundToDecimals(number, decimals) {
  const factor = Math.pow(10, decimals);
  return Math.round(number * factor) / factor;
}


const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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


  function toggleInfo(element) {
    document.querySelectorAll('.info-bubble.active').forEach(bubble => {
        if (bubble !== element) {
            bubble.classList.remove('active');
        }
    });
    
    element.classList.toggle('active');
    
    event.stopPropagation();
}

document.addEventListener('click', function(event) {
    if (!event.target.closest('.info-bubble')) {
        document.querySelectorAll('.info-bubble.active').forEach(bubble => {
            bubble.classList.remove('active');
        });
    }
});