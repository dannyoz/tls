<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package tls
 */

get_header(); ?>
blog index
	<div ng-include="'<?php bloginfo('template_directory'); ?>/ng-views/home.html'"></div>
	<div ng-include="'<?php bloginfo('template_directory'); ?>/ng-views/edition-preview.html'"></div>


<?php get_footer(); ?>
