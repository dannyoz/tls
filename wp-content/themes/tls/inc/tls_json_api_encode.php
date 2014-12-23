<?php

/**
 * tls_json_api_enconde			This function serves to filter/modify the JSON Response Object of the JSON API for various pages
 * @param  object $response 	The JSON API Response Object
 * @return object $reposnse		Returns the new and filtered/modefied JSON API Response Object
 */
function tls_json_api_encode($response) {
	// Globals to be used with many of the specific searches
    global $json_api, $wp_query;
	
    // URL parsing to use with custom JSON API queries
    $url = parse_url($_SERVER['REQUEST_URI']);
    $url_query = wp_parse_args($url['query']);

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
    		'slug'				=> 'tls_faq',
    		'search_count'		=> (int) $faqs->found_posts
    	);


        /**
         * Date Filters Options with post count
         */
        // Past 7 Days
        $past_7_days = new WP_Query( array (
            'post_type'         => array('post', 'tls_articles', 'tls_faq'),
            'post_status'       => 'publish',
            's'                 => $search_query,
            'date_query'        => array(
                array(
                    'column'    => 'post_date',
                    'after'     => '7 days ago',
                    'inclusive' => true
                ),
            ),
        ) ); wp_reset_query();
        $response['date_filters']['past_7_days'] = array(
            'item_label'        => __('Past 7 Days'),
            'search_term'       => '7 days ago',
            'search_count'      => (int) $past_7_days->found_posts
        );

        // Past 30 Days
        $past_30_days = new WP_Query( array (
            'post_type'         => array('post', 'tls_articles', 'tls_faq'),
            'post_status'       => 'publish',
            's'                 => $search_query,
            'date_query'        => array(
                array(
                    'column'    => 'post_date',
                    'after'     => '30 days ago',
                    'inclusive' => true
                ),
            ),
        ) ); wp_reset_query();
        $response['date_filters']['past_30_days'] = array(
            'item_label'        => __('Past 30 Days'),
            'search_term'       => '30 days ago',
            'search_count'      => (int) $past_30_days->found_posts
        );

        // Past 1 Year
        $past_1_year = new WP_Query( array (
            'post_type'         => array('post', 'tls_articles', 'tls_faq'),
            'post_status'       => 'publish',
            's'                 => $search_query,
            'date_query'        => array(
                array(
                    'column'    => 'post_date',
                    'after'     => '1 year ago',
                    'inclusive' => true
                ),
            ),
        ) ); wp_reset_query();
        $response['date_filters']['past_1_year'] = array(
            'item_label'        => __('Past 1 Year'),
            'search_term'       => '1 year ago',
            'search_count'      => (int) $past_1_year->found_posts
        );

        // Past 5 Years
        $past_5_years = new WP_Query( array (
            'post_type'         => array('post', 'tls_articles', 'tls_faq'),
            'post_status'       => 'publish',
            's'                 => $search_query,
            'date_query'        => array(
                array(
                    'column'    => 'post_date',
                    'after'     => '5 years ago',
                    'inclusive' => true
                ),
            ),
        ) ); wp_reset_query();
        $response['date_filters']['past_5_years'] = array(
            'item_label'        => __('Past 5 Years'),
            'search_term'       => '5 years ago',
            'search_count'      => (int) $past_5_years->found_posts
        );

        // Past 10 Years
        $past_10_years = new WP_Query( array (
            'post_type'         => array('post', 'tls_articles', 'tls_faq'),
            'post_status'       => 'publish',
            's'                 => $search_query,
            'date_query'        => array(
                array(
                    'column'    => 'post_date',
                    'after'     => '10 years ago',
                    'inclusive' => true
                ),
            ),
        ) ); wp_reset_query();
        $response['date_filters']['past_10_years'] = array(
            'item_label'        => __('Past 10 Years'),
            'search_term'       => '10 years ago',
            'search_count'      => (int) $past_10_years->found_posts
        );

        /**
         * Add Custom post filtering for Custom taxonomy search
         * then return appropriate filtered posts
         */
        if ( isset( $_GET['taxonomy'] ) ) {

            $taxonomy_posts_archive_args = array(
                'post_type'         => array('post', 'tls_articles', 'tls_faq'),
                'post_status'       => 'publish',
                's'                 => $search_query,
                'tax_query'         => array (
                    array(
                        'taxonomy'      => $url_query['taxonomy'],
                        'field'         => 'slug',
                        'terms'         => $url_query['taxonomy_terms']
                    ),
                ),
            );
            $taxonomy_posts_archive = $json_api->introspector->get_posts($taxonomy_posts_archive_args);
            $response['count'] = count($taxonomy_posts_archive);
            $response['count_total'] = (int) $wp_query->found_posts;
            $response['pages'] = $wp_query->max_num_pages;
            $response['posts'] = $taxonomy_posts_archive;
        }


    	/**
    	 * Add Date filtering with custom date_filter option in GET request
         * then return the new posts with the date filetering
    	 */
    	if ( isset( $_GET['date_filter'] ) && $_GET['date_filter'] != '' ) {

			$date_posts_archive_args = array(
                'post_type'         => array('post', 'tls_articles', 'tls_faq'),
    			'post_status'		=> 'publish',
                's'                 => $search_query,
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

	} // END of Search Specific JSON API queries

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
    $response['query'] = $wp_query->query;
	return $response;
}
add_filter('json_api_encode', 'tls_json_api_encode');