<?php
/**
 * tls functions and definitions
 *
 * @package tls
 */


/**
 * Add Custom Post Types
 */
require_once get_template_directory() . '/inc/tls-custom-post-types.php';

/**
 * Add Custom Taxonomies
 */
require_once get_template_directory() . '/inc/tls-custom-taxonomies.php';

/**
 *  Add Theme Options Page
 */
require_once get_template_directory() . '/inc/tls-theme-options.php';

/**
 * PuSHSubscriberWP - TLS Custom PubSubHubbub Integration
 */
require_once get_template_directory() . '/inc/push_subscriber_wp/PuSHSubscriberWP.php';
$PuSHSubscriberWP = new PuSHSubscriberWP;


/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function tls_setup() {

	// launching operation cleanup
	add_action( 'init', 'tls_head_cleanup' );
	// remove WP version from RSS
	add_filter( 'the_generator', 'tls_rss_version' );

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
		'footer' => __( 'Footer Menu', 'tls' ),
	) );

	// Added by Dan to enable editor stylesheet
	add_editor_style();

}
add_action( 'after_setup_theme', 'tls_setup' );



/**********************
WP_HEAD GOODNESS
The default WordPress head is
a mess. Let's clean it up.

Thanks for Bones
http://themble.com/bones/
**********************/

function tls_head_cleanup() {
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// index link
	remove_action( 'wp_head', 'index_rel_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
  	// remove WP version from css
  	add_filter( 'style_loader_src', 'tls_remove_wp_ver_css_js', 9999 );
  	// remove Wp version from scripts
  	add_filter( 'script_loader_src', 'tls_remove_wp_ver_css_js', 9999 );

} /* end head cleanup */

// remove WP version from RSS
function tls_rss_version() { return ''; }

// remove WP version from scripts
function tls_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}

/**
 * Enqueue scripts and styles.
 */
// function tls_scripts_and_styles() {

// }
// add_action( 'wp_enqueue_scripts', 'tls_scripts_and_styles' );
