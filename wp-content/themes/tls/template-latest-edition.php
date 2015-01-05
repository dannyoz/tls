<?php
/**
 * Template Name: Latest Edition Template
 *
 * @package tls
 */

get_header();

// Latest Edition WP_Query arguments
$latest_edition_args = array(
	'post_type'			=> 'tls_editions',
	'posts_per_page'	=> 1,
	'order_by'			=> 'date',
	'order'				=> 'DESC'
);
// Latest Edition new WP_Query
$latest_edition = new WP_Query($latest_edition_args);
?>

<section id="latest-edition">
	<?php
	// Latest Edition Loop
	if ( $latest_edition->have_posts() ) :
		while ( $latest_edition->have_posts() ) : $latest_edition->the_post();
	?>
		<h1><?php the_title(); ?></h1>

	<?php
		endwhile; // End while loop
	endif; // End if have_posts()
	?>
</section>
<?php get_footer(); ?>