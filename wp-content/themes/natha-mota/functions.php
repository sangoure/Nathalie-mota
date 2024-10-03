<?php
// Ajout des styles personnalisés
function enqueue_custom_styles()
{
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/assets/scss/style.css');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_styles');

// Ajout du support pour la balise de titre
function theme_slug_setup()
{
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'theme_slug_setup');

// Enregistrement des menus
function register_menus()
{
    register_nav_menus(
        array(
            'header-menu' => 'menu header',
            'footer-menu' => 'menu footer'
        )
    );
}
add_action('init', 'register_menus');

// Ajout du support pour les miniatures (post-thumbnails)
function theme_support_post_thumbnails()
{
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'theme_support_post_thumbnails');


// Ajout des scripts personnalisés
function enqueue_custom_scripts()
{
    // Enqueue jQuery from CDN
    wp_enqueue_script('jquery-cdn', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js', array(), '3.7.1', true);

    // Enqueue modale-contact.js
    wp_enqueue_script('modale-contact-script', get_template_directory_uri() . '/assets/js/modale-contact.js', array('jquery'), '1.0.0', true);

    // Enqueue burger-menu.js
    wp_enqueue_script('menu-burger-script', get_template_directory_uri() . '/assets/js/menu-burger.js', array('jquery'), '1.0.0', true);

    // Enqueue miniatures.js
    wp_enqueue_script('miniatures-script', get_template_directory_uri() . '/assets/js/miniatures.js', array('jquery'), '1.0.0', true);

    // Enqueue lightbox.js
    wp_enqueue_script('lightbox-script', get_template_directory_uri() . '/assets/js/lightbox.js', array('jquery'), '1.0.0', true);

    // Enqueue filtre.js
    wp_enqueue_script('filtre-script', get_template_directory_uri() . '/assets/js/filtre.js', array('jquery'), '1.0.0', true);

    // Bibliotheque Select2 pour les selects de tri
    wp_enqueue_script('select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13', true);
    wp_enqueue_style('select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', array());

    //Enqueue custom-select.js
    wp_enqueue_script('custom-select-script', get_template_directory_uri() . '/assets/js/custom-select.js', array('jquery'), '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

// Ajout du script load-more-photos.js et filtre.js avec wp_localize_script pour passer des paramètres AJAX
function enqueue_load_more_photos_script()
{
    wp_enqueue_script('load-more-photos', get_template_directory_uri() . '/assets/js/load-more-photos.js', array('jquery'), null, true);

    wp_enqueue_script('filtre', get_template_directory_uri() . '/assets/js/filtre.js', array('jquery'), null, true);

    // Utilisez wp_localize_script pour passer des paramètres à votre script
    wp_localize_script('load-more-photos', 'ajax_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));

    wp_localize_script('filtre', 'ajax_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_load_more_photos_script');






// Fonction pour charger plus de photos via AJAX
function load_more_photos()
{
    //démarrer une session (via session_start//
    session_start();
    // Récupère le numéro de page à partir des données POST (via $_POST['page']).
    $page = $_POST['page']; 


    //construit une requête WP_Query pour récupérer les photos
       
        $args = array(
            'post_type'      => 'photo',     // Type de publication : photo
            'posts_per_page' => 8,          // Nombre de photos par page (-1 pour toutes)
            //'orderby'        => 'rand',      // Tri aléatoire
            'order'          => 'ASC',       // L’ordre de tri est ascendant (ASC).
            'paged'          => $page,       // Numéro de page

        );




    // Exécute la requête WP_Query avec les arguments
    $photo_block = new WP_Query($args);
    $return = array();
    //la boucle parcourt chaque photo  Vérifie s'il y a des photos dans la requête //
    if ($photo_block->have_posts()) :
        // Boucle à travers les photos
        while ($photo_block->have_posts()) :
            $photo_block->the_post();
            // Inclut la partie du modèle pour afficher un bloc de photo
            
            get_template_part('template-parts/bloc-photo', get_post_format());
            
        endwhile;
//Après la boucle, les données post sont réinitialisées avec reset-postdata//
        // Cela garantit que les requêtes ultérieures ne sont pas affectées par les données de la boucle précédente//
        wp_reset_postdata();
    endif;
    //get number of post in $photo_block->have_posts() left

    if($photo_block->max_num_pages != $page ){
        $return['more'] = true;
        //Si d’autres pages de photos sont disponibles (c’est-à-dire qu’il reste plus de photos à afficher),
        // un bouton “Charger plus” est généré. Le numéro de page suivant est calculé en ajoutant 1 au numéro de page actuel ($next_page = $page + 1). 
//Le chemin vers le fichier admin-ajax.php est inclus pour gérer la requête AJAX.
        $next_page = $page + 1;
        echo '<div id="load-moreContainer"><button id="btnLoad-more" data-page="'. $next_page .'" data-url="' . admin_url('admin-ajax.php') . '" data-filtered="1" >Charger plus</button></div>';
    }

    //  La fonction se termine avec die() pour éviter toute autre exécution après avoir généré la sortie.
    die();
}


//add_action('wp_ajax_load_more_photos', 'load_more_photos'); : Cette ligne ajoute une action pour les utilisateurs
// connectés. Lorsque l’action load_more_photos est déclenchée via une requête AJAX,
// la fonction load_more_photos sera exécutée.
add_action('wp_ajax_load_more_photos', 'load_more_photos');

//add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos'); : Cette ligne ajoute une action pour les utilisateurs
// non connectés (les visiteurs du site qui ne sont pas connectés). Elle fonctionne de la même manière que la première ligne, 
//mais s’applique aux utilisateurs non connectés.
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');







// Fonction pour filtrer les photos via AJAX
function filter_photos()
{
    // Vérifiez si l'action est définie
    if (isset($_POST['action']) && $_POST['action'] == 'filter_photos') {
        // Récupérez les filtres et nettoyez-les
        $filter = array_map('sanitize_text_field', $_POST['filter']);
        $page = $_POST['page'];

        // Ajoutez des messages de débogage pour voir les valeurs reçues
        error_log('Filter values: ' . print_r($filter, true));

        // Construisez votre requête WP_Query avec les filtres
        $args = array(
            'post_type'      => 'photo',
            'posts_per_page' => 8,
            'order'          => 'ASC',
            'paged'          => $page,
            'tax_query'      => array(
                'relation' => 'AND',
            ),
        );








        // Ajoutez la taxonomie pour la catégorie si elle est spécifiée
        if (!empty($filter['category'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'categorie',
                'field'    => 'slug',
                'terms'    => $filter['category'],
            );
        }

        // Ajoutez la taxonomie pour l'année si elle est spécifiée
        if (!empty($filter['years'])) {
            $args['order'] = ($filter['years'] == 'date_desc') ? 'DESC' : 'ASC';
        }

        // Ajoutez la taxonomie pour le format si elle est spécifiée
        if (!empty($filter['format'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'format',
                'field'    => 'slug',
                'terms'    => $filter['format'],
            );
        }
        // Effectuez la requête WP_Query
        $query = new WP_Query($args);

        // Vérifiez si la requête a réussi
        if ($query->have_posts()) {
            // Boucle à travers les résultats de la requête
            while ($query->have_posts()) :
                $query->the_post();
                // Récupérez et affichez les informations de chaque photo
                $photoId      = get_post_thumbnail_id();
                $reference    = get_field('reference');
                $refUppercase = strtoupper($reference);

                // Ajoutez des messages de débogage pour les champs ACF
                error_log('Photo ID: ' . $photoId);
                error_log('Reference: ' . $reference);

                // Affiche le bloc de photo
                get_template_part('template-parts/bloc-photo');
            endwhile;

            if ($query->max_num_pages > 1 && $page == 1) {
                $next_page = $page + 1;
                echo '<div id="load-moreContainer"><button id="btnLoad-more" data-page="'. $next_page .'" data-url="' . admin_url('admin-ajax.php') . '" data-filtered="1" >Charger plus</button></div>';
            }
            ?>

            <?php
            // Réinitialisez les données de requête après la boucle de requête
            wp_reset_query();
        } else {
            // Aucune photo ne correspond aux critères de filtrage
            echo '<p class="critereFiltrage">Aucune photo ne correspond aux critères de filtrage</p>';
        }
    }

    // Assurez-vous que votre code renvoie la sortie souhaitée pour le traitement AJAX
    die();
}

// Hook pour les utilisateurs connectés
add_action('wp_ajax_filter_photos', 'filter_photos');
// Hook pour les utilisateurs non connectés
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos');
