jQuery(document).ready(function ($) {
  $("#load-more").on("click", function () {
    let button = $(this);
    let page = button.data("page");
    let ajaxurl = button.data("url");

    $.ajax({
      url: ajaxurl,
      type: "post",
      data: {
        action: "load_more_photos",
        page: page,
      },
      success: function (response) {
        if (response) {
          // Ajouter les nouveaux articles après les anciens
          $(".blocListPhoto").append(response);
          button.data("page", page + 1); // Incrémenter le numéro de la page
        } else {
          // Si on a atteint la fin des articles
          button.text("Plus d'articles").attr("disabled", true);
        }
      },
    });
  });
});
