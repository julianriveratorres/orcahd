<?php
/*
Template Name: Landing Page (Blank Canvas)
*/

add_filter( 'unos_layout', 'unosglow_layout' );
function unosglow_layout( $sidebar ){
	return apply_filters( 'unosglow_layout', 'full-width', $sidebar );
}

remove_action( 'wp_body_open', 'hootkit_topbanner_display' );

remove_action( 'wp_footer', 'hootkit_flycart_display' );

?>
<!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?>>

<head>
<?php
// Fire the wp_head action required for hooking in scripts, styles, and other <head> tags.
wp_head();
?>
</head>

<body <?php hoot_attr( 'body' ); ?>>

	<?php wp_body_open(); ?>

	<a href="#main" class="screen-reader-text"><?php esc_html_e( 'Skip to content', 'unos-glow' ); ?></a>

	<div <?php hoot_attr( 'page-wrapper' ); ?>>

		<div <?php hoot_attr( 'main' ); ?>>



<div class="hgrid main-content-grid">

	<main <?php hoot_attr( 'content' ); ?>>
		<div <?php hoot_attr( 'content-wrap', 'page-landing' ); ?>>

			<?php
			// Checks if any posts were found.
			if ( have_posts() ) :

				// Begins the loop through found posts, and load the post data.
				while ( have_posts() ) : the_post();

					// Loads the template-parts/content-{$post_type}.php template.
					hoot_get_content_template();

				// End found posts loop.
				endwhile;

			// If no posts were found.
			else :

				// Loads the template-parts/error.php template.
				get_template_part( 'template-parts/error' );

			// End check for posts.
			endif;
			?>

		</div><!-- #content-wrap -->
	</main><!-- #content -->

</div><!-- .main-content-grid -->



		</div><!-- #main -->

	</div><!-- #page-wrapper -->

	<?php wp_footer(); // WordPress hook for loading JavaScript, toolbar, and other things in the footer. ?>

</body>
</html>