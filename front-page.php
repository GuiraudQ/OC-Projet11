<?php get_header();

unset($_SESSION['paged']);
unset($_SESSION['categorie_id']);
unset($_SESSION['categorie_name']);
unset($_SESSION['format_id']);
unset($_SESSION['format_name']);
unset($_SESSION['filter_name']);

if( have_posts() ) : while( have_posts() ) : the_post();

$title = get_the_title();
?>

<?php 
$args = array(
    'post_type' => 'photo',
    'orderby'        => 'rand',
    'posts_per_page' => 1,
);
$my_query = new WP_Query( $args );

if( $my_query->have_posts() ) : 
    $my_query->the_post();
    if ( has_post_thumbnail() ) : 
        $img = get_the_post_thumbnail_url();

    endif;
endif;
wp_reset_postdata();
?>

<div class="heroHeader" style="background-image: url(<?php echo($img) ?>)">
    <h1><?php echo $title ?></h1>
</div>


<div class="container">
    <section id="listPhoto">
        <div class="filter">
            <?php
                $args1 = array(
                    'post_type' => 'photo',
                );
                $query = new WP_Query($args1);
                if ($query->have_posts()) :
                    $categories =  get_terms('categorie');
                    $formats =  get_terms('format');
                    ?>
                    <div id="select-cat" class="custom-select">
                        <input type="hidden" id="input-cat" class="hiddenInput" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
                        <div id="default-cat" class="custom-default">
                            CATÉGORIE <span class="material-symbols-outlined">keyboard_arrow_down</span>
                        </div>
                        <ul id="options-cat" class="custom-options">
                        <?php
                                foreach($categories as $category) {
                                    echo '<li data-value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</li>';
                                }
                            ?>
                        </ul>
                    </div>
                    <div id="select-format" class="custom-select">
                        <input type="hidden" id="input-format" class="hiddenInput" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
                        <div id="default-format" class="custom-default">
                            FORMATS <span class="material-symbols-outlined">keyboard_arrow_down</span>
                        </div>
                        <ul id="options-format" class="custom-options">
                        <?php
                                foreach($formats as $format) {
                                    echo '<li data-value="' . esc_attr($format->term_id) . '">' . esc_html($format->name) . '</li>';
                                }
                            ?>
                        </ul>
                    </div>
                    <div id="select-filter" class="custom-select">
                        <input type="hidden" id="input-filter" class="hiddenInput" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
                        <div id="default-filter" class="custom-default">
                            TRIER PAR <span class="material-symbols-outlined">keyboard_arrow_down</span>
                        </div>
                        <ul id="options-filter" class="custom-options">
                            <li data-value="DESC" >A partir des plus récentes</li>
                            <li data-value="ASC" >A partir des plus anciennes</li>
                        </ul>
                    </div>
                    <?php
                endif;
                wp_reset_postdata();
            ?>
        </div>
        <div class="blocListPhoto">
            <?php
                $args2 = array(
                    'post_type' => 'photo',
                    'posts_per_page' => 6, // Nombre d'articles affichés au chargement initial
                );
                $query = new WP_Query($args2);

                
                
                if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

                $categorie_post = get_the_terms( get_the_ID() , 'categorie');
                $categorie_name = join(', ', wp_list_pluck($categorie_post, 'name'));

                ?>
                    <div class="image">
                        <?php if ( has_post_thumbnail() ): ?>
                            <a>
                                <?php the_post_thumbnail(); ?>
                            </a>
                        <?php endif; ?>
                        <div class="imgHover">
                            <div class="fullScreenIcon" onclick="openLightbox('<?php echo get_the_ID();?>', '<?php echo admin_url('admin-ajax.php');?>')">
                                <span class="material-symbols-outlined">fullscreen</span>
                            </div>
                            <div class="eyeIcon">
                                <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
                                    <span class="material-symbols-outlined">visibility</span>
                                </a>
                            </div>
                            <div class="imgInfo">
                                <p><?php the_field('references'); ?></p>
                                <p><?php echo($categorie_name) ?></p>
                            </div>
                        </div>
                    </div> 
                <?php endwhile;
                wp_reset_postdata();
            ?>
        </div>
        <?php endif; ?>
        <button id="load-more" data-page="1" data-url="<?php echo admin_url('admin-ajax.php'); ?>">Charger plus</button>
    </section>
</div>

<div id="lightbox">
    <p>Veuillez patienter</p>
</div>

<?php endwhile; endif; ?>   
<?php get_footer(); ?>