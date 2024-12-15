// Sélectionne le bouton et la div
const contactBtn = document.querySelector(".contact-Btn");
const contactDiv = document.getElementById("contact");
const contactForm = document.querySelector("#contact .contactForm");

const menuBtn = document.querySelector(".menu-burger");
const menuNav = document.querySelector("#menu nav");

const refPhoto = document.querySelector('span[data-name="your-subject"] input');

// Ajoute un écouteur d'événement au bouton
contactBtn.addEventListener("click", (e) => {
  e.stopImmediatePropagation();
  e.preventDefault();

  contactDiv.classList.toggle("active");

  closeContact();
});

menuBtn.addEventListener("click", (e) => {
  e.preventDefault(); // Empêche le comportement par défaut du bouton

  // Alterne l'ajout ou la suppression de la classe 'active' sur la div
  menuNav.classList.toggle("navOpen");
});

function openContact(reference, e) {
  // Alterne l'ajout ou la suppression de la classe 'active' sur la div

  e = e || window.event;

  e.stopImmediatePropagation();

  contactDiv.classList.toggle("active");
  refPhoto.value = `${reference}`;

  closeContact();
}

function closeContact() {
  document.addEventListener("click", (e) => {
    if (!contactForm.contains(e.target)) {
      contactDiv.classList.remove("active");
    }
  });
}
