<?php get_header();

if( have_posts() ) : while( have_posts() ) : the_post();

// Récupérer l'article suivant
$next_post = get_next_post();

// Récupérer l'article precedent
$previous_post = get_previous_post();

if ($next_post) {// Vérifie s'il existe un article suivant
    $next_post_url = get_permalink($next_post->ID); // Obtenir l'URL de l'article suivant
    $next_post_title = get_the_title($next_post->ID); // Obtenir le titre de l'article suivant
    $next_post_image = get_the_post_thumbnail($next_post->ID, 'thumbnail'); // Récupérer la miniature de l'article suivant
}

if ($previous_post) { // Vérifie s'il existe un article précédent
    $previous_post_url = get_permalink($previous_post->ID); // Obtenir l'URL de l'article précédent
    $previous_post_title = get_the_title($previous_post->ID); // Obtenir le titre de l'article précédent
    $previous_post_image = get_the_post_thumbnail($previous_post->ID, 'thumbnail'); // Récupérer la miniature de l'article précédent
}

	$categorie_post = get_the_terms( get_the_ID() , 'categorie');
	$categorie_name = join(', ', wp_list_pluck($categorie_post, 'name'));
?>

<div class="container">
	<section id="photo">
		<div class="info">
			<h1><?php the_title(); ?></h1>
			<?php if( get_field('references') ): ?>
				<p>Références : <?php the_field('references'); ?></p>
			<?php endif; ?>
			<p><?php the_terms( get_the_ID() , 'categorie', 'Catégorie :', ', ', '' ); ?></p>
			<p><?php the_terms( get_the_ID() , 'format', 'Formats :', ', ', '' ); ?></p>
			<?php if( get_field('type') ): ?>
				<p>Types : <?php the_field('type'); ?></p>
			<?php endif; ?>
			<p>Année : <?php echo get_the_date('Y'); ?></p>
		</div>
		<div class="image">
			<?php if ( has_post_thumbnail() ): ?>
				<?php the_post_thumbnail(); ?>
			<?php endif; ?>
		</div>
	</section>
	<section id="actionPhoto">
		<div class="callToAction">
			<p>Cette photo vous interessée ?</p>
			<button onclick="openContact('<?php the_field('references'); ?>')">Contact</button>
		</div>
		<div class="navPhoto">
			<div class="hoverImage">
				<?php echo $previous_post_image; // Afficher l'image de l'article précedentes ?>
				<?php echo $next_post_image; // Afficher l'image de l'article suivant ?>
			</div>
			<div class="nextPrev">
				<?php if ($previous_post): ?>
					<a href="<?php echo esc_url($previous_post_url); ?>" title="<?php echo esc_attr($previous_post_title); ?>">
						<img src="<?php echo get_template_directory_uri() ?>/assets/images/icons/arrowLeft.png" alt="fleche gauche">
					</a>
				<?php endif; ?>
				<?php if ($next_post): ?>
					<a href="<?php echo esc_url($next_post_url); ?>" title="<?php echo esc_attr($next_post_title); ?>">
						<img src="<?php echo get_template_directory_uri() ?>/assets/images/icons/arrowRight.png" alt="fleche droite">
					</a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<section id="similarPhoto">
		<h2>Vous aimerez aussi</h2>
		<div class="blocSimilarPhoto">
			<?php 
			$args = array(
				'post_type' => 'photo',
				'categorie' => $categorie_name,
				'posts_per_page' => 2,
			);
			$my_query = new WP_Query( $args );

			

			if( $my_query->have_posts() ) : while( $my_query->have_posts() ) : $my_query->the_post();
			?>
			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
				<?php if ( has_post_thumbnail() ): ?>
					<?php the_post_thumbnail(); ?>
				<?php endif; ?>
			</a>			

			<?php
			endwhile; endif;

			wp_reset_postdata();
			?>
		</div>
	</section>
</div>

<?php endwhile; endif; ?>
<?php get_footer(); ?>