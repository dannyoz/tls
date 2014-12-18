<?php

/**
 * tls_json_api_enconde			This function serves to filter/modify the JSON Response Object of the JSON API for various pages
 * @param  object $response 	The JSON API Response Object
 * @return object $reposnse		Returns the new and filtered/modefied JSON API Response Object
 */
function tls_json_api_encode($response) {
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
    	$search_query = get_search_query();
    	
    	// TLS Blogs Category Term Search Filter Info
    	$tls_blogs = get_term_by( 'slug', 'tls-blogs', 'category' );
    	$tls_blogs_id = (int) $tls_blogs->term_id;
    	$blogs = new WP_Query( array(  
    		'post_type'			=> 'post',
    		'post_status' 		=> 'publish',
    		'cat' 				=> $tls_blogs_id,
    		'posts_per_page' 	=> -1,
    		's'					=> $search_query
    	) );

    	$response['search_filters']['blogs'] = array(
    		'name' 				=> __($tls_blogs->name, 'tls'),
    		'taxonomy'			=> $tls_blogs->taxonomy,
    		'slug' 				=> $tls_blogs->slug,
    		'search_count' 		=> $blogs->post_count
    	);

    	// FAQs Post Type Search Filter
    	$faqs = new WP_Query( array( 
    		'post_type'			=> 'tls_faq',
    		'post_status'		=> 'publish',
    		'posts_per_page'	=> -1,
    		's'					=> $search_query
    	) );

    	$response['search_filters']['faqs'] = array(
    		'name'				=> __('FAQs', 'tls'),
    		'slug'				=> 'faqs',
    		'search_count'		=> $faqs->post_count
    	);

    	// Reviews Articles Tag Search Filter
    	$reviews_tag = get_term_by( 'slug', 'reviews', 'post_tag' );
    	$reviews_tag_id = (int) $reviews_tag->term_id;
    	$reviews = new WP_Query( array(  
    		'post_type'			=> 'tls_articles',
    		'post_status'		=> 'publish',
    		'posts_per_page'	=> -1,
    		's'					=> $search_query,
    		'tax_query'			=> array( array (
    			'taxonomy'		=> 'post_tag',
    			'field'			=> 'term_id',
    			'terms'			=> $reviews_tag_id
    		) )
    	) );

    	$response['search_filters']['reviews'] = array(
    		'name'				=> __($reviews_tag->name),
    		'taxonomy'			=> $reviews_tag->taxonomy,
    		'slug'				=> $reviews_tag->slug,
    		'search_count'		=> $reviews->post_count
    	);

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