<?php
/**
 * tls functions and definitions
 *
 * @package tls
 */

//ini_set('max_execution_time', 300); //300 seconds = 5 minutes

if(function_exists('xdebug_disable')) { xdebug_disable(); }

/**
 * Define Constants
 */

define( 'TLS_TEMPLATE_DIR', get_template_directory() );
define( 'TLS_THEME_URI', get_stylesheet_directory_uri() );

/**
 * Include Plugin Absolute Path
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Add Custom Post Types
 */
require_once TLS_TEMPLATE_DIR . '/inc/tls-custom-post-types.php';

/**
 * Add Taxonomies
 */
require_once TLS_TEMPLATE_DIR . '/inc/tls-taxonomies.php';

/**
 *  Add Theme Options Page
 */
require_once TLS_TEMPLATE_DIR . '/inc/tls-theme-options.php';

/**
 * PuSHSubscriberWP - TLS Custom PubSubHubbub Integration
 */
require_once TLS_TEMPLATE_DIR . '/inc/push_subscriber_wp/vendor/autoload.php'; // Load Composer Auto load file
$PuSHSubscriberWP = new PuSHSubscriberWP\PuSHSubscriberWP;


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
	load_theme_textdomain( 'tls', TLS_TEMPLATE_DIR . '/languages' );

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
 function tls_scripts_and_styles() {

	 // Enqueue Styles
	 wp_enqueue_style( 'tls-styles', TLS_THEME_URI . '/style.css', array(), '', 'all' );

	 // Enqueue Scripts
	 wp_enqueue_script( 'tls-typekit', '//use.typekit.net/zvh7bpe.js', array(), '', false );
	 wp_enqueue_script( 'tls-scripts', TLS_THEME_URI . '/js/main.min.js', array(), '', true);

 }
 add_action( 'wp_enqueue_scripts', 'tls_scripts_and_styles' );


if (is_plugin_active('json-api/json-api.php')) {

	include_once TLS_TEMPLATE_DIR . '/inc/tls_json_api_encode.php';

}
 
/**
 * tls_remove_page_from_search	Remove Page Post Type from Search
 */
function tls_remove_page_from_search() {
	global $wp_post_types;
 
	if ( post_type_exists( 'page' ) ) {
		// exclude from search results
		$wp_post_types['page']->exclude_from_search = true;
	}
}
add_action( 'init', 'tls_remove_page_from_search', 99 );

/**
 * Remove Default Post Tags Taxonomy from WordPress
 */
function tls_unregister_post_tag_taxonomy()
{
	global $wp_taxonomies;
	$taxonomy = 'post_tag';
	if ( taxonomy_exists($taxonomy) )
		unset( $wp_taxonomies[$taxonomy] );
}
add_action( 'init', 'tls_unregister_post_tag_taxonomy' );

/**
 * Modify Permalink for Articles Post Type
 */
//add_filter('post_link', 'article_visibility_modify_permalink', 10, 3);
//add_filter('post_type_link', 'article_visibility_modify_permalink', 10, 3);
//
//function article_visibility_modify_permalink($permalink, $post_id, $leavename) {
//	if (strpos($permalink, '%article_visibility%') === FALSE) return $permalink;
//
//	// Get post
//	$post = get_post($post_id);
//	if (!$post) return $permalink;
//
//	// Get taxonomy terms
//	$terms = wp_get_object_terms($post->ID, 'article_visibility');
//	if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) {
//		$taxonomy_slug = $terms[0]->slug;
//	} else {
//		$taxonomy_slug = 'public';
//	}
//
//	return str_replace('%article_visibility%', $taxonomy_slug, $permalink);
//}