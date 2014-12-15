<?php

if ( ! function_exists( 'tls_taxonomies' ) ) {

/**
 * TLS Custom Taxonomies
 */
function tls_taxonomies() {

	/**
	 * Tags Taxonomy
	 *
	 * This will remove the tags taxonomy from Wordpress native post type
	 * and will add it to the tls_articles only
	 */
	$tags_args = array(
		'show_admin_column'          => true,
	);
    register_taxonomy( 'post_tag', array( 'tls_articles' ), $tags_args );

	/**
	 * Article Section Taxonomy
	 */
	$article_section_labels = array(
		'name'                       => _x( 'Article Sections', 'Taxonomy General Name', 'tls' ),
		'singular_name'              => _x( 'Article Section', 'Taxonomy Singular Name', 'tls' ),
		'menu_name'                  => __( 'Article Section', 'tls' ),
		'all_items'                  => __( 'All Article Sections', 'tls' ),
		'parent_item'                => __( 'Parent Article Section', 'tls' ),
		'parent_item_colon'          => __( 'Parent Article Section:', 'tls' ),
		'new_item_name'              => __( 'New Article Section Name', 'tls' ),
		'add_new_item'               => __( 'Add Article Section', 'tls' ),
		'edit_item'                  => __( 'Edit Article Section', 'tls' ),
		'update_item'                => __( 'Update Article Section', 'tls' ),
		'separate_items_with_commas' => __( 'Separate Article Sections with commas', 'tls' ),
		'search_items'               => __( 'Search Article Sections', 'tls' ),
		'add_or_remove_items'        => __( 'Add or remove Article Sections', 'tls' ),
		'choose_from_most_used'      => __( 'Choose from the most used Article Sections', 'tls' ),
		'not_found'                  => __( 'Not Found', 'tls' ),
	);
	$article_section_args = array(
		'labels'                     => $article_section_labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'article-section', array('tls_articles'), $article_section_args );


	/**
	 * Article Visibility Taxonomy
	 */
	$article_visibility_labels = array(
		'name'                       => _x( 'Article Visibility', 'Taxonomy General Name', 'tls' ),
		'singular_name'              => _x( 'Article Visibility', 'Taxonomy Singular Name', 'tls' ),
		'menu_name'                  => __( 'Article Visibility', 'tls' ),
		'all_items'                  => __( 'All Article Visibility', 'tls' ),
		'parent_item'                => __( 'Parent Article Visibility', 'tls' ),
		'parent_item_colon'          => __( 'Parent Article Visibility:', 'tls' ),
		'new_item_name'              => __( 'New Article Visibility Name', 'tls' ),
		'add_new_item'               => __( 'Add Article Visibility', 'tls' ),
		'edit_item'                  => __( 'Edit Article Visibility', 'tls' ),
		'update_item'                => __( 'Update Article Visibility', 'tls' ),
		'separate_items_with_commas' => __( 'Separate Article Visibility with commas', 'tls' ),
		'search_items'               => __( 'Search Article Visibility', 'tls' ),
		'add_or_remove_items'        => __( 'Add or remove Article Visibility', 'tls' ),
		'choose_from_most_used'      => __( 'Choose from the most used Article Visibility', 'tls' ),
		'not_found'                  => __( 'Not Found', 'tls' ),
	);
	$article_visibility_args = array(
		'labels'                     => $article_visibility_labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'article-visibility', array('tls_articles'), $article_visibility_args );

}

// init action hook to add tls taxonomies
add_action( 'init', 'tls_taxonomies', 0 );

} // end tls_taxonomies()