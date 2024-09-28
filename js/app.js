// Sélectionne le bouton et la div
const contactBtn = document.querySelector('.contact-Btn');
const contactDiv = document.getElementById('contact');

const refPhoto = document.querySelector('span[data-name="your-subject"] input');


// Ajoute un écouteur d'événement au bouton
contactBtn.addEventListener('click', (e) => {
  e.preventDefault(); // Empêche le comportement par défaut du bouton
  
  // Alterne l'ajout ou la suppression de la classe 'active' sur la div
  contactDiv.classList.toggle('active');
});

function openContact(reference) {
  // Alterne l'ajout ou la suppression de la classe 'active' sur la div
  contactDiv.classList.toggle('active');

  refPhoto.value = `${reference}`;
}