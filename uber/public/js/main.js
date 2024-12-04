//#region Header hide
let dernierPoint = 1;
const header = document.getElementById("header");
window.addEventListener("scroll", () => {
  const pointActuelle = window.scrollY;

  if (pointActuelle > dernierPoint) {
    header.classList.add("hidden");
  } else {
    header.classList.remove("hidden");
  }

  dernierPoint = pointActuelle;
});
//#endregion Header hide

//#region Planification
document.addEventListener("DOMContentLoaded", () => {
  //#region Jour
  const headerPlanification = document.querySelector(".planifier-header");
  const arrow = document.querySelector(".toggle-arrow");
  const planifSection = document.querySelector(".interface-planif");

  // Gestion de l'ouverture/fermeture de la planification
  headerPlanification.addEventListener("click", () => {
    const isOpen = planifSection.style.display === "block";
    planifSection.style.display = isOpen ? "none" : "block";
    arrow.classList.toggle("open", !isOpen);
  });

  const joursContainer = document.querySelector(".jours");
  const options = { weekday: "long" };
  const today = new Date();

  // Générer jours
  for (let i = 0; i < 7; i++) {
    const currentDate = new Date();
    currentDate.setDate(today.getDate() + i);

    const dayName = currentDate.toLocaleDateString("fr-FR", options);

    const jourElement = document.createElement("div");
    if (i == 0) {
      jourElement.textContent = "Aujourd'hui"; // Affiche "Aujourd'hui"
    } else {
      jourElement.textContent =
        dayName.charAt(0).toUpperCase() + dayName.slice(1);
    }

    jourElement.addEventListener("click", () => {
      joursContainer.querySelectorAll("div").forEach((day) => {
        day.classList.remove("selected");
      });
      jourElement.classList.add("selected");

      // Après avoir sélectionné le jour, on génère les créneaux horaires correspondants
      generateTimeSlots(jourElement.textContent);
    });

    joursContainer.appendChild(jourElement);
  }
  //#endregion Jour

  //#region Heure
  const horairesContainer = document.querySelector(".horraires");
  const horaireSelectedInput = document.getElementById("horaire-selected");

  function generateTimeSlots(selectedDay) {
    let startTime;

    // Si "Aujourd'hui" est sélectionné
    if (selectedDay === "Aujourd'hui") {
      startTime = new Date();
      startTime.setSeconds(0, 0); // On supprime les secondes et millisecondes
      if (startTime.getMinutes() > 0) {
        startTime.setMinutes(30 * Math.ceil(startTime.getMinutes() / 30));
        if (startTime.getMinutes() === 60) {
          startTime.setHours(startTime.getHours() + 1, 0); // Changer d'heure si c'est 60 minutes
        }
      }
    } else {
      startTime = new Date();
      startTime.setDate(
        today.getDate() +
          [
            "lundi",
            "mardi",
            "mercredi",
            "jeudi",
            "vendredi",
            "samedi",
            "dimanche",
          ].indexOf(selectedDay),
      );
      startTime.setHours(0, 0, 0, 0); // Début à minuit
    }

    let endTime = new Date();
    endTime.setHours(24, 0, 0, 0); // Fixe la fin à minuit

    horairesContainer.innerHTML = ""; // Effacer les créneaux précédents

    while (startTime < endTime) {
      const start = `${startTime.getHours().toString().padStart(2, "0")}:${startTime.getMinutes().toString().padStart(2, "0")}`;
      startTime.setMinutes(startTime.getMinutes() + 30); // Ajoute 30 minutes
      const end = `${startTime.getHours().toString().padStart(2, "0")}:${startTime.getMinutes().toString().padStart(2, "0")}`;

      // Vérifie que le prochain créneau ne dépasse pas la fin
      if (startTime > endTime) {
        break;
      }

      const timeSlot = document.createElement("div");
      timeSlot.className = "time-slot";
      timeSlot.textContent = `${start} - ${end}`;

      // Ajouter l'événement click pour sélectionner un créneau
      timeSlot.addEventListener("click", () => {
        horairesContainer.querySelectorAll(".time-slot").forEach((slot) => {
          slot.classList.remove("selected");
        });
        timeSlot.classList.add("selected");

        // Mettre à jour le champ caché avec l'horaire sélectionné
        horaireSelectedInput.value = `${start} - ${end}`;
      });

      horairesContainer.appendChild(timeSlot);
    }
  }

  // Initialiser les créneaux horaires pour aujourd'hui (si aucun jour sélectionné)
  generateTimeSlots("Aujourd'hui");

  //#endregion Heure
});
//#endregion Planification
