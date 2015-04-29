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

        // Page ID
        $page_id = $response['page']->id;

        /**
         * ===========================================
         *              Featured Blog Post
         * ===========================================
         */

        // Get Featured Post Details
        $featured_post = get_field('featured_blog_post', $page_id);

        // Get Featured Post Category
        $featured_post_category = wp_get_post_terms($featured_post[0]->ID, 'category');

        $attachment_id = get_post_thumbnail_id($featured_post[0]->ID);
        $featured_post_hero_image = wp_get_attachment_image_src($attachment_id, 'full');

        $response['featured_post'] = array(
            'type' => ($featured_post_category[0]->slug == 'a-dons-life' || $featured_post_category[0]->slug == 'dons-life') ? 'dons_life_blog' : str_replace('-',
                    '_', $featured_post_category[0]->slug) . '_blog',
            'id' => $featured_post[0]->ID,
            'title' => $featured_post[0]->post_title,
            'excerpt' => tls_make_post_excerpt($featured_post[0]->post_content, 30),
            'link' => get_permalink($featured_post[0]->ID),
            'hero_image' => esc_url($featured_post_hero_image[0])
        );

        /**
         * ===========================================
         *              Spotlight Podcast
         * ===========================================
         */
        $spotlight_podcast = get_field('spotlight_podcast', $page_id);
        $spotlight_podcast_custom_fields = get_post_custom($spotlight_podcast[0]->ID);
        $response['posts'][] = array(
            'type' => 'listen_blog',
            'id' => $spotlight_podcast[0]->ID,
            'title' => $spotlight_podcast[0]->post_title,
            'link' => get_permalink($spotlight_podcast[0]->ID),
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
                $featured_post[0]->ID,
                $spotlight_podcast[0]->ID
            ),
        );

        $wp_query->query = $blogs_archive_args;
        $blogs_archive = $json_api->introspector->get_posts($wp_query->query);

        foreach ($blogs_archive as $blog_post) {

            $categories = wp_get_post_terms($blog_post->id, 'category');
            $blog_post_custom_fields = get_post_custom($blog_post->id);

            $blog_post->soundcloud = $blog_post_custom_fields['soundcloud_embed_code'][0];
            $blog_post->category_url = get_term_link($categories[0]->term_id, $categories[0]->taxonomy);


            if ($categories[0]->slug == 'a-dons-life' || $categories[0]->slug == 'dons-life') {
                $blog_post->type = 'dons_life_blog';
            } else {
                $blog_post->type = str_replace('-', '_', $categories[0]->slug) . '_blog';
            }

            $response['posts'][] = $blog_post;
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
                $blog_post_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($response_post->id));

                if ($blog_post_thumb !== false || $blog_post_thumb !== null) {
                    $response_post->thumbnail = $blog_post_thumb[0];
                }

            }
        }

    }

    return $response;
}

add_filter('json_api_encode', 'tls_blogs_archive_json_api_encode');