<?php

/**
 * Generic / Misc JSON API Response Modifications
 *
 * @param $response     JSON API Response Object
 * @return Object       Updated JSON API Response Object
 */
function tls_json_api_encode($response) {
    // Globals to be used with many of the specific searches
    global $json_api, $wp_query;

	/**
	 *  Single Post Specific JSON API Response
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

		/**
		 * Change Content Length For Private Articles with Akamai Teaser View set true
		 */
		if ( $response['post']->type == 'tls_articles' && $response['post']->taxonomy_article_visibility[0]->slug == 'private' ) {
			$url = parse_str( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY ), $urlQuery );

			if ( isset( $urlQuery['akamai-teaser'] ) && $urlQuery['akamai-teaser'] == 'true' ) {
				$article_post = get_post( $response['post']->id );
				$response['post']->akamai_teaser = true;
				$response['post']->content = tls_limit_content( $article_post->post_content, 150 );
			}
		}

	}

	/**
	 * Page Template Specific JSON API Responses
	 */
	if ( isset( $response['page'] ) ) {
        // Add page template slug to JSON API Response
		$response['page_template_slug'] = get_page_template_slug($response['page']->id);
	}

    /**
     * Page with template-page-with-accordion.php Page template JSON API Response
     */
    if ( isset( $response['page'] ) && $response['page_template_slug'] == 'template-page-with-accordion.php' ) { // If it is template-page-with-accordion.php Page template
        $response['page']->accordion_items = get_field('accordion', $response['page']->id);
    }

    $response['query'] = $wp_query->query;
    $response['json_query'] = $json_api->query;
	return $response;
}
add_filter( 'json_api_encode', 'tls_json_api_encode' );