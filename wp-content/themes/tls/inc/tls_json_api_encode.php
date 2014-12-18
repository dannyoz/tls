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
    	$s = get_search_query();
    	$tls_blogs = get_term_by( 'slug', 'tls-blogs', 'category' );
    	$tls_blogs_id = (int) $tls_blogs->term_id;
    	$blogs = new WP_Query( "post_type=post&post_status=publish&cat={$tls_blogs_id}&posts_per_page=-1&s={$s}" );

    	$response['blogs'] = array(
    		'id'			=> $tls_blogs,
    		'name' 			=> __('TLS Blogs', 'tls'),
    		'slug' 			=> 'category/tls-blogs',
    		'search_count' 	=> $blogs->post_count
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