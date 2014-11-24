<?php
/**
 * tls functions and definitions
 *
 * @package tls
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function tls_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on tls, use a find and replace
	 * to change 'tls' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'tls', get_template_directory() . '/languages' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'tls' ),
	) );

}
add_action( 'after_setup_theme', 'tls_setup' );

/**
 * Add Custom Post Types
 */
require 'inc/tls-custom-post-types.php';

/**
 * Enqueue scripts and styles.
 */
function tls_scripts() {
	// wp_enqueue_scripts();
}
add_action( 'wp_enqueue_scripts', 'tls_scripts' );