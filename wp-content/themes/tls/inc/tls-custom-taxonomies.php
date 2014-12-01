<?php

if ( ! function_exists( 'tls_custom_taxonomies' ) ) {

// Register Custom Taxonomy
function tls_custom_taxonomies() {

	$labels = array(
		'name'                       => _x( 'Article Categories', 'Taxonomy General Name', 'tls' ),
		'singular_name'              => _x( 'Article Category', 'Taxonomy Singular Name', 'tls' ),
		'menu_name'                  => __( 'Article Category', 'tls' ),
		'all_items'                  => __( 'All Article Categories', 'tls' ),
		'parent_item'                => __( 'Parent Article Category', 'tls' ),
		'parent_item_colon'          => __( 'Parent Article Category:', 'tls' ),
		'new_item_name'              => __( 'New Article Category Name', 'tls' ),
		'add_new_item'               => __( 'Add Article Category', 'tls' ),
		'edit_item'                  => __( 'Edit Article Category', 'tls' ),
		'update_item'                => __( 'Update Article Category', 'tls' ),
		'separate_items_with_commas' => __( 'Separate Article Categories with commas', 'tls' ),
		'search_items'               => __( 'Search Article Categories', 'tls' ),
		'add_or_remove_items'        => __( 'Add or remove Article Categories', 'tls' ),
		'choose_from_most_used'      => __( 'Choose from the most used Article Categories', 'tls' ),
		'not_found'                  => __( 'Not Found', 'tls' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'article-category', array('tls_articles'), $args );

}

// Hook into the 'init' action
add_action( 'init', 'tls_custom_taxonomies', 0 );

} // end tls_custom_taxonomies()