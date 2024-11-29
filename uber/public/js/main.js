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
//#endregion

//#region Planification

//#region Jour
document.addEventListener("DOMContentLoaded", () => {
  const arrow = document.querySelector(".toggle-arrow");
  const planifSection = document.querySelector(".interface-planif");

  // Gestion de l'ouverture/fermeture
  arrow.addEventListener("click", () => {
    const isOpen = planifSection.style.display === "block";
    planifSection.style.display = isOpen ? "none" : "block";
    arrow.classList.toggle("open", !isOpen); // Faire pivoter la flèche
  });

  const joursContainer = document.querySelector(".jours");
  const options = { weekday: "long" };
  const today = new Date();

  // Générer les jours
  for (let i = 0; i < 7; i++) {
    const currentDate = new Date();
    currentDate.setDate(today.getDate() + i);

    const dayName = currentDate.toLocaleDateString("fr-FR", options);

    const jourElement = document.createElement("div");
    if (i == 0) {
      jourElement.textContent = "Aujourd'hui";
    } else if (i == 1) {
      jourElement.textContent = "Demain";
    } else {
      jourElement.textContent =
        dayName.charAt(0).toUpperCase() + dayName.slice(1);
    }

    jourElement.addEventListener("click", () => {
      joursContainer.querySelectorAll("div").forEach((day) => {
        day.classList.remove("selected");
      });
      jourElement.classList.add("selected");
    });

    joursContainer.appendChild(jourElement);
  }

  //#region Heure
  const horairesContainer = document.querySelector(".horraires");
  const horaireSelectedInput = document.getElementById("horaire-selected"); // Le champ caché pour l'horraire sélectionné

  let startTime = new Date();
  startTime.setHours(8, 0, 0); // Début à 8h00
  let endTime = new Date(startTime);
  endTime.setHours(8 + 24, 0, 0); // Fin à 5h00 le lendemain

  while (startTime < endTime) {
    const start = `${startTime.getHours()}h${startTime.getMinutes().toString().padStart(2, "0")}`;
    startTime.setMinutes(startTime.getMinutes() + 30); // Ajoute 30 minutes
    const end = `${startTime.getHours()}h${startTime.getMinutes().toString().padStart(2, "0")}`;

    // Vérifie que le prochain créneau ne dépasse pas la fin
    if (startTime >= endTime) break;

    const timeSlot = document.createElement("div");
    timeSlot.className = "time-slot";
    timeSlot.textContent = `${start} - ${end}`;

    // Ajouter l'événement click pour sélectionner un créneau
    timeSlot.addEventListener("click", () => {
      horairesContainer.querySelectorAll(".time-slot").forEach((slot) => {
        slot.classList.remove("selected");
      });
      timeSlot.classList.add("selected");

      // Mettre à jour le champ caché avec l'horraire sélectionné
      horaireSelectedInput.value = `${start} - ${end}`;
    });

    horairesContainer.appendChild(timeSlot);

    // Reculer de 15 minutes pour le chevauchement
    startTime.setMinutes(startTime.getMinutes() - 15);
  }
});
//#endregion Heure
//#endregion Planification
