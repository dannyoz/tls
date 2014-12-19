<?php

/**
 * tls_json_api_enconde			This function serves to filter/modify the JSON Response Object of the JSON API for various pages
 * @param  object $response 	The JSON API Response Object
 * @return object $reposnse		Returns the new and filtered/modefied JSON API Response Object
 */
function tls_json_api_encode($response) {
	global $json_api, $wp_query;
	//echo $GLOBALS['wp_query']->request;

    // enable debug mode and display native json object when debug is set in the url
    if (isset($_GET['debug'])) {
        return $response;
    }

    // if empty or errpr return 404 Error along with the JSON Object containing error message and status
    if (empty($response) || $response['status'] == 'error'
    ) {
        header("HTTP/1.0 404 Not Found");
        header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
        $return = json_encode( $response );
        die( $return );
    }

    /**
     * Search Page Specific
     */
    if ( is_search() ) {
    	// Get Search Query to be used in all the queries
    	$search_query = get_search_query();

		/**
		 * Reviews Articles Tag Search Filter
		 */
    	$reviews_tag = get_term_by( 'slug', 'reviews', 'post_tag' );
    	$reviews_tag_id = (int) $reviews_tag->term_id;
    	$reviews = new WP_Query( array(  
    		'post_type'			=> 'tls_articles',
    		'post_status'		=> 'publish',
    		'posts_per_page'	=> 1,
    		's'					=> $search_query,
    		'tax_query'			=> array( array (
    			'taxonomy'		=> 'post_tag',
    			'field'			=> 'term_id',
    			'terms'			=> $reviews_tag_id
    		) )
    	) ); wp_reset_query();

    	$response['content_type_filters']['reviews'] = array(
    		'item_label'		=> __('Reviews', 'tls'),
    		'taxonomy'			=> $reviews_tag->taxonomy,
    		'slug'				=> $reviews_tag->slug,
    		'search_count'		=> (int) $reviews->found_posts
    	);

    	/**
    	 * Reviews Articles Tag Search Filter
    	 */
    	$public_visibility = get_term_by( 'slug', 'public', 'article-visibility' );
    	$public_visibility_id = (int) $public_visibility->term_id;
    	$public_articles = new WP_Query( array(  
    		'post_type'			=> 'tls_articles',
    		'post_status'		=> 'publish',
    		'posts_per_page'	=> 1,
    		's'					=> $search_query,
    		'tax_query'			=> array( array (
    			'taxonomy'		=> 'article-visibility',
    			'field'			=> 'term_id',
    			'terms'			=> $public_visibility_id
    		) )
    	) ); wp_reset_query();

    	$response['content_type_filters']['public_articles'] = array(
    		'item_label'		=> __('Free To Non Subscribers', 'tls'),
    		'taxonomy'			=> $public_visibility->taxonomy,
    		'slug'				=> $public_visibility->slug,
    		'search_count'		=> (int) $public_articles->found_posts
    	);

    	/**
    	 * TLS Blogs Category Term Search Filter Info
    	 */
    	$tls_blogs = get_term_by( 'slug', 'tls-blogs', 'category' );
    	$tls_blogs_id = (int) $tls_blogs->term_id;
    	$blogs = new WP_Query( array(  
    		'post_type'			=> 'post',
    		'post_status' 		=> 'publish',
    		'cat' 				=> $tls_blogs_id,
    		'posts_per_page' 	=> 1,
    		's'					=> $search_query
    	) ); wp_reset_query();

    	$response['content_type_filters']['blogs'] = array(
    		'item_label'		=> __('Blogs', 'tls'),
    		'taxonomy'			=> $tls_blogs->taxonomy,
    		'slug' 				=> $tls_blogs->slug,
    		'search_count' 		=> (int) $blogs->found_posts
    	);

    	/**
    	 * FAQs Post Type Search Filter
    	 */
    	$faqs = new WP_Query( array( 
    		'post_type'			=> 'tls_faq',
    		'post_status'		=> 'publish',
    		'posts_per_page'	=> 1,
    		's'					=> $search_query
    	) ); wp_reset_query();

    	$response['content_type_filters']['faqs'] = array(
    		'item_label'		=> __('FAQs', 'tls'),
    		'slug'				=> 'faqs',
    		'search_count'		=> (int) $faqs->found_posts
    	);


    	/**
    	 * Attempt to implement a date_query inside the search page
    	 */
    	if ( isset( $_GET['date_filter'] ) && $_GET['date_filter'] != '' ) {
 
 			$url = parse_url($_SERVER['REQUEST_URI']);
    		$url_query = wp_parse_args($url['query']);

			$date_posts_archive_args = array(
    			'post_status'		=> 'publish',
    			'date_query'		=> array(
    				array(
						'column' => 'post_date',
						'after' => $url_query['date_filter'],
						'inclusive' => true
					),
    			),

    		);
    		
    		$date_posts_archive = $json_api->introspector->get_posts($date_posts_archive_args);
    		$response['count'] = count($date_posts_archive);
    		$response['count_total'] = (int) $wp_query->found_posts;
    		$response['pages'] = $wp_query->max_num_pages;
    		$response['posts'] = $date_posts_archive;
    	}

	}

	/**
	 *  Single Post Specific
	 */
	if ( isset( $response['post'] ) ) {
		$previous_post = get_previous_post();
		$next_post = get_next_post();

		if ( !empty( $previous_post ) ) {
			$response['post']->previous_post_info = array(
				'url'	=> get_permalink($previous_post->ID),
				'title'	=> $previous_post->post_title
			);
		}

		if ( !empty( $next_post ) ) {
			$response['post']->next_post_info = array(
				'url'	=> get_permalink($next_post->ID),
				'title'	=> $next_post->post_title
			);
		}
	}

	/**
	 * Add Template Slug to All the single pages + Add accordion items to pages with template template-page-with-accordion.php
	 */
	if (isset($response['page'])) {
		$response['page']->page_template_slug = get_page_template_slug($response['page']->id);

		if ( $response['page']->page_template_slug == 'template-page-with-accordion.php' ) {
			$response['page']->accordion_items = get_field('accordion', $response['page']->id);
		}
	}

	return $response;
}
add_filter('json_api_encode', 'tls_json_api_encode');