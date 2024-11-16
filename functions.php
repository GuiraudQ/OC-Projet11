<?php

//======================================================================
// Demarrer les variable de session
//======================================================================
function start_session() {
    if (!session_id()) {
        session_start();
    }
}

//======================================================================
// Chargement des script et style
//======================================================================
function enqueue_theme_styles() {
    //Reset CSS
    wp_enqueue_style('reset-css', 'https://cdn.jsdelivr.net/gh/jgthms/minireset.css@master/minireset.min.css', array(), '8.0.1');
    //Material icons
    wp_enqueue_style('material-icons', 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined', array(), '8.0.1');

    // Enqueue App perso
    wp_enqueue_script('app', get_stylesheet_directory_uri() . '/js/app.js', array(), '1.0.0', true, filemtime(get_stylesheet_directory() . '/js/app.js'));
    wp_enqueue_script('select', get_stylesheet_directory_uri() . '/js/select.js', array(), '1.0.0', true, filemtime(get_stylesheet_directory() . '/js/select.js'));

    // Chemin vers le fichier CSS
    $theme_version = wp_get_theme()->get('Version');
    wp_enqueue_style('theme-styles', get_template_directory_uri() . '/assets/style/theme.css', array(), $theme_version);

    // Chargement fichiers Ajax
    wp_enqueue_script('load-more', get_template_directory_uri() . '/js/load-more.js', array('jquery'), null, true);

    // Chargement script et style fichier Single
    if( is_single() ) {
        wp_enqueue_style('single-styles', get_template_directory_uri() . '/assets/style/single-photo.css', array(), $theme_version);
        wp_enqueue_script('single-script', get_template_directory_uri() . '/js/single-photo.js', array(), $theme_version);
    }
}

//======================================================================
// Mise en place du menu
//======================================================================
function register_my_menus() {
    register_nav_menus(
        array(
            'primary' => __( 'Primary Menu' ),
            'footer' => __( 'Footer Menu' )
        )
    );
}

//======================================================================
// Logo Personaliser
//======================================================================
function theme_setup() {
    // Support du logo personnalisé
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}

//======================================================================
// Fonction factoriser et variable
//======================================================================
$category_id = null;
$category_name = "";
$category_id = null;
$category_name = "";
$filter_name = "";
$paged = 0;

function loadArgs($category_name = null, $paged = 1){
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 6, // Nombre d'articles par page
        'paged' => $paged, // Page actuelle pour la pagination
    );

    if ($category_name) {
        $args['categorie'] = $category_name->name;
    }
}
function blocList($link, $title, $isImage, $image){
    $myHtml = '<a href="' . $link . '" title="' . $title . '">' . $image . '</a>';
    return $myHtml;
}

//======================================================================
// Fonction Load More
//======================================================================
function load_more_photos() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_POST['page'])) {
        wp_send_json_error('Numéros de page manquant');
    }

    $_SESSION['paged'] = intval($_POST['page'] + 1); // Récupérer la page à charger (incrémenté côté client)
    
    if (isset($_SESSION['categorie_id']) && $_SESSION['categorie_id'] != 0 ){
        $args = array(
            'post_type' => 'photo',
            'posts_per_page' => 6, // Même nombre que dans la requête initiale
            'categorie' => $_SESSION['category_name'],
            'paged' => $_SESSION['paged'],
        );
    }else {
        $args = array(
            'post_type' => 'photo',
            'posts_per_page' => 6, // Même nombre que dans la requête initiale
            'paged' => $_SESSION['paged'],
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
                <?php if ( has_post_thumbnail() ){
                    the_post_thumbnail();
                }?>
            </a>		
        <?php endwhile;
        wp_reset_postdata();
    else :
        wp_send_json(false); // Si pas d'articles à charger, on renvoie false
    endif;

    wp_die(); // Terminer la requête AJAX correctement
}

//======================================================================
// Fonction Select Categorie
//======================================================================
function select_categorie() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_POST['categorieId'])) {
        wp_send_json_error('ID de catégorie manquant');
    }

    $_SESSION['categorie_id'] = intval($_POST['categorieId']);
    $term = get_term($_SESSION['categorie_id'], 'categorie');
    $_SESSION['categorie_name'] = $term->name; // Met à jour la variable globale

    if ($_SESSION['categorie_id'] === 0) {
        $args = array(
            'post_type' => 'photo',
            'posts_per_page' => 6,
        );
    } else {        
        $args = array(
            'post_type' => 'photo',
            'posts_per_page' => 6,
            'categorie' => $_SESSION['categorie_name'], // Utilise le nom de la catégorie
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
                <?php if ( has_post_thumbnail() ): ?>
                    <?php the_post_thumbnail(); ?>
                <?php endif; ?>
            </a>		
        <?php endwhile;
        wp_reset_postdata();
    else :
        wp_send_json(false); // Si pas d'articles à charger, on renvoie false
    endif;
    
    wp_die(); // Terminer la requête AJAX correctement
} 

//======================================================================
// Fonction Select Format
//======================================================================
function select_format() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_POST['formatId'])) {
        wp_send_json_error('ID de format manquant');
    }

    $_SESSION['format_id'] = intval($_POST['formatId']);
    $term = get_term($_SESSION['format_id'], 'format');
    $_SESSION['format_name'] = $term->name; // Met à jour la variable globale

    if ($_SESSION['format_id'] === 0) {
        $args = array(
            'post_type' => 'photo',
            'posts_per_page' => 6,
        );
    } else {        
        $args = array(
            'post_type' => 'photo',
            'posts_per_page' => 6,
            'format' => $_SESSION['format_name'], // Utilise le nom de la catégorie
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
                <?php if ( has_post_thumbnail() ): ?>
                    <?php the_post_thumbnail(); ?>
                <?php endif; ?>
            </a>		
        <?php endwhile;
        wp_reset_postdata();
    else :
        wp_send_json(false); // Si pas d'articles à charger, on renvoie false
    endif;
    
    wp_die(); // Terminer la requête AJAX correctement
}

//======================================================================
// Fonction Select Format
//======================================================================
function select_filter() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_POST['filterName'])) {
        wp_send_json_error('nom du filtre manquant');
    }

    $_SESSION['filter_name'] = sanitize_text_field($_POST['filterName']);
    
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 6,
        'order' => $_SESSION['filter_name'], // Utilise le nom de la catégorie
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
                <?php if ( has_post_thumbnail() ): ?>
                    <?php the_post_thumbnail(); ?>
                <?php endif; ?>
            </a>		
        <?php endwhile;
        wp_reset_postdata();
    else :
        wp_send_json(false); // Si pas d'articles à charger, on renvoie false
    endif;
    
    wp_die(); // Terminer la requête AJAX correctement
} 

//======================================================================
// Hook & Action
//======================================================================

add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );

// Demarrer les variable de session
add_action('init', 'start_session', 1);

//Chargement des script et style
add_action('wp_enqueue_scripts', 'enqueue_theme_styles');

// Mise en place du menu
add_action( 'init', 'register_my_menus' );

// Logo Personaliser
add_action('after_setup_theme', 'theme_setup');

//Chargement Ajax Fonction Load More
add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');

//Chargement Ajax Fonction Select Categorie
add_action('wp_ajax_select_categorie', 'select_categorie');
add_action('wp_ajax_nopriv_select_categorie', 'select_categorie');

//Chargement Ajax Fonction Select Format
add_action('wp_ajax_select_format', 'select_format');
add_action('wp_ajax_nopriv_select_format', 'select_format');

//Chargement Ajax Fonction Select Trie
add_action('wp_ajax_select_filter', 'select_filter');
add_action('wp_ajax_nopriv_select_filter', 'select_filter');