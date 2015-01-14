<?php

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

        foreach ( $blogs_archive as $blog_post ) {
            $categories = wp_get_post_terms( $blog_post->id, 'category' );

            $blog_post->category_url = get_term_link( $categories[0]->term_id, $categories[0]->taxonomy );
        }

        $response['count'] = count($blogs_archive);
        $response['count_total'] = (int) $wp_query->found_posts;
        $response['pages'] = $wp_query->max_num_pages;
        $response['posts'] = $blogs_archive;
    }

    return $response;
}
add_filter( 'json_api_encode', 'tls_blogs_archive_json_api_encode' );