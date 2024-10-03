<?php

$photoUrl = get_field('photo');
// Récupérer le titre de la photo
$photo_titre = get_the_title();
// Récupérer l'URL du post
$post_url = get_permalink();
// Récupérer la référence de la photo
$reference = get_field('reference');
// Récupérer les catégories de la photo
$categories = get_the_terms(get_the_ID(), 'categorie');
$categorie_name = $categories[0]->name; // On suppose qu'il y a au moins une catégorie

?>

<div class="blockPhotoRelative">
    <!-- Afficher l'image avec son URL et un texte alternatif -->
    <img src="<?php echo esc_url($photoUrl); ?>" alt="<?php the_title_attribute(); ?>">

    <div class="overlay">

        <!-- Afficher le titre de la photo -->
        <h2><?php echo esc_html($photo_titre); ?></h2>

        <!-- Afficher le nom de la catégorie -->
        <h3><?php echo esc_html($categorie_name); ?></h3>

        <!-- Icône pour voir la photo en détail -->
        <div class="eye-icon">
            <a href="<?php echo esc_url($post_url); ?>"> <!-- equivalent a https://natha-photo/Photo/non-de-la-photo  acf= [url, url2 , url3] -->
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/icon_eye.svg" alt="voir la photo">
            </a>
        </div>




        <!-- Vérifier si la référence est définie avant d'afficher l'icône fullscreen (on les recuperer pour js)-->
        <?php if ($reference) : ?>
            <div class="fullscreen-icon" data-full="<?php echo esc_attr($photoUrl); ?>" data-category="<?php echo esc_attr($categorie_name); ?>" data-reference="<?php echo esc_attr($reference); ?>">
                 <!-- on associe les references a l'icone fullscreen pour la recuperer en js -->
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/fullscreen.svg" alt="Icone fullscreen">
            </div>
        <?php endif; ?>

    </div>
</div>
