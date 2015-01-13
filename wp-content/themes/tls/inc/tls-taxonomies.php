<?php

if ( ! function_exists( 'tls_taxonomies' ) ) {

/**
 * TLS Custom Taxonomies
 */
function tls_taxonomies() {

	/**
	 * Article Tags Taxonomy
	 */
	$article_tags_labels = array(
		'name'                       => _x( 'Article Tags', 'Taxonomy General Name', 'tls' ),
		'singular_name'              => _x( 'Article Tag', 'Taxonomy Singular Name', 'tls' ),
		'menu_name'                  => __( 'Article Tag', 'tls' ),
		'all_items'                  => __( 'All Article Tags', 'tls' ),
		'parent_item'                => __( 'Parent Article Tag', 'tls' ),
		'parent_item_colon'          => __( 'Parent Article Tag:', 'tls' ),
		'new_item_name'              => __( 'New Article Tag Name', 'tls' ),
		'add_new_item'               => __( 'Add Article Tag', 'tls' ),
		'edit_item'                  => __( 'Edit Article Tag', 'tls' ),
		'update_item'                => __( 'Update Article Tag', 'tls' ),
		'separate_items_with_commas' => __( 'Separate Article Tags with commas', 'tls' ),
		'search_items'               => __( 'Search Article Tags', 'tls' ),
		'add_or_remove_items'        => __( 'Add or remove Article Tags', 'tls' ),
		'choose_from_most_used'      => __( 'Choose from the most used Article Tags', 'tls' ),
		'not_found'                  => __( 'Not Found', 'tls' ),
	);
	$article_tags_args = array(
		'labels'                     => $article_tags_labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'					 => array( 'slug' => 'tag' )
	);
	register_taxonomy( 'article_tags', array('tls_articles'), $article_tags_args );

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
		'rewrite'					 => array( 'slug' => 'article-section' )
	);
	register_taxonomy( 'article_section', array('tls_articles'), $article_section_args );


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
		'query_var'					 => 'article_visibility',
		'rewrite'					 => true,
	);
	register_taxonomy( 'article_visibility', array('tls_articles'), $article_visibility_args );

	/**
	 * FAQ Category Taxonomy
	 */
	$faq_category_labels = array(
		'name'                       => _x( 'FAQ Categories', 'Taxonomy General Name', 'tls' ),
		'singular_name'              => _x( 'FAQ Category', 'Taxonomy Singular Name', 'tls' ),
		'menu_name'                  => __( 'FAQ Category', 'tls' ),
		'all_items'                  => __( 'All FAQ Categories', 'tls' ),
		'parent_item'                => __( 'Parent FAQ Category', 'tls' ),
		'parent_item_colon'          => __( 'Parent FAQ Category:', 'tls' ),
		'new_item_name'              => __( 'New FAQ Category Name', 'tls' ),
		'add_new_item'               => __( 'Add FAQ Category', 'tls' ),
		'edit_item'                  => __( 'Edit FAQ Category', 'tls' ),
		'update_item'                => __( 'Update FAQ Category', 'tls' ),
		'separate_items_with_commas' => __( 'Separate FAQ Categories with commas', 'tls' ),
		'search_items'               => __( 'Search FAQ Categories', 'tls' ),
		'add_or_remove_items'        => __( 'Add or remove FAQ Categories', 'tls' ),
		'choose_from_most_used'      => __( 'Choose from the most used FAQ Categories', 'tls' ),
		'not_found'                  => __( 'Not Found', 'tls' ),
	);
	$faq_category_args = array(
		'labels'                     => $faq_category_labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'faq_category', array('tls_faq'), $faq_category_args );

}

// init action hook to add tls taxonomies
add_action( 'init', 'tls_taxonomies', 0 );

} // end tls_taxonomies()