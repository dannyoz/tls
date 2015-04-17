<?php

/**
 * Blogs Archive Page Template JSON API Response Modifications
 *
 * @param $response     The JSON API Response Object
 *
 * @return $response    Update JSON API Response Object
 */
function tls_blogs_archive_json_api_encode($response)
{

    if (isset($response['page']) && $response['page_template_slug'] == 'template-blogs-archive.php') {
        // Globals to be used with many of the specific searches
        global $json_api, $wp_query;

        /**
         * ===========================================
         *              Featured Blog Post
         * ===========================================
         */

        // Get Featured Post Details
        $featured_post = get_post($response['page']->custom_fields->featured_blog_post[0]);

        // Get Post Thumbnail and all it's different sizes as URLs for Featured Blog
        $sizes = get_intermediate_image_sizes();
        $attachment_id = get_post_thumbnail_id($featured_post->ID);

        // Get Featured Post Category
        $featured_post_category = wp_get_post_terms($featured_post->ID, 'category');

        // Teaser Summary
        $featured_post_teaser = get_field('teaser_summary', $featured_post->ID);

        $images = array();
        foreach ($sizes as $size) {
            $images[$size] = wp_get_attachment_image_src($attachment_id, $size);
        }
        $images['full'] = wp_get_attachment_image_src($attachment_id, 'full');

        $response['featured_post'] = array(
            'type' => ($featured_post_category[0]->slug == 'a-dons-life' || $featured_post_category[0]->slug == 'dons-life') ? 'dons_life_blog' : str_replace('-',
                    '_', $featured_post_category[0]->slug) . '_blog',
            'id' => $featured_post->ID,
            'title' => $featured_post->post_title,
            'link' => get_permalink($featured_post->ID),
            'images' => $images
        );

        /**
         * ===========================================
         *              Spotlight Podcast
         * ===========================================
         */
        $spotlight_podcast = get_post($response['page']->custom_fields->spotlight_podcast[0]);
        $spotlight_podcast_custom_fields = get_post_custom($spotlight_podcast->ID);
        $response['posts'][] = array(
            'type' => 'listen_blog',
            'id' => $spotlight_podcast->ID,
            'title' => $spotlight_podcast->post_title,
            'link' => get_permalink($spotlight_podcast->ID),
            'soundcloud' => $spotlight_podcast_custom_fields['soundcloud_embed_code'][0]
        );

        /**
         * ===========================================
         *              Blog Posts Archive
         * ===========================================
         */

        // Get Blog Archive excluding Featured Blog from list
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $blogs_archive_args = array(
            'post_type' => array('post'),
            'orderby' => 'date',
            'order' => 'DESC',
            'paged' => $paged,
            'post__not_in' => array(
                $featured_post->ID,
                $spotlight_podcast->ID
            ),
        );

        $wp_query->query = $blogs_archive_args;
        $blogs_archive = $json_api->introspector->get_posts($wp_query->query);

        foreach ($blogs_archive as $blog_post) {
            $response['posts'][] = $blog_post;

            $categories = wp_get_post_terms($blog_post->id, 'category');
            $blog_post_custom_fields = get_post_custom($blog_post->id);

            $blog_post->soundcloud = $blog_post_custom_fields['soundcloud_embed_code'][0];
            $blog_post->category_url = get_term_link($categories[0]->term_id, $categories[0]->taxonomy);

            if ($categories[0]->slug == 'a-dons-life' || $categories[0]->slug == 'dons-life') {
                $blog_post->type = 'dons_life_blog';
            } else {
                $blog_post->type = str_replace('-', '_', $categories[0]->slug) . '_blog';
            }
        }

        $response['count'] = count($blogs_archive);
        $response['count_total'] = (int)$wp_query->found_posts;
        $response['pages'] = $wp_query->max_num_pages;
    }

    // If it is archive of blogs (post type 'post') create new excerpt
    if ($response['posts']) {

        foreach ($response['posts'] as $response_post) {
            if ('post' == get_post_type($response_post->id)) {
                $response_post->excerpt = tls_make_post_excerpt($response_post->content, 30);
            }
        }

    }

    return $response;
}

add_filter('json_api_encode', 'tls_blogs_archive_json_api_encode');