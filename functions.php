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
// Fonctions de factorisation
//======================================================================
function loadArgs(){
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 6, // Nombre d'articles par page
    );

    if ($_SESSION['paged']) {
        $args['paged'] = $_SESSION['paged'];
    }
    if ($_SESSION['categorie_id']) {
        $term = get_term($_SESSION['categorie_id'], 'categorie');

        $args['categorie'] = $term->name;
    }
    if ($_SESSION['format_id']) {
        $term = get_term($_SESSION['format_id'], 'format');

        $args['format'] = $term->name;
    }
    if ($_SESSION['filter_name']) {
        $args['order'] = $_SESSION['filter_name'];
    }

    return $args;
}
function blocList($link, $title, $image, $the_Id, $the_Ref, $the_Cat) {
    ob_start(); // Démarre la mise en mémoire tampon
    ?>
    <div class="image">
        <?php if (!empty($image)): ?>
            <a href="<?php echo $link; ?>" title="<?php echo $title; ?>">
                <?php echo $image; ?>
            </a>
        <?php endif; ?>
        <div class="imgHover">
            <div class="fullScreenIcon" onclick="openLightbox('<?php echo $the_Id; ?>', '<?php echo admin_url('admin-ajax.php'); ?>')">
                <span class="material-symbols-outlined">fullscreen</span>
            </div>
            <div class="eyeIcon">
                <a href="<?php echo $link; ?>" title="<?php echo $title; ?>">
                    <span class="material-symbols-outlined">visibility</span>
                </a>
            </div>
            <div class="imgInfo">
                <p><?php echo htmlspecialchars($the_Ref); ?></p>
                <p><?php echo htmlspecialchars($the_Cat); ?></p>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean(); // Récupère et retourne le contenu de la mémoire tampon
}
function blocFalse(){
    $myHtml = 'Il n\'y a pas d\'article a charger modifier vos filtres';
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

    $args = loadArgs();

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            $categorie_post = get_the_terms( get_the_ID() , 'categorie');
            $categorie_name = join(', ', wp_list_pluck($categorie_post, 'name'));

            echo blocList(get_the_permalink(), get_the_title(), get_the_post_thumbnail(), get_the_ID(), get_field('references'), $categorie_name);

        endwhile;
        wp_reset_postdata();
    else :
        wp_send_json(blocFalse()); // Si pas d'articles à charger, on renvoie false
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
    unset($_SESSION['paged']);
    $_SESSION['categorie_id'] = intval($_POST['categorieId']);

    $args = loadArgs();

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
        $categorie_post = get_the_terms( get_the_ID() , 'categorie');
        $categorie_name = join(', ', wp_list_pluck($categorie_post, 'name'));

        echo blocList(get_the_permalink(), get_the_title(), get_the_post_thumbnail(), get_the_ID(), get_field('references'), $categorie_name);
        endwhile;
        wp_reset_postdata();
    else :
        wp_send_json(blocFalse()); // Si pas d'articles à charger, on renvoie false
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

    unset($_SESSION['paged']);
    $_SESSION['format_id'] = intval($_POST['formatId']);

    $args = loadArgs();

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
        $categorie_post = get_the_terms( get_the_ID() , 'categorie');
        $categorie_name = join(', ', wp_list_pluck($categorie_post, 'name'));

        echo blocList(get_the_permalink(), get_the_title(), get_the_post_thumbnail(), get_the_ID(), get_field('references'), $categorie_name);
        endwhile;
        wp_reset_postdata();
    else :
        wp_send_json(blocFalse()); // Si pas d'articles à charger, on renvoie false
    endif;
    
    wp_die(); // Terminer la requête AJAX correctement
}

//======================================================================
// Fonction Select Filter
//======================================================================
function select_filter() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_POST['filterName'])) {
        wp_send_json_error('nom du filtre manquant');
    }

    unset($_SESSION['paged']);
    $_SESSION['filter_name'] = sanitize_text_field($_POST['filterName']);

    $args = loadArgs();

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); 
        $categorie_post = get_the_terms( get_the_ID() , 'categorie');
        $categorie_name = join(', ', wp_list_pluck($categorie_post, 'name'));

        echo blocList(get_the_permalink(), get_the_title(), get_the_post_thumbnail(), get_the_ID(), get_field('references'), $categorie_name);
        endwhile;
        wp_reset_postdata();
    else :
        wp_send_json(blocFalse()); // Si pas d'articles à charger, on renvoie false
    endif;
    
    wp_die(); // Terminer la requête AJAX correctement
}


//======================================================================
// Fonction Lightbox Info
//======================================================================
function lightbox_info(){
    global $post;
    if (!isset($_POST['postId'])) {
        wp_send_json_error('ID du post manquant.');
    }

    $post_id = intval($_POST['postId']); // Nettoyer l'ID

    if (!$post_id) {
        wp_send_json_error('ID du post invalide.');
    }

    $post = get_post($post_id);

    
    if (!$post) {
        wp_send_json_error('Post introuvable ou type de post incorrect.');
    }

    $references = get_field('references', $post_id); // Nom du champ ACF
    
    if (!$references) {
        $references = 'Aucune référence trouvée.';
    }

    $terms = wp_get_post_terms($post_id, 'categorie');
    $categories = [];
    if (!is_wp_error($terms) && !empty($terms)) {
        foreach ($terms as $term) {
            $categories[] = $term->name; // Ajoute le nom des catégories à un tableau
        }
    }

    // Récupérer l'article suivant
    if ($_SESSION['categorie_id']) {
        $previous_post = get_previous_post(true,'','categorie');
        $next_post = get_next_post(true,'','categorie');
    }else {
        $next_post = get_next_post();
        $previous_post = get_previous_post();
    }

    // Récupérer l'URL de l'image mise en avant
    $thumbnail_url = get_the_post_thumbnail_url($post_id, 'full'); // Taille 'full'
    if (!$thumbnail_url) {
        $thumbnail_url = 'URL de l’image non disponible.';
    }?>
        <?php if ($previous_post): ?>
            <div>
                <p onclick="openLightbox('<?php echo($previous_post->ID); ?>', '<?php echo admin_url('admin-ajax.php');?>')"><span class="material-symbols-outlined bigIcon">arrow_left_alt</span></p>
            </div>
		<?php endif; ?>
        <div class="lightbox_img">
            <?php echo(get_the_post_thumbnail($post_id)); ?>
            <div class="lightbox_img_data">
                <p><?php echo($references); ?></p>
                <p><?php echo($categories[0]); ?></p>
            </div>
        </div>
        <?php if ($next_post): ?>
        <div>
            <p onclick="openLightbox('<?php echo($next_post->ID); ?>', '<?php echo admin_url('admin-ajax.php');?>')"><span class="material-symbols-outlined bigIcon">arrow_right_alt</span></p>
        </div>
        <?php endif; ?>
        <div class="closeLightbox" onclick="closeLightbox()">
            <span class="material-symbols-outlined">close</span>
        </div>
    <?php

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

//Chargement Ajax Fonction Lightbox Info
add_action('wp_ajax_lightbox_info', 'lightbox_info');
add_action('wp_ajax_nopriv_lightbox_info', 'lightbox_info');