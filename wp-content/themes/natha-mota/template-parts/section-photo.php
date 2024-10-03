<?php
//sargs    contient les paramètres pour la requête.
$args = array(
    //difini les articles types photo
    'post_type'      => 'photo',
    //limite le nombre de photos à 8.
    'posts_per_page' => 8,
    //spécifie l’ordre de tri (dans ce cas, croissant).
    'order'          => 'ASC',
);
//La requête est effectuée avec WP_Query pour récupérer les articles correspondant aux critères définis dans $args.
$photo_block = new WP_Query($args);

// Vérification s'il y a des photos
if ($photo_block->have_posts()) :

    // Définir les arguments pour le bloc photo
    set_query_var('photo_block_args', array('context' => 'front-page'));

    // Boucle pour afficher chaque photo
    while ($photo_block->have_posts()) :
        $photo_block->the_post();
        //Pour chaque photo, le modèle bloc-photo est inclus en utilisant get_template_part().
        get_template_part('template-parts/bloc-photo', get_post_format());//Le format de la photo est déterminé post format
    endwhile;

    // Réinitialisation de la requête
    //Après avoir parcouru toutes les photos, la requête est réinitialisée avec wp_reset_postdata().
//Si aucune photo n’est trouvée, le message “Aucune photo trouvée.” est affiché.
    wp_reset_postdata();
else :
    echo 'Aucune photo trouvée.';
endif;
?>


<!-- Bloc pour le chargement de plus de photos -->
 <!--  Cet attribut permet d’identifier le bouton.  -->
<div id="load-moreContainer">
    <!--  Cet attribut permet d’identifier le bouton data-page=1  -->
    <!--data-url=""Cet attribut personnalisé stocke l’URL à laquelle charger davantage de photos-->
    <button id="btnLoad-more" data-page="1" data-url="">Charger plus</button>
</div>
