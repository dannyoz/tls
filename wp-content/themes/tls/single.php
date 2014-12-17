<?php
/**
 * The template for displaying all single posts.
 *
 * @package tls
 */

get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>
				
		<?php 
		if ( locate_template( 'content-single-' . get_post_type() . '.php' ) ) {
			get_template_part( 'content-single', get_post_type() );
		} else {
			get_template_part( 'content', 'single' ); 
		}
		
		?>

	<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
