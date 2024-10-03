jQuery(function ($) {
  // Fonction pour gérer le chargement du contenu additionnel
  function loadMoreContent() {
    //La variable page est extraite d’un élément avec l’ID btnLoad-more//
    const page = $("#btnLoad-more").data("page");
    // calcule le numéro de la page suivante en incrémentant la page actuelle//
    const newPage = page + 1;
    //La variable ajaxurl contient l’URL à partir de laquelle le contenu additionnel est récupéré via AJAX//
    const ajaxurl = ajax_params.ajax_url;





  
    //requete pour serveur /backend 
    $.ajax({
      url: ajaxurl,
      type: "post",
      data: {
        page: newPage,
        action: "load_more_photos",
      },
      success: function (response) {
        // Insérez la nouvelle charge dans le conteneur des photos
        $("#load-moreContainer").before(response);

        // Mettez à jour la valeur de la page
        $("#btnLoad-more").data("page", newPage);
      },
    });
  }




  // Utiliser la délégation d'événement sur un parent stable
  $(document).on("click", "#load-moreContainer #btnLoad-more", function () {
//L’élément avec l’ID "load-moreContainer" est masqué en douceur (avec une animation “slow”)//
    $("#load-moreContainer").hide('slow');

//il vérifie si la valeur de l’attribut "data-filtered" de l’élément "#btnLoad-more" est égale à 1.
    if($("#btnLoad-more").data("filtered") == 1){
      //Si c’est le cas, il appelle la fonction changeFilter(true)//
      changeFilter(true);
    } else {
      //Sinon il appelle la fonction loadMoreContent()
      loadMoreContent();
    }
  });
});
