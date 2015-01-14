<?php

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