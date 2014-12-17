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

    //exclude these fields from the json response data, could't find native way to exlude them so this way :/
    $excludes = array(
        'posts' => array(
            'page' 
        )
    );

    return $response;
}
add_filter('json_api_encode', 'tls_json_api_encode');

/**
 * tls_add_page_template_slug_json_api	Add Page Template Slug to pages
 * @param  object $response 	The JSON API Response Object
 * @return object $reposnse		Returns the new JSON API Response Object
 */
function tls_add_page_template_slug_json_api($response) {
  if (isset($response['page'])) {
   $response['page']->page_template_slug = get_page_template_slug($response['page']->id);
  }
  return $response;
}
add_filter('json_api_encode', 'tls_add_page_template_slug_json_api');

/**
 * tls_page_accordion_items_json_api	Add Accordion Items to the pages with template-page-with-accordion.php page template only
 * @param  object $response 	The JSON API Response Object
 * @return object $reposnse		Returns the new JSON API Response Object
 */
function tls_page_accordion_items_json_api($response) {
	if ( isset( $response['page'] ) ) {

		if ( $response['page']->page_template_slug == 'template-page-with-accordion.php' ) {
			$response['page']->accordion_items = get_field('accordion', $response['page']->id);
		}
	}
	return $response;
}
add_filter('json_api_encode', 'tls_page_accordion_items_json_api');