<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package tls
 */
?>

<article class="card" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<h3>
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
	</h3>

	<div>
		<?php the_excerpt(); ?>
	</div>

</article>
