<?php

/**
 * tls_json_api_enconde			This function serves to filter/modify the JSON Response Object of the JSON API for various pages
 * @param  object $response 	The JSON API Response Object
 * @return object $reposnse		Returns the new and filtered/modefied JSON API Response Object
 */
function tls_json_api_encode($response) {
    // Globals to be used with many of the specific searches
    global $json_api, $wp_query;

    /**
     * Search Page Specific
     */
    if ( is_search() ) {

        // URL parsing to use with custom JSON API queries
        $url = parse_url($_SERVER['REQUEST_URI']);
        $url_query = wp_parse_args($url['query']);

    	// Get Search Query to be used in all the queries
    	$search_query = get_search_query();

        // Get Current WP Query;
        $current_query = $wp_query->query;

		/**
		 * Reviews Articles Tag Search Filter
		 */
    	$reviews_tag = get_term_by( 'slug', 'reviews', 'article_tags' );
    	$reviews_tag_id = (int) $reviews_tag->term_id;
    	$reviews = new WP_Query( array(  
    		'post_type'			=> 'tls_articles',
    		'post_status'		=> 'publish',
    		'posts_per_page'	=> 1,
    		's'					=> $search_query,
    		'tax_query'			=> array( array (
    			'taxonomy'		=> 'article_tags',
    			'field'			=> 'term_id',
    			'terms'			=> $reviews_tag_id
    		) )
    	) ); wp_reset_query();

    	$response['content_type_filters']['reviews'] = array(
    		'item_label'		=> __('Reviews', 'tls'),
            'json_query'        => 'post_types[tls_articles]&tax_filter[article_tags]=reviews',
    		//'taxonomy'			=> $reviews_tag->taxonomy,
    		//'slug'				=> $reviews_tag->slug,
    		'search_count'		=> (int) $reviews->found_posts
    	);

    	/**
    	 * Reviews Articles Tag Search Filter
    	 */
    	$public_visibility = get_term_by( 'slug', 'public', 'article_visibility' );
    	$public_visibility_id = (int) $public_visibility->term_id;
    	$public_articles = new WP_Query( array(  
    		'post_type'			=> 'tls_articles',
    		'post_status'		=> 'publish',
    		'posts_per_page'	=> 1,
    		's'					=> $search_query,
    		'tax_query'			=> array( array (
    			'taxonomy'		=> 'article_visibility',
    			'field'			=> 'term_id',
    			'terms'			=> $public_visibility_id
    		) )
    	) ); wp_reset_query();

    	$response['content_type_filters']['public_articles'] = array(
    		'item_label'		=> __('Free To Non Subscribers', 'tls'),
            //'type'              => 'taxonomy',
            'json_query'        => 'post_types[tls_articles]&tax_filter[article_visibility]=public',
    		//'taxonomy'			=> $public_visibility->taxonomy,
    		//'slug'				=> $public_visibility->slug,
    		'search_count'		=> (int) $public_articles->found_posts
    	);


        /**
         * TLS catagory filters
         */
        $sections = get_terms( 'article_section' );

        foreach ($sections as $key => $value) {
            $section_count = 0;
            foreach ($response['posts'] as $postkey => $postvalue) {

                $post_section = wp_get_post_terms($postvalue->id,'article_section');

                if ($value->slug == $post_section[0]->slug) { $section_count++; }
               
            }

           $response['articles_sections'][$value->slug] = array(
                'item_label'        => $value->name,
                'type'              => 'taxonomy',
                'json_query'        => 'tax_filter[article_section]='.$value->slug,
                'taxonomy'          => $value->taxonomy,
                'slug'              => $value->slug,
                'search_count'      => (int) $section_count
            );
        }

    	/**
    	 * TLS Blogs post type
    	 */
    	$blogs = new WP_Query( array(
    		'post_type'			=> 'post',
    		'post_status' 		=> 'publish',
    		'posts_per_page' 	=> 1,
    		's'					=> $search_query
    	) ); wp_reset_query();

    	$response['content_type_filters']['blogs'] = array(
    		'item_label'		=> __('Blogs', 'tls'),
            //'type'              => 'post_type',
    		//'slug' 				=> 'post',
            'json_query'        => 'post_types[post]',
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
            'json_query'        => 'post_types[tls_faq]',
            //'type'              => 'post_type',
    		//'slug'				=> 'tls_faq',
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
        $response['date_filters']['1_past_7_days'] = array(
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
        $response['date_filters']['2_past_30_days'] = array(
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
        $response['date_filters']['3_past_1_year'] = array(
            'item_label'        => __('Past Year'),
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
        $response['date_filters']['4_past_5_years'] = array(
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
        $response['date_filters']['5_past_10_years'] = array(
            'item_label'        => __('Past 10 Years'),
            'search_term'       => '10 years ago',
            'search_count'      => (int) $past_10_years->found_posts
        );

        /**
         * Add Multiple Custom Post Type Search Filtering to
         * JSON API post_type filter which only works
         * with one post type out of the box
         */
        if ( isset( $_GET['post_types'] ) ) {

            $post_types = array();

            foreach ( $_GET['post_types'] as $post_type_key => $post_type_value ) {
                if ( $post_type_key != 'tls_articles' ) {
                    $post_types[] = wp_strip_all_tags($post_type_key);
                }
            }

            $current_query['post_type'] = $post_types;

            $post_type_search_archive_args = array(
                'post_type'         => $post_types,
                'post_status'       => 'publish',
                's'                 => $search_query
            );

            $post_type_search_archive_query = array_merge($current_query, $post_type_search_archive_args);
            $post_type_search_archive = $json_api->introspector->get_posts($post_type_search_archive_query);
            $response['count'] = count($post_type_search_archive);
            $response['count_total'] = (int) $wp_query->found_posts;
            $response['pages'] = $wp_query->max_num_pages;
            $response['posts'] = $post_type_search_archive;
        }

        /**
         * Add Custom post filtering for Custom taxonomy search
         * then return appropriate filtered posts
         */
        if ( isset( $_GET['tax_filter'] ) ) {

            $current_query['posts_per_page'] = -1;
            $current_archive = get_posts($current_query);
            $response['current_query_posts'] = $current_archive;

            $tax_query = array(
                'relation'          => 'OR'
            );

            foreach ($_GET['tax_filter'] as $tax => $tax_term) {
                $taxonomy_queries = array(
                    'taxonomy'      => wp_strip_all_tags($tax),
                    'field'         => 'slug',
                    'terms'         => wp_strip_all_tags($tax_term),
                );
                array_push($tax_query, $taxonomy_queries);
            }

            $taxonomy_posts_archive_args = array(
                'post_type'         => array( 'tls_articles' ),
                'post_status'       => 'publish',
                's'                 => $search_query,
                'tax_query'         => $tax_query,
                'posts_per_page'    => -1
            );

            $taxonomy_posts_archive = new WP_Query($taxonomy_posts_archive_args);

            $results_archive = array_merge( $current_archive, $taxonomy_posts_archive->posts );

            $results_post_ids = array();
            foreach ($results_archive as $result) {
                $results_post_ids[]=$result->ID;
            }

            $final_post_ids = array_unique($results_post_ids);
            $final_archive_args = array(
                'post_type'     => array( 'post', 'tls_faq', 'tls_articles' ),
                'post__in'      => $final_post_ids,
                'post_status'   => 'publish'
            );

            $final_archive = new WP_Query($final_archive_args);

            $results = $json_api->introspector->get_posts($final_archive);

            $wp_query = $final_archive;
            $response['wp_query'] = $wp_query;
            $response['count'] = count($results);
            $response['count_total'] = (int) $wp_query->found_posts;
            $response['pages'] = $wp_query->max_num_pages;
            $response['posts'] = $results;


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
	 * Page Template Specific JSON API Responses
	 */
	if ( isset( $response['page'] ) ) {
        // Add page template slug to JSON API Response
		$response['page_template_slug'] = get_page_template_slug($response['page']->id);


		if ( $response['page_template_slug'] == 'template-page-with-accordion.php' ) { // If it is template-page-with-accordion.php Page template
			$response['page']->accordion_items = get_field('accordion', $response['page']->id);
		}
	}

    $response['query'] = $wp_query->query;
    $response['json_query'] = $json_api->query;
	return $response;
}
add_filter( 'json_api_encode', 'tls_json_api_encode' );


/**
 * Home Page Template JSON API Response Modifications
 *
 * @param $response     JSON API Response Object
 * @return Object       Updated JSON API Response Object
 */
function tls_home_page_json_api_encode($response) {

    if ( isset( $response['page'] ) && $response['page_template_slug'] == 'template-home.php' ) {

        // Get Home Page ID for this page using Template Home Page Template
        $home_page_id = $response['page']->id;

        // Get Featured Post Details
        $featured_article = get_field( 'featured_article', $home_page_id );

        $images = array(
            'hero_image'        => get_field( 'hero_image_url', $featured_article->ID ),
            'full_image'        => get_field( 'full_image_url', $featured_article->ID ),
            'thumbnail_image'   => get_field( 'thumbnail_image_url', $featured_article->ID )
        );

        $response['featured_article'] = array(
            'id'                => $featured_article->ID,
            'title'             => $featured_article->post_title,
            'author'            => get_the_author_meta( 'display_name', $featured_article->post_author ),
            'text'              => wp_strip_all_tags( substr( $featured_article->post_content, 0, 100 ) ) . '...',
            'link'              => get_permalink( $featured_article->ID ),
            'images'            => $images
        );

        /**
         * Home Page Blog Cards (For the 2 first Blog Cards)
         */
        $blog_cards = get_field('blogs_cards', $home_page_id);
        foreach ( (array)$blog_cards as $blog_card) {
            // Get the categories for the Blog Post
            $categories = wp_get_post_terms( $blog_card->ID, 'category' );

            // Add Blog Post Cards to JSON Response in the first card slot
            $response['home_page_cards']['card_0'][] = array(
                'type'          => 'blog',
                'id'            => $blog_card->ID,
                'title'         => $blog_card->post_title,
                'author'        => get_the_author_meta( 'display_name', $blog_card->post_author ),
                'text'          => wp_strip_all_tags( substr( $blog_card->post_content, 0, 50 ) ) . '...',
                'link'          => get_permalink( $blog_card->ID ),
                'section'       => array(
                    'name'      => $categories[0]->name,
                    'link'      => site_url() . '/' . $categories[0]->taxonomy . '/' . $categories[0]->slug
                ),
            );
        }

        /**
         * Home Page Cards (For the rest of the home page cards)
         */
        $home_page_cards = get_field('home_page_cards', $home_page_id);

        // Start the Cards at 1 since the Home Page Blog Cards already took the place 0
        $home_page_card_count = 1;
        foreach ((array)$home_page_cards as $home_page_card) {

            // Variable that grabs Card Type
            $card_type = $home_page_card['card_type'];
            // Variable that gets the post for the Card Type
            $card_post = $home_page_card[$card_type . '_post'];

            // If Card Type is Article the create variable $section with the article-section taxonomy otherwise use the taxonomy category
            if ( $card_type == 'article' ) {
                $section = wp_get_post_terms( $card_post->ID, 'article_section' );
                $thumbnail_image = get_field( 'thumbnail_image_url', $card_post->ID );
            } else {
                $section = wp_get_post_terms( $card_post->ID, 'category' );
                $thumbnail_image = '';
            }

            // Add Cards to the JSON Response in the specific count slot
            $response['home_page_cards']['card_' . $home_page_card_count] = array(
                'type'          => $card_type,
                'id'            => $card_post->ID,
                'title'         => $card_post->post_title,
                'author'        => get_the_author_meta( 'display_name', $card_post->post_author ),
                'text'          => substr( $card_post->post_content, 0, 50 ) . '...',
                'link'          => get_permalink( $card_post->ID ),
                'section'       => array(
                    'name'      => $section[0]->name,
                    'link'      => site_url() . '/' . $section[0]->taxonomy . '/' . $section[0]->slug
                ),
                'image'         => $thumbnail_image
            );

            // Iterate to next count slot
            $home_page_card_count++;
        }

    }
    return $response;
}
add_filter( 'json_api_encode', 'tls_home_page_json_api_encode' );


/**
 * Latest Edition Page Template JSON API Response Modifications
 *
 * @param $response     The JSON API Response Object
 * @return $response    Update JSON API Response Object
 */
function tls_latest_edition_page_json_api_encode($response) {

    if ( isset( $response['page_template_slug'] ) && $response['page_template_slug'] == 'template-latest-edition.php' ) {
        
        $latest_edition = new WP_Query( array(  
            'post_type'         => 'tls_editions',
            'post_status'       => 'publish',
            'posts_per_page'    => 1,
            'orderby '          => 'date'
        ) ); wp_reset_query();
        $latest_edition = $latest_edition->posts[0];

        $response['latest_edition'] = array(
                'id'        => $latest_edition->ID,
                'title'     => $latest_edition->post_title,
                'url'       => get_permalink($latest_edition->ID),
                
            );

        $previousPost = get_previous_post($latest_edition->ID); 
        $response['latest_edition']['previous_post_info'] = array(
                'id'        => $previousPost->ID,
                'title'     => $previousPost->post_title,
                'url'       => get_permalink($previousPost->ID),
                
            );

        $nextPost = get_next_post($latest_edition->ID);
        $response['latest_edition']['next_post_info'] = array(
                'id'        => $nextPost->ID,
                'title'     => $nextPost->post_title,
                'url'       => get_permalink($nextPost->ID),
                
            );

        $latest_edition_articles = get_fields($latest_edition->ID);
        //$response['latest_edition']['test_content'] =  $latest_edition_articles['edition_number'];
         
        $response['latest_edition']['content']['featured'] = array(
                'issue_no'  => $latest_edition_articles['edition_number'],
                'image_url' => $latest_edition_articles['feautured_image']['url']
                
                
            );


        $response['latest_edition']['content']['public']['title'] = 'Public content';

        foreach ($latest_edition_articles['public_articles'] as $key => $value) {

            $section = get_the_terms($value->ID,'article_section');


            foreach ($section as $termkey => $termvalue) { 
                $section = $value->name;
             }

            $postAuthor = get_fields($value->ID);
            $response['latest_edition']['content']['public']['articles'][$value->post_name] = array(
                'id'        => $value->ID,
                'author'    => $postAuthor['article_author_name'],
                'title'     => $value->post_title,
                'section'   => $section,
                'url'       => get_permalink($value->ID),
                
            );
        }
        
        $response['latest_edition']['content']['regulars']['title'] = 'Regulars';
        foreach ($latest_edition_articles['regular_articles'] as $key => $value) {
            $section = get_the_terms($value->ID,'article_section');
            foreach ($section as $termkey => $termvalue) { $section = $value->name; }
            $response['latest_edition']['content']['regulars']['articles'][$value->post_name] = array(
                'id'        => $value->ID,
                'author'    => $value->post_author,
                'title'     => $value->post_title,
                'section'   => $section,
                'url'       => get_permalink($value->ID),
                
            );
        }   


        $response['latest_edition']['content']['subscribers']['title'] = 'Subscriber Exclusive';
        foreach ($latest_edition_articles['subscriber_only_articles'] as $key => $value) {
            
            $section = get_the_terms($value->ID,'article_section');
            foreach ($section as $termkey => $termvalue) {
                $section = $termvalue->name; 
            }
            
            $response['latest_edition']['content']['subscribers']['articles'][$section][$value->post_name] = array(
                'id'        => $value->ID,
                'author'    => $value->post_author,
                'title'     => $value->post_title,
                'section'   => $section,
                'url'       => get_permalink($value->ID),
                
            );
        }  

        //$response['latest_edition']['content'] = get_the_terms('2433','article_section');

    }

    return $response;
}
add_filter( 'json_api_encode', 'tls_latest_edition_page_json_api_encode' );


/**
 * Discover Articles Archive Page Template JSON API Response Modifications
 *
 * @param $response     The JSON API Response Object
 * @return $response    Update JSON API Response Object
 */
function tls_discover_json_api_encode($response) {

    if ( isset( $response['page'] ) &&  $response['page_template_slug'] == 'template-articles-archive.php' ) {
        // Globals to be used with many of the specific searches
        global $json_api, $wp_query;

        /**
         * Top Article from each Article Section
         */

        // Variable to store Top Articles IDs to later on remove from the second list of articles
        $top_section_articles = array();

        // Get all the terms from Article Section Taxonomy
        $article_sections_args = array(
            'hide_empty'    => false
        );
        $article_sections = get_terms( 'article_section', $article_sections_args );

        // Loop through each of the sections to make a WP_Query
        foreach ($article_sections as $section) {

            // Arguments for the WP_Query
            $top_section_article_args = array(
                'post_type'         => 'tls_articles',
                'posts_per_page'    => 1,
                'orderby'           => 'date',
                'order'             => 'DESC',
                'tax_query'         => array( array(
                    'taxonomy'      => 'article_section',
                    'field'         => 'term_id',
                    'terms'         => $section->term_id
                ) )
            );

            // WP_Query for the Top Article in current section term
            $top_section_article_query = new WP_Query($top_section_article_args);

            // Get the first article returned from Query
            $top_section_article = $top_section_article_query->posts[0];

            // Add Article ID into the top_section_articles array
            $top_section_articles[] = $top_section_article->ID;

            // Add this article into top_articles array from the JSON Response Object
            $response['top_articles'][] = array(
                'id'                            => $top_section_article->ID,
                'url'                           => get_permalink( $top_section_article->ID ),
                'title'                         => $top_section_article->post_title,
                'excerpt'                       => str_replace( '[...]', '', apply_filters( 'the_excerpt', $top_section_article->post_excerpt ) ),
                'author'                        => array(
                    'name'                      => get_the_author_meta( 'display_name', $top_section_article->post_author ),
                    'slug'                      => get_the_author_meta( 'slug', $top_section_article->post_author ),
                ),
                'custom_fields'                 => array(
                    'thumbnail_image_url'       => get_field( 'thumbnail_image_url', $top_section_article->ID )
                ),
                'taxonomy_article_section'      => wp_get_post_terms( $top_section_article->ID, 'article_section' ),
                'taxonomy_article_visibility'   => wp_get_post_terms( $top_section_article->ID, 'article_visibility' ),
            );

        } // END Loop of top Articles from each Article Section

        /**
         * All articles excluding the ones in the Top Articles Section
         */

        // Set up paged variable for the query
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        // WP_Query Arguments
        $articles_archive_args = array(
            'post_type'         => array( 'tls_articles' ),
            'orderby'           => 'date',
            'order'             => 'DESC',
            'paged'             => $paged,
            'post__not_in'      => $top_section_articles // Array of ID's of Top Articles to remove
        );

        // Override query inside the $wp_query global to be able to use the JSON API get_posts
        $wp_query->query = $articles_archive_args;
        $articles_archive = $json_api->introspector->get_posts($wp_query->query);

        // Update JSON Response Object for the main Articles Archive
        $response['count'] = count($articles_archive);
        $response['count_total'] = (int) $wp_query->found_posts;
        $response['pages'] = $wp_query->max_num_pages;
        $response['posts'] = $articles_archive;

    }

    return $response;
}
add_filter( 'json_api_encode', 'tls_discover_json_api_encode' );


/**
 * Blogs Archive Page Template JSON API Response Modifications
 *
 * @param $response     The JSON API Response Object
 * @return $response    Update JSON API Response Object
 */
function tls_blogs_archive_json_api_encode($response) {

    if ( isset( $response['page'] ) && $response['page_template_slug'] == 'template-blogs-archive.php' ) {
        // Globals to be used with many of the specific searches
        global $json_api, $wp_query;

        // Get Featured Post Details
        $featured_post = get_post( $response['page']->custom_fields->featured_blog_post[0] );

        // Get Post Thumbnail and all it's different sizes as URLs for Featured Blog
        $sizes = get_intermediate_image_sizes();
        $attachment_id = get_post_thumbnail_id( $featured_post->ID );

        $images = array();
        foreach ( $sizes as $size ) {
            $images[$size] = wp_get_attachment_image_src( $attachment_id, $size );
        }
        $images['full'] = wp_get_attachment_image_src( $attachment_id, 'full' );

        $response['featured_post'] = array(
            'id'            => $featured_post->ID,
            'title'         => $featured_post->post_title,
            'excerpt'       => wp_strip_all_tags( substr($featured_post->post_content, 0, 100) ) . '...',
            'link'          => get_permalink( $featured_post->ID ),
            'images'     => $images
        );

        // Get Blog Archive excluding Featured Blog from list
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $blogs_archive_args = array(
            'post_type'         => array( 'post' ),
            'orderby'           => 'date',
            'order'             => 'DESC',
            'paged'             => $paged,
            'post__not_in'      => array( $featured_post->ID ),
        );

        $wp_query->query = $blogs_archive_args;
        $blogs_archive = $json_api->introspector->get_posts($wp_query->query);
        $response['count'] = count($blogs_archive);
        $response['count_total'] = (int) $wp_query->found_posts;
        $response['pages'] = $wp_query->max_num_pages;
        $response['posts'] = $blogs_archive;
    }

    return $response;
}
add_filter( 'json_api_encode', 'tls_blogs_archive_json_api_encode' );


/**
 * FAQs Page Template JSON API Response Modifications
 *
 * @param $response     The JSON API Response Object
 * @return $response    Update JSON API Response Object
 */
function tls_faqs_json_api_encode($response) {

    if ( isset( $response['page'] ) && $response['page_template_slug'] == 'template-faqs.php' ) {

        $faqs_query = new WP_Query( array(
            'post_type'         => array( 'tls_faq' ),
            'posts_per_page'    => -1
        ) );
        $faqs_posts = $faqs_query->posts;

        foreach ($faqs_posts as $faq) {
            $response['faqs'][] = array(
                'title'         => $faq->post_title,
                'content'       => $faq->post_content
            );
        }

    }

    return $response;
}
add_filter( 'json_api_encode', 'tls_faqs_json_api_encode' );
