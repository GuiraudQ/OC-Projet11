document.addEventListener("DOMContentLoaded", function (event) {
  const blocListPhoto = document.querySelector(".blocListPhoto");

  //======================================================================
  // Fonction Select Categorie
  //======================================================================
  const selectCat = document.querySelector("#select-cat");
  const optionsCat = document.querySelector("#options-cat");
  const defaultCat = document.querySelector("#default-cat");
  const inputCat = document.querySelector("#input-cat");
  const optionsCatItems = document.querySelectorAll("#options-cat li");

  selectCat.addEventListener("click", function () {
    optionsCat.classList.toggle("open");
    defaultCat.classList.toggle("defaultOpen");
  });

  optionsCatItems.forEach((option) => {
    option.addEventListener("click", function () {
      defaultCat.innerHTML = `${this.textContent} <span class="material-symbols-outlined">keyboard_arrow_down</span>`;
      inputCat.value = this.getAttribute("data-value");
      let ajaxurl = inputCat.getAttribute("data-url");

      fetch(ajaxurl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "select_categorie",
          categorieId: this.getAttribute("data-value"),
        }),
      })
        .then((response) => response.text()) // Convertit la réponse en texte
        .then((response) => {
          if (response) {
            blocListPhoto.innerHTML = response;
          } else {
          }
        })
        .catch((error) => console.error("Erreur :", error));
    });
  });

  //======================================================================
  // Fonction Select Format
  //======================================================================
  const selectFormat = document.querySelector("#select-format");
  const optionsFormat = document.querySelector("#options-format");
  const defaultFormat = document.querySelector("#default-format");
  const hiddenFormat = document.querySelector("#input-format");
  const optionsFormatItems = document.querySelectorAll("#options-format li");

  selectFormat.addEventListener("click", function () {
    optionsFormat.classList.toggle("open");
    defaultFormat.classList.toggle("defaultOpen");
  });

  optionsFormatItems.forEach((optionFormat) => {
    optionFormat.addEventListener("click", function () {
      defaultFormat.innerHTML = `${this.textContent} <span class="material-symbols-outlined">keyboard_arrow_down</span>`;
      hiddenFormat.value = this.getAttribute("data-value");
      let ajaxurlFormat = hiddenFormat.getAttribute("data-url");

      fetch(ajaxurlFormat, {
        method: "post",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "select_format",
          formatId: this.getAttribute("data-value"),
        }),
      })
        .then((response) => response.text()) // Convertit la réponse en texte
        .then((response) => {
          if (response) {
            blocListPhoto.innerHTML = response;
          } else {
          }
        })
        .catch((error) => console.error("Erreur :", error));
    });
  });

  //======================================================================
  // Fonction Select Filter
  //======================================================================
  const selectFilter = document.querySelector("#select-filter");
  const optionsFilter = document.querySelector("#options-filter");
  const defaultFilter = document.querySelector("#default-filter");
  const hiddenFilter = document.querySelector("#input-filter");
  const optionsFilterItems = document.querySelectorAll("#options-filter li");

  selectFilter.addEventListener("click", function () {
    optionsFilter.classList.toggle("open");
    defaultFilter.classList.toggle("defaultOpen");
  });

  optionsFilterItems.forEach((optionFilter) => {
    optionFilter.addEventListener("click", function () {
      defaultFilter.innerHTML = `${this.textContent} <span class="material-symbols-outlined">keyboard_arrow_down</span>`;
      hiddenFilter.value = this.getAttribute("data-value");
      let ajaxurlFilter = hiddenFilter.getAttribute("data-url");

      fetch(ajaxurlFilter, {
        method: "post",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "select_filter",
          filterName: this.getAttribute("data-value"),
        }),
      })
        .then((response) => response.text()) // Convertit la réponse en texte
        .then((response) => {
          if (response) {
            blocListPhoto.innerHTML = response;
          } else {
          }
        })
        .catch((error) => console.error("Erreur :", error));
    });
  });
});

const lightbox = document.querySelector("#lightbox");

function openLightbox(id, url) {
  lightbox.classList.add("open");

  fetch(url, {
    method: "post",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      action: "lightbox_info",
      postId: id,
    }),
  })
    .then((response) => response.text()) // Convertit la réponse en texte
    .then((response) => {
      if (response) {
        lightbox.innerHTML = response;
      } else {
      }
    })
    .catch((error) => console.error("Erreur :", error));
}
function closeLightbox(id, url) {
  lightbox.classList.remove("open");
}
