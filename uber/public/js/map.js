//ici on utilise 3 api : 
//nominatim et locationIQ pour convertir les adresses en coordonnées, nominatim complètement gratuit mais on peut pas envoyer beaucoup de requetes
//locationIQ pareil avec plus de requetes possibles mais faut créer un compte (mail temporaire possible) d'où la clé d'api en dessous 
//osrm pour calculer le meilleur itinéraire en voiture entre 2 points et sa durée


// Initialisation de la carte Leaflet
const map = L.map('map').setView([45.9207646, 6.1527482], 13); // Coordonnées de l'IUT par défaut

// Ajout d'un fond de carte OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

const inputDepart = document.getElementById("inputDepart");
const inputArrivee = document.getElementById("inputArrivee");
const suggestionsDepart = document.getElementById("suggestionsDepart");
const suggestionsArrivee = document.getElementById("suggestionsArrivee");
const inputDateDepart = document.getElementById("dateDepart");
const dateToday = new Date();
const listePropositions = document.getElementById('propositionsList');


console.log(dateToday.toLocaleTimeString('fr-FR'));

console.log(getDate(new Date(dateToday)))

const cleAPILocationIQ = "pk.69ac2966071395cd67e8a9a5ed00d2c3"; // clé API LocationIQ

var markerDepart = null;
var markerArrivee = null;
let durationInSeconds;

//quand on arrive sur la page on vide les champs
window.onload = function() {
  inputDepart.value = '';
  inputArrivee.value = ' ';
  inputDateDepart.value = dateToday.toISOString().split('T')[0]+"T"+dateToday.toLocaleTimeString('fr-FR',{ hour: '2-digit', minute: '2-digit' });
  inputDateDepart.min = dateToday.toISOString().split('T')[0]+"T"+dateToday.toLocaleTimeString('fr-FR',{ hour: '2-digit', minute: '2-digit' });
};

//ajout de trouverchauffeur au clic du bouton valider de la view
document.getElementById('boutonValider').addEventListener('click', trouverChauffeurs);

// Appliquer la fonction pour les deux inputs
geocodeAddress(inputDepart, suggestionsDepart, markerDepart, true);
geocodeAddress(inputArrivee, suggestionsArrivee, markerArrivee, false);


function getDate(date) {
  const day = String(date.getDate()).padStart(2, '0');  //padstart pour ajouter un 0 si le jour n'a qu'un chiffre
  const month = String(date.getMonth() + 1).padStart(2, '0');  // Le mois commence à 0, donc on ajoute 1
  const year = date.getFullYear();

  return `${day}/${month}/${year}`;
}

function calculDistanceChauffeur(chauffeur, callback) {
  //on prend markerDepart vu qu'on va utiliser la fonction pour localiser les chauffeurs les plus proches du point de départ
  var start = markerDepart.getLatLng();
  const adresse = `${chauffeur.adresse.rue}, ${chauffeur.adresse.ville}, ${chauffeur.adresse.cp}, France`;

  //on utilise l'api locationiq pour convertir la position du chauffeur (qui est au format adresse) en coordonnées pour ne pas surcharger l'api nominatim de requêtes
  fetch(`https://us1.locationiq.com/v1/search.php?key=${cleAPILocationIQ}&q=${encodeURIComponent(adresse)}&format=json`)
  .then(response => response.json())
  .then(data => {
  if (data.length > 0) {
    const lat = data[0].lat;
    const lon = data[0].lon;
    const end = L.latLng(lat, lon);

    //recherche du trajet le plus court depuis la position du chauffeur jusqu'au point de départ du client avec l'api d'osrm
    var osrmUrl = `https://router.project-osrm.org/route/v1/driving/${start.lng},${start.lat};${end.lng},${end.lat}?overview=full&geometries=geojson`;

    fetch(osrmUrl)
      .then(response => response.json())
      .then(data => {
        var durationInSeconds = data.routes[0].duration;

        const minutes = Math.floor(durationInSeconds / 60);

        console.log(`${chauffeur.prenom_chauffeur} ${chauffeur.nom_chauffeur} devrait arriver en ${minutes} minutes `);


        // on fait callback minutes pour être sûr que les minutes se renvoient après la réponse de l'api et son traitement
        callback(minutes);
      })
      .catch(error => {
        console.error("Erreur OSRM:", error);
        callback(null);
      });
  }
})
  .catch(error => {
    console.error("Erreur géocodage:", error);
    callback(null);
});
}

//méthode pour trouver et afficher le meilleur itinéraire entre les deux marqueurs en utilisant l'API OSRM
// Variable globale pour stocker la route affichée
var currentRouteLayer = null;

function getRoute(marker1, marker2) {
  // on prend les coordonnées des marqueurs
  var start = marker1.getLatLng();
  var end = marker2.getLatLng();

  // on envoie une requête OSRM avec l'URL en prenant les coordonnées des marqueurs
  var osrmUrl = `https://router.project-osrm.org/route/v1/driving/${start.lng},${start.lat};${end.lng},${end.lat}?overview=full&geometries=geojson`;

  // on récupère le résultat avec Fetch et on l'affiche avec "L.geoJSON(route).addTo(map);" en utilisant les méthodes de leaflet
  fetch(osrmUrl)
    .then(response => response.json())
    .then(data => {
      var route = data.routes[0].geometry;

      // Si une route existe déjà, on la supprime
      if (currentRouteLayer) {
        map.removeLayer(currentRouteLayer);
      }

      // Ajouter la nouvelle route à la carte
      currentRouteLayer = L.geoJSON(route).addTo(map);

      // on récupère la durée du trajet en secondes
      durationInSeconds = data.routes[0].duration;

      var minutes = Math.floor(durationInSeconds / 60);
      var heures = Math.round(durationInSeconds / 3600);

      var messageTempsDeTrajet = "";

      // Vérification du temps de trajet
      if (durationInSeconds < 0) {
        console.log("temps de trajet anormal en secondes");
        console.log(durationInSeconds);
      }

      if (durationInSeconds < 3600) {
        messageTempsDeTrajet = `En Uber vous devriez mettre ${minutes} minutes à atteindre votre destination`;
      } else {
        messageTempsDeTrajet = `En Uber vous devriez mettre ${heures} heures et ${minutes % 60} minutes à atteindre votre destination`;
      }
      console.log(messageTempsDeTrajet);

      var elementTempsTrajet = document.getElementById("tempsTrajet");
      elementTempsTrajet.textContent = messageTempsDeTrajet;

      // Zoom ou dézoom de la map pour qu'on voie le point de départ et d'arrivée
      var latLngBounds = L.latLngBounds([start, end]);
      map.fitBounds(latLngBounds, { padding: [50, 50] });
    })
    .catch(error => console.error(error));
}

// Fonction pour mettre à jour un marqueur de façon globale
function updateMarker(marker, result, suggestionText) {
  if (marker) {
    // Si un marqueur existe déjà, on le déplace à la nouvelle position
    marker.setLatLng([result.lat, result.lon]);
    marker.bindPopup(suggestionText).openPopup();
  } else {
    // Sinon, créer un nouveau marqueur
    marker = L.marker([result.lat, result.lon]).addTo(map)
      .bindPopup(suggestionText)
      .openPopup();
}
return marker;
}


// Fonction pour géocoder l'adresse, afficher les suggestions dans une liste déroulante et placer un marqueur
function geocodeAddress(inputElement, suggestionsBox, marker, isdepart) {
inputElement.addEventListener("input", debounce(function() {
const query = inputElement.value;
suggestionsBox.innerHTML = ''; // Réinitialiser les suggestions

if (query.length > 3) { // on recherche que l'utilisateur a écrit plus de 3 caractères
  fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}%2C+France&addressdetails=1&limit=5`)
    .then(response => response.json())
    .then(data => {
      console.log("requete")
      if (data.length > 0) {
        // on limite le nombre de suggestions à 5 pour que ça soit plus lisible
        const limitedData = data.slice(0, 5);

        limitedData.forEach(result => {
          // on extrait les détails de l'adresse
          const address = result.address;
          const number = address.house_number || ''; // Numéro de rue
          const street = address.road || ''; // Rue
          const city = address.city || address.town || address.village || ''; // Ville
          const postalCode = address.postcode || ''; // Code postal
          const country = address.country || ''; // Pays

          // on constuit la suggestion avec les détails du dessus pour améliorer la lisibilité (avant il donnait tout genre le quartier, si l'adresse est en france métropolitaine ou non etc)
          const suggestionText = `${number} ${street}, ${postalCode} ${city}, ${country}`;

          const div = document.createElement('div');
          div.textContent = suggestionText.trim(); // Afficher l'adresse formatée

          div.addEventListener('click', function() {
            // on met l'adresse sélectionnée dans le champ de saisie
            inputElement.value = suggestionText;

            // on centre la map sur la position
            map.setView([result. lat, result.lon], 13);

            // Appeler la fonction de mise à jour du marqueur
            marker = updateMarker(marker, result, suggestionText);
            suggestionsBox.innerHTML = ''; // Fermer la liste après sélection
            
            // on affecte les variables des marqueurs en fonction du booléen donné en paramètres
            if(isdepart){
            markerDepart = marker;

            }
            else {
                markerArrivee=marker;
            }

            // si les deux marqueurs ont une valeur on trace la route
            if(markerDepart != null && markerArrivee != null ){
              getRoute(markerDepart,markerArrivee)
            }
            
          });

          suggestionsBox.appendChild(div);
        });
      }
    })
    .catch(error => {
      console.error("Erreur lors de la récupération des données", error); // Gérer les erreurs de fetch
    });
} else {
  suggestionsBox.innerHTML = ''; // on réinitialises les suggestions si la saisie est trop courte
}
}, 1000)); // Délai de 1 seconde
}


//si les deux marqueurs sont renseignés on lance la méthode désignée
function trouverChauffeurs() {
  let dateDepart = new Date(inputDateDepart.value);
  //on supprime les anciennes propositions
  while (propositionsList.firstChild) {
    propositionsList.removeChild(propositionsList.firstChild);
}
  if (getDate(dateDepart) == getDate(dateToday))
  {
    if (!markerDepart || !markerArrivee) {
      alert("Veuillez saisir les deux adresses (départ et arrivée).");
    }
    else {
      console.log("recherche chauffeurs : ");
      geocodeChauffeurs(chauffeurs);
    }
  }
  else{
    categories.forEach(AfficheCategorie);
  }
}


// Fonction de debouncing : déclenche la fonction après un délai
function debounce(func, delay) {
let timeout;
return function(...args) {
clearTimeout(timeout);
timeout = setTimeout(() => func.apply(this, args), delay);
};
}



// fonction pour déterminer les chauffeurs proches de l'adresse de départ
function geocodeChauffeurs(chauffeurs) {
let index = 0;
let chauffeursProches = [];

// setinterval pour espacer les requêtes et ne pas surcharger l'API
const intervalId = setInterval(() => {
  if (index < chauffeurs.length) {
    // Passer un callback à calculDistanceChauffeur
    calculDistanceChauffeur(chauffeurs[index], (trajet) => {

      // Si le temps de trajet est inférieur ou égal à 60 minutes, ajouter à la liste des chauffeurs proches
      if (trajet <= 60 && trajet !== null) {
        chauffeurs[index].tempsDeTrajet = trajet; // Ajouter le temps de trajet au chauffeur
        chauffeursProches.push(chauffeurs[index]);
      }
    });

    index++;
  }
else {
  // Arrêter après avoir traité tous les chauffeurs
  clearInterval(intervalId); 
  if (chauffeursProches.length == 0){
    AfficheAdresse("null");
  }
  else {
  chauffeursProches.forEach(AfficheAdresse);
  }

  }
}, 550);

}



//méthode pour afficher les méthodes des chauffeurs en html
function AfficheAdresse(chauffeur) {
  
  if(chauffeur != "null"){


    let multiplicateurcourse=1;
    var prixint = (durationInSeconds + chauffeur.tempsDeTrajet *60)/50;
    console.log(prixint + 'z');

    // Créer un élément de liste pour afficher le chauffeur proche
    const titrechauffeur = document.createElement('h4');
    const tempsDeTrajetElement = document.createElement('p');
    const prix =document.createElement('p');
    tempsDeTrajetElement.textContent = `${chauffeur.nom_chauffeur}, ${chauffeur.prenom_chauffeur} mettra ${chauffeur.tempsDeTrajet} minutes à arriver.`;
    titrechauffeur.textContent = `${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}`;
    document.getElementById('propositionsList').appendChild(titrechauffeur);
    document.getElementById('propositionsList').appendChild(tempsDeTrajetElement);


    //Uber X , Green, Uber XL, Uber Pet, Berline, Confort

    if (`${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}` == "Uber X"){
      multiplicateurcourse=1.05;
    }
    else if (`${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}` == "Green"){
      multiplicateurcourse=1;
    }    
    else if (`${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}` == "Uber Pet"){
      multiplicateurcourse=1.1;
    }
    else if (`${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}` == "Uber XL"){
      multiplicateurcourse=1.2;
    }
    else if (`${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}` == "Berline"){
      multiplicateurcourse=1.25;
    }
    else if (`${chauffeur.vehicule.categorie_vehicule.lib_categorie_vehicule}` == "Confort"){
      multiplicateurcourse=1.15;
    }
    prixint = roundToDecimals(prixint * multiplicateurcourse, 2);

    console.log(multiplicateurcourse);
    console.log(prixint);
    prix.textContent = `${prixint} €`

    document.getElementById('propositionsList').appendChild(prix);
  }
  else{
    const aucunchauffeur = document.createElement('p');
    aucunchauffeur.textContent = `Il n'y a pas de chauffeur disponible`;
    document.getElementById('propositionsList').appendChild(aucunchauffeur);
  }

  }

  function AfficheCategorie(categorie) {
      
      let multiplicateurcourse=1;
      var prixint = (durationInSeconds)/50;
      
  
      // Créer un élément de liste pour afficher le chauffeur proche
      const titreCategorie = document.createElement('h4');
      const prix =document.createElement('p');
      titreCategorie.textContent = `${categorie.lib_categorie_vehicule}`;
      document.getElementById('propositionsList').appendChild(titreCategorie);
  
  
      //Uber X , Green, Uber XL, Uber Pet, Berline, Confort
  
      if (`${categorie.lib_categorie_vehicule}` == "Uber X"){
        multiplicateurcourse=1.05;
      }
      else if (`${categorie.lib_categorie_vehicule}` == "Green"){
        multiplicateurcourse=1;
      }    
      else if (`${categorie.lib_categorie_vehicule}` == "Uber Pet"){
        multiplicateurcourse=1.1;
      }
      else if (`${categorie.lib_categorie_vehicule}` == "Uber XL"){
        multiplicateurcourse=1.2;
      }
      else if (`${categorie.lib_categorie_vehicule}` == "Berline"){
        multiplicateurcourse=1.25;
      }
      else if (`${categorie.lib_categorie_vehicule}` == "Confort"){
        multiplicateurcourse=1.15;
      }
      prixint = roundToDecimals(prixint * multiplicateurcourse, 2);
  
      console.log(multiplicateurcourse);
      console.log(prixint);
      prix.textContent = `${prixint} €`
  
      document.getElementById('propositionsList').appendChild(prix);
    }

  function roundToDecimals(number, decimals) {
    const factor = Math.pow(10, decimals);
    return Math.round(number * factor) / factor;
  }
