<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header>
    <!-- Logo du Site ou Texte -->
    <?php
    if (has_custom_logo()) {
        the_custom_logo();
    } else {
        echo '<a href="' . esc_url(home_url('/')) . '" rel="home">' . get_bloginfo('name') . '</a>';
    }
    ?>

    <!-- Menu de navigation -->
    <div id="menu">
        <div class="menu-burger">
            <span class="material-symbols-outlined">menu</span>
        </div>
        <nav>
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
            ));
            ?>
        </nav>
    </div>
    </header>

    <main>
    <?php wp_body_open(); ?>
