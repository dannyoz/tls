<?php
/**
 * tls functions and definitions
 *
 * @package tls
 */

/**
 * Define Constants
 */

define( 'TLS_TEMPLATE_DIR', get_template_directory() );
define( 'TLS_THEME_URI', get_stylesheet_directory_uri() );

/**
 * Include Composer Autoloader
 */
require_once TLS_TEMPLATE_DIR . '/vendor/autoload.php';

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
 * TLS Tealium
 */
$TlsTealium = new \Tls\TlsTealium();

/**
 *  Add Theme Options Page
 */
$TlsThemeOoptions = new Tls\ThemeOptions();

/**
 * PuSHSubscriberWP - TLS Custom PubSubHubbub Integration
 */
$TlsHubSubscriberWP = new Tls\TlsHubSubscriber\TlsHubSubscriberWP();


/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function tls_setup()
{

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
    register_nav_menus( array( 'primary' => __( 'Primary Menu', 'tls' ), 'footer' => __( 'Footer Menu', 'tls' ), ) );

    // Added by Dan to enable editor stylesheet
    add_editor_style();

}

add_action( 'after_setup_theme', 'tls_setup' );


/**********************
 * WP_HEAD GOODNESS
 * The default WordPress head is
 * a mess. Let's clean it up.
 *
 * Thanks for Bones
 * http://themble.com/bones/
 **********************/

function tls_head_cleanup()
{
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
function tls_remove_wp_ver_css_js( $src )
{
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );

    return $src;
}


/**
 * Enqueue scripts and styles.
 */
function tls_scripts_and_styles()
{
    // Enqueue Styles
    wp_enqueue_style( 'tls-styles', TLS_THEME_URI . '/style.css', array(), '', 'all' );

    // Enqueue Scripts
    wp_enqueue_script( 'tls-typekit', '//use.typekit.net/zvh7bpe.js', array(), '', false );
    wp_enqueue_script( 'tls-scripts', TLS_THEME_URI . '/js/main.min.js', array(), '', true );

    if ( !is_admin() ) {
        wp_dequeue_script( 'jquery' );
    }
}

add_action( 'wp_enqueue_scripts', 'tls_scripts_and_styles', 100 );

/**
 * JSON API Response Modifications to work with Angularjs Front End
 */
if ( is_plugin_active( 'json-api/json-api.php' ) ) {

    // Generic/Misc JSON API Modifications (for smaller changes that are not overly complicated or long)
    include_once TLS_TEMPLATE_DIR . '/inc/tls_json_api_encode.php';

    // Home Page Template JSON API Modifications
    include_once TLS_TEMPLATE_DIR . '/inc/tls_home_page_json_api_encode.php';

    // Discover Archive Page Template JSON API Modifications
    include_once TLS_TEMPLATE_DIR . '/inc/tls_discover_json_api_encode.php';

    // Blogs Archive Page Template JSON API Modifications
    include_once TLS_TEMPLATE_DIR . '/inc/tls_blogs_archive_json_api_encode.php';

    // Latest Edition Page Template JSON API Modifications
    include_once TLS_TEMPLATE_DIR . '/inc/tls_latest_edition_page_json_api_encode.php';

    // Search Results Page Template JSON API Modifications
    include_once TLS_TEMPLATE_DIR . '/inc/tls_search_results_json_api_encode.php';

}

/**
 * tls_remove_page_from_search    Remove Page Post Type from Search
 */
function tls_remove_page_from_search()
{
    global $wp_post_types;

    if ( post_type_exists( 'page' ) ) {
        // exclude from search results
        $wp_post_types[ 'page' ]->exclude_from_search = true;
    }

    if ( post_type_exists( 'attachment' ) ) {
        // exclude from search results
        $wp_post_types[ 'attachment' ]->exclude_from_search = true;
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
    if ( taxonomy_exists( $taxonomy ) )
        unset( $wp_taxonomies[ $taxonomy ] );
}

add_action( 'init', 'tls_unregister_post_tag_taxonomy' );


/**
 * Modify the Articles Visibility Permalink
 *
 * This will rewrite the %article_visibility% query var from the URL of the article and include the
 * Article visibility taxonomy term which will be private or public
 *
 * @param string $post_link The link of the Post
 * @param int    $id        The Post ID
 * @param bool   $leavename
 *
 * @return mixed                Returns the new rewritten URL permalink
 */
function tls_articles_visibility_permalink( $post_link, $id = 0, $leavename = false )
{
    if ( strpos( '%article_visibility%', $post_link ) > 0 )
        return $post_link;
    $post = get_post( $id );
    if ( !is_object( $post ) || $post->post_type != 'tls_articles' )
        return $post_link;
    $terms = wp_get_object_terms( $post->ID, 'article_visibility' );
    if ( !empty( $terms ) ) {
        $visibility_slug = $terms[ 0 ]->slug;
    }
    if ( !$terms )
        return str_replace( '%article_visibility%/', '', $post_link );

    return str_replace( '%article_visibility%', $visibility_slug, $post_link );
}

add_filter( 'post_link', 'tls_articles_visibility_permalink', 1, 3 );
add_filter( 'post_type_link', 'tls_articles_visibility_permalink', 1, 3 );


/**
 * Article Visibility Security
 *
 * This function protects the permalink so if a user changes the URL of a Private post to have public in the URL
 * from being viewed. It will get the Post Object and compare to see if the the Visibility terms exists in the Request URI
 * and if not then redirect to a 404 page
 *
 * @param $post        Post Object
 *
 * @return mixed    Returns the $post Object if URL is ok or redirect to 404 if not
 */
function tls_articles_visibility_permalink_security( $post )
{
    if ( is_single() && 'tls_articles' == $post->post_type ) {
        $visibility = wp_get_post_terms( $post->ID, 'article_visibility' );

        if ( strpos( esc_url( $_SERVER[ 'REQUEST_URI' ] ), $visibility[ 0 ]->slug ) ) {
            return $post;
        } else {
            $string = '<script type="text/javascript">';
            $string .= 'window.location = "' . home_url( '404' ) . '"';
            $string .= '</script>';

            echo $string;
            exit();
        }

    }
}

add_action( 'the_post', 'tls_articles_visibility_permalink_security' );

/**
 * Remove Content from Wordpress Search (This could not be achieved with Search Everything Plugin)
 * Adaptation from https://wordpress.org/support/topic/exclude-content-from-search-results , removed the last \) from the query because it was
 * causing an SQL error
 */
add_action( 'posts_search', 'dont_search_post_content', 10000, 1 );
function dont_search_post_content( $search_sql )
{
    if ( !is_admin() && is_search() ) {
        global $wpdb, $wp_query;
        $search_query = $wp_query->query_vars[ 's' ];
        //var_dump($wp_query);
        if ( strpos( $search_sql, 'post_content LIKE' ) ) {
            $search_sql = preg_replace( "/OR \({$wpdb->posts}.post_content LIKE '\%{$search_query}\%'\)/", '', $search_sql );
        }
    }

    return $search_sql;
}

/**
 * Custom Post Excerpt
 *
 * @param object $post        Post Object
 * @param int    $word_length The amount of words for the excerpt
 *
 * @return string                The newly made excerpt
 */
function tls_make_post_excerpt( $post, $word_length = 55 )
{

    if ( $word_length < 0 ) {
        $word_length = 55;
    }

    $text = $post->post_content;
    $text = strip_shortcodes( $text );
    $text = apply_filters( 'the_content', $text );
    $text = str_replace( ']]>', ']]>', $text );

    $excerpt_length = apply_filters( 'excerpt_length', $word_length );
    $excerpt_more = apply_filters( 'excerpt_more', '' );
    $text = wp_trim_words( $text, $excerpt_length, '' );

    return $text;
}

/**
 * Limit the content length
 * Inspired from http://bavotasan.com/2009/limiting-the-number-of-words-in-your-excerpt-or-content-in-wordpress/
 *
 * @param string $text
 * @param int    $limit
 *
 * @return string $content
 */
function tls_limit_content( $text, $limit = 50 )
{

    $content = strip_shortcodes( $text ); // Strip away any shortcodes from text
    $content = strip_tags( $content, '<p><a><strong><b><i><blockquote><br><br />' ); // strip tags, allowing only a selected few
    $content = explode( ' ', $content, $limit ); // Break down the content into a words array based on space

    // If the content is longer or equal to the limit
    if ( count( $content ) >= $limit ) {
        array_pop( $content );
        $content = implode( " ", $content ) . ' ...';
    } else {
        $content = implode( " ", $content );
    }

    $content = apply_filters( 'the_content', $content ); // Applies the filter the_content
    return $content;
}

/**
 * Function to dynamically populate the Spotlight Category Select Box Custom Field
 * Only includes the Article Section with the custom field option to include
 * in the Discover Page archive.
 *
 * @param array $field
 *
 * @return array $field
 */
function acf_load_spotlight_category_choices( $field )
{

    // reset choices
    $field[ 'choices' ] = array();

    // Get all the terms from Article Section Taxonomy
    $article_sections_args = array( 'hide_empty' => false, 'orderby' => 'name', 'order' => 'ASC', );
    $all_article_sections = get_terms( 'article_section', $article_sections_args );

    $field[ 'choices' ][ '' ] = ' -- Select the Spotlight Section -- ';

    foreach ( $all_article_sections as $article_section ) {
        $section_show_in_discover_page = get_field( 'show_in_discover_page', 'article_section_' . $article_section->term_id );

        if ( $section_show_in_discover_page == 'yes' ) {
            $field[ 'choices' ][ $article_section->term_id ] = $article_section->name;
        }
    }

    // return the field
    return $field;

}

add_filter( 'acf/load_field/name=spotlight_category', 'acf_load_spotlight_category_choices' );