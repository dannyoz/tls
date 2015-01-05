<?php
/**
 * Template Name: Articles Archive Template
 *
 * @package tls
 */

get_header();

// Articles Archive WP_Query arguments
$articles_archive_args = array(
	'post_type'		=> 'tls_articles',
	'orderby'		=> 'date',
	'order'			=> 'DESC'
);
// Articles Archive new WP_Query
$articles_archive = new WP_Query($articles_archive_args);
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>


			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				get_template_part( 'content', 'article-archive' );
				?>

			<?php endwhile; ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
