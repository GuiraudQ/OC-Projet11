document.addEventListener("DOMContentLoaded", function(event) {
    // Your code to run since DOM is loaded and ready
    const flecheDroite = document.querySelector('#actionPhoto .navPhoto .nextPrev a:nth-child(1)');
    const flecheGauche = document.querySelector('#actionPhoto .navPhoto .nextPrev a:nth-child(2)');

    const imgDroite = document.querySelector('#actionPhoto .navPhoto .hoverImage img:nth-child(1)');
    const imgGauche = document.querySelector('#actionPhoto .navPhoto .hoverImage img:nth-child(2)');

    // Ajoute un écouteur d'événement au bouton
    flecheDroite.addEventListener('mouseover', (e) => {        
        imgDroite.style.display = "block" ;
    });
    flecheDroite.addEventListener('mouseleave', (e) => {        
        imgDroite.style.display = "none" ;
    });
    flecheGauche.addEventListener('mouseover', (e) => {   
        imgGauche.style.display = "block" ;
    });
    flecheGauche.addEventListener('mouseleave', (e) => {   
        imgGauche.style.display = "none" ;
    });
});