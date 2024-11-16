<?php get_header();

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
        
            <?php
                $args1 = array(
                    'post_type' => 'photo',
                );
                $query = new WP_Query($args1);
                if ($query->have_posts()) :
                    $categories =  get_terms('categorie');
                    ?>
                    <form>
                        <ul class="select">
                            <li>
                                <input class="select_close" type="radio" name="awesomeness" id="awesomeness-close" value=""/>
                                <span class="select_label select_label-placeholder">Catégorie</span>
                            </li>
                            <li class="select_items">
                                <input class="select_expand" type="radio" name="awesomeness" id="awesomeness-opener"/>
                                <label class="select_closeLabel" for="awesomeness-close"></label>
                                <ul class="select_options">
                            <?php
                                foreach($categories as $category) {
                                    echo '<li class="select_option">';
                                    echo '<input class="select_input" type="radio" name="awesomeness" id="awesomeness-' . esc_html($category->name) . '"/>';
                                    echo '<label class="select_label" for="awesomeness-' . esc_html($category->name) . '">' . esc_html($category->name) . '</label>';
                                    echo '</li>';
                                }
                            ?>
                                </ul>
                                <label class="select_expandLabel" for="awesomeness-opener"></label>
                            </li>
                        </ul>
                    </form>
                    <?php
                endif;
                wp_reset_postdata()
            ?>
        
        <div class="blocListPhoto">
            <?php
                $args2 = array(
                    'post_type' => 'photo',
                    'posts_per_page' => 6, // Nombre d'articles affichés au chargement initial
                );
                $query = new WP_Query($args2);
                
                if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>
                    <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
                        <?php if ( has_post_thumbnail() ): ?>
                            <?php the_post_thumbnail(); ?>
                        <?php endif; ?>
                    </a>		
                <?php endwhile;
                wp_reset_postdata();
            ?>
        </div>
        <?php endif; ?>
        <button id="load-more" data-page="1" data-url="<?php echo admin_url('admin-ajax.php'); ?>">Charger plus</button>
    </section>
</div>


<?php endwhile; endif; ?>   
<?php get_footer(); ?>