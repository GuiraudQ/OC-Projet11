<?php

add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );

add_action('init', 'start_session', 1);
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
$paged = 0;

function loadArgs($category_id = null, $paged = 1){
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 6, // Nombre d'articles par page
        'paged' => $paged, // Page actuelle pour la pagination
    );

    if ($category_id) {
        $args['categorie__in'] = $category_id;
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

    if (isset($_POST['page'])) {
        // Stocker l'ID de la catégorie dans la session
        $_SESSION['paged'] = intval($_POST['page']);
        
        wp_send_json_success('Catégorie sélectionnée : ' . $_SESSION['category_id'] . 'Page : ' . $_SESSION['paged']);
    } else {
        wp_send_json_error('ID de catégorie manquant');
    }

    wp_die(); // Fin de la requête AJAX
}

//======================================================================
// Fonction Select
//======================================================================
function select_categorie() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_POST['categorieId'])) {
        // Stocker l'ID de la catégorie dans la session
        $_SESSION['category_id'] = intval($_POST['categorieId']);
        
        wp_send_json_success('Catégorie sélectionnée : ' . $_SESSION['category_id'] . 'Page : ' . $_SESSION['paged']);
    } else {
        wp_send_json_error('ID de catégorie manquant');
    }

    wp_die(); // Fin de la requête AJAX
} 



//======================================================================
// Hook & Action
//======================================================================

//Chargement des script et style
add_action('wp_enqueue_scripts', 'enqueue_theme_styles');

// Mise en place du menu
add_action( 'init', 'register_my_menus' );

// Logo Personaliser
add_action('after_setup_theme', 'theme_setup');

//Chargement Ajax Fonction Load More
add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');

//Chargement Ajax Fonction Select
add_action('wp_ajax_select_categorie', 'select_categorie');
add_action('wp_ajax_nopriv_select_categorie', 'select_categorie');