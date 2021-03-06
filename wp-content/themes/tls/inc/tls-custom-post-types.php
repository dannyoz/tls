<?php

if (!function_exists('tls_custom_post_types')) {

    function tls_custom_post_types()
    {

        /**
         *  Editions Post Type
         */
        // Edition Post Type Labels
        $ed_labels = array(
            'name' => _x('Editions', 'Post Type General Name', 'tls'),
            'singular_name' => _x('Edition', 'Post Type Singular Name', 'tls'),
            'menu_name' => __('Editions', 'tls'),
            'parent_item_colon' => __('Parent Edition:', 'tls'),
            'all_items' => __('All Editions', 'tls'),
            'view_item' => __('View Edition', 'tls'),
            'add_new_item' => __('Add New Edition', 'tls'),
            'add_new' => __('Add Edition', 'tls'),
            'edit_item' => __('Edit Edition', 'tls'),
            'update_item' => __('Update Edition', 'tls'),
            'search_items' => __('Search Edition', 'tls'),
            'not_found' => __('Edition Not found', 'tls'),
            'not_found_in_trash' => __('Edition Not found in Trash', 'tls'),
        );
        // Edition Post Type Rewrite Rules
        $ed_rewrite = array(
            'slug' => 'editions',
            'with_front' => true,
            'pages' => true,
            'feeds' => true,
        );
        // Edition Post Type Arguments
        $ed_args = array(
            'label' => __('tls_editions', 'tls'),
            'description' => __('TLS Editions Custom Content Post Type', 'tls'),
            'labels' => $ed_labels,
            'supports' => array('title'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-media-spreadsheet',
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => $ed_rewrite,
            'capability_type' => 'post',
        );
        // Register tls_editions Post Type
        register_post_type('tls_editions', $ed_args);


        /**
         *  Articles Post Type
         */
        // Article Post Type Labels
        $art_labels = array(
            'name' => _x('Articles', 'Post Type General Name', 'tls'),
            'singular_name' => _x('Article', 'Post Type Singular Name', 'tls'),
            'menu_name' => __('Articles', 'tls'),
            'parent_item_colon' => __('Parent Article:', 'tls'),
            'all_items' => __('All Articles', 'tls'),
            'view_item' => __('View Article', 'tls'),
            'add_new_item' => __('Add New Article', 'tls'),
            'add_new' => __('Add Article', 'tls'),
            'edit_item' => __('Edit Article', 'tls'),
            'update_item' => __('Update Article', 'tls'),
            'search_items' => __('Search Article', 'tls'),
            'not_found' => __('Article Not found', 'tls'),
            'not_found_in_trash' => __('Article Not found in Trash', 'tls'),
        );
        // Article Post Type Rewrite Rules
        $art_rewrite = array(
            'slug' => 'articles/%article_visibility%',
            'with_front' => true,
            'pages' => true,
            'feeds' => true,
        );
        // Article Post Type Arguments
        $art_args = array(
            'label' => __('tls_articles', 'tls'),
            'description' => __('TLS Articles Custom Content Post Type', 'tls'),
            'labels' => $art_labels,
            'supports' => array(
                'title',
                'editor',
                'comments'
            ),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-media-document',
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'rewrite' => $art_rewrite,
            'capability_type' => 'post',
        );
        // Register tls_articles Post Type
        register_post_type('tls_articles', $art_args);


        /**
         *  FAQs Post Type
         */
        // FAQ Post Type Labels
        $faq_labels = array(
            'name' => _x('FAQs', 'Post Type General Name', 'tls'),
            'singular_name' => _x('FAQ', 'Post Type Singular Name', 'tls'),
            'menu_name' => __('FAQs', 'tls'),
            'parent_item_colon' => __('Parent FAQ:', 'tls'),
            'all_items' => __('All FAQs', 'tls'),
            'view_item' => __('View FAQ', 'tls'),
            'add_new_item' => __('Add New FAQ', 'tls'),
            'add_new' => __('Add FAQ', 'tls'),
            'edit_item' => __('Edit FAQ', 'tls'),
            'update_item' => __('Update FAQ', 'tls'),
            'search_items' => __('Search FAQ', 'tls'),
            'not_found' => __('FAQ Not found', 'tls'),
            'not_found_in_trash' => __('FAQ Not found in Trash', 'tls'),
        );
        // FAQ Post Type Rewrite Rules
        $faq_rewrite = array(
            'slug' => 'faq',
            'with_front' => true,
            'pages' => true,
            'feeds' => true,
        );
        // FAQ Post Type Arguments
        $faq_args = array(
            'label' => __('tls_faq', 'tls'),
            'description' => __('TLS FAQs Custom Content Post Type', 'tls'),
            'labels' => $faq_labels,
            'supports' => array(
                'title',
                'editor',
                'comments',
                'page-attributes'
            ),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-editor-help',
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'rewrite' => $faq_rewrite,
            'capability_type' => 'post',
        );
        // Register tls_faq Post Type
        register_post_type('tls_faq', $faq_args);

    }

// Hook into the 'init' action
    add_action('init', 'tls_custom_post_types', 0);

} // end of tls_custom_post_types()