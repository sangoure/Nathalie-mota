<div class="banner">
    <h1>Photographe event</h1>
    <?php
    // on a inclus un bloc PHP qui configure les arguments pour récupérer une photo aléatoire.
//Les arguments incluent le type de publication (dans ce cas, “photo”), le nombre de publications à afficher (1), et le tri aléatoire.
//Ensuite, on a  initialise une requête WordPress avec ces arguments.

    //Configuration des arguments de la requête pour récupérer une photo aléatoire
    $photo_args = array(
        'post_type'      => 'photo',
        'posts_per_page' => 1,
        'orderby'        => 'rand', // Tri aléatoire
    );

    // Initialisation de la requête WordPress avec les arguments définis
    $photo_query = new WP_Query($photo_args);
    // Vérification si des photos sont disponibles avec if
    if ($photo_query->have_posts()) {
        //si sont disponible on mis une Boucle à travers les photos
        while ($photo_query->have_posts()) {
            $photo_query->the_post();
            // Affichage de la vignette de la photo en taille complète
           // Pour chaque photo, on affiche la vignette en taille complète avec
            echo get_the_post_thumbnail(get_the_ID(), 'full');
        }
        // Réinitialisation des données de publication après la boucle
        wp_reset_postdata();
    }
    ?>
</div>
