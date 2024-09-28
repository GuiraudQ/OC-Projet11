<?php

// Ajouter la prise en charge des images mises en avant
add_theme_support( 'post-thumbnails' );

// Ajouter automatiquement le titre du site dans l'en-tête du site
add_theme_support( 'title-tag' );

function enqueue_theme_styles() {
    //Reset CSS
    wp_enqueue_style('reset-css', 'https://cdn.jsdelivr.net/gh/jgthms/minireset.css@master/minireset.min.css', array(), '8.0.1');

    // Enqueue App perso
    wp_enqueue_script('app', get_stylesheet_directory_uri() . '/js/app.js', array(), '1.0.0', true, filemtime(get_stylesheet_directory() . '/js/app.js'));
  
    // Chemin vers le fichier CSS
    $theme_version = wp_get_theme()->get('Version');
    wp_enqueue_style('theme-styles', get_template_directory_uri() . '/assets/style/theme.css', array(), $theme_version);

    if( is_single() ) {
        wp_enqueue_style('single-styles', get_template_directory_uri() . '/assets/style/single-photo.css', array(), $theme_version);
        wp_enqueue_script('single-script', get_template_directory_uri() . '/js/single-photo.js', array(), $theme_version);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_theme_styles');

function register_my_menus() {
    register_nav_menus(
        array(
            'primary' => __( 'Primary Menu' ),
            'footer' => __( 'Footer Menu' )
        )
    );
}
add_action( 'init', 'register_my_menus' );

function theme_setup() {
    // Support du logo personnalisé
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'theme_setup');