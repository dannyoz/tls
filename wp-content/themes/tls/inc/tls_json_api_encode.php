<?php

/**
 * Generic / Misc JSON API Response Modifications
 *
 * @param $response     JSON API Response Object
 *
 * @return Object       Updated JSON API Response Object
 */
function tls_json_api_encode($response)
{
    // Globals to be used with many of the specific searches
    global $json_api, $wp_query;

    /**
     *  Single Post Specific JSON API Response
     */
    if (isset($response['post'])) {
        $previous_post = get_previous_post();
        $next_post = get_next_post();

        // Previous Post
        if (!empty($previous_post)) {
            $response['post']->previous_post_info = array(
                'url' => get_permalink($previous_post->ID),
                'title' => $previous_post->post_title
            );
        }

        // Next Post
        if (!empty($next_post)) {
            $response['post']->next_post_info = array(
                'url' => get_permalink($next_post->ID),
                'title' => $next_post->post_title
            );
        }

        /**
         * Change Content Length For Private Articles with Akamai Teaser View set true
         */
        if ($response['post']->type == 'tls_articles' && $response['post']->taxonomy_article_visibility[0]->slug == 'private') {

            $url = parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $urlQuery);
            if (isset($urlQuery['akamai-teaser']) && $urlQuery['akamai-teaser'] == 'true') {
                $article_post = get_post($response['post']->id);
                $response['post']->akamai_teaser = true;
                $response['post']->content = tls_limit_content($article_post->post_content, 150);
            }
        }

        /**
         * Taxonomy Section
         */
        if ($response['post']->type == 'tls_articles') {
            $article_section_terms = wp_get_post_terms($response['post']->id, 'article_section');
            $response['post']->section = array(
                'name'  => $article_section_terms[0]->name,
                'link'  => get_term_link($article_section_terms[0]->term_id, $article_section_terms[0]->taxonomy)
            );
        } else if ($response['post']->type == 'post') {
            $post_category_terms = wp_get_post_terms($response['post']->id, 'category');
            $response['post']->category = array(
                'name'  => $post_category_terms[0]->name,
                'link'  => get_term_link($post_category_terms[0]->term_id, $post_category_terms[0]->taxonomy)
            );
        }

        /**
         * Single Blog Post
         */
        // Commenter Information
        if (!is_user_logged_in()) {
            $commenter_info = wp_get_current_commenter();
            $comment_author = $commenter_info['comment_author'];
            $comment_author_email = $commenter_info['comment_author_email'];
        } else {
            global $current_user;
            get_currentuserinfo();
            $comment_author = $current_user->display_name;
            $comment_author_email = $current_user->user_email;
        }

        $response['post']->commenter_information = array(
            'comment_author' => $comment_author,
            'comment_author_email' => $comment_author_email
        );

        /**
         * Subscribe Section
         */
        $theme_options = get_option('theme_options_settings');
        $subscribe_url = $theme_options['subscribe_link'];
        $subscribe_text = $theme_options['subscribe_text'];

        $latest_edition = new WP_Query(array(
            'post_type' => 'tls_editions',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'orderby ' => 'date'
        ));
        $featured_image = get_field('field_54ad3f64b4697', $latest_edition->posts[0]->ID);

        $response['post']->subscribe_section = array(
            'image_url'         => esc_url($featured_image['url']),
            'subscribe_link'    => esc_url($subscribe_url),
            'subscribe_text'    => wp_strip_all_tags($subscribe_text)
        );

        // Books
        $response['post']->books = get_field('field_54edde1e60d80', $response['post']->id);

        // Images
        $hero_image = get_field('field_54e4d4b3b009c', $response['post']->id);
        $response['post']->custom_fields->hero_image_url = $hero_image['url'];

        $full_image = get_field('field_54e4d4a5b009b', $response['post']->id);
        $response['post']->custom_fields->full_image_url = $full_image['url'];

        $thumbnail_image = get_field('field_54e4d481b009a', $response['post']->id);
        $response['post']->custom_fields->thumbnail_image_url = $thumbnail_image['url'];

    }

    /**
     * Posts Archive (get_posts) from JSON API - Especially when getting
     * Related Articles through the API
     */
    if (isset($response['posts']) && $wp_query->is_search == false) {
        if (isset($_GET['exclude_post']) && intval($_GET['exclude_post'])) {
            $current_query = $wp_query->query;

            $exclude_post_args = array(
                'post__not_in'  => array((int) $_GET['exclude_post'])
            );
            $exclude_post_archive_query = array_merge($current_query, $exclude_post_args);

            $exclude_post_archive = $json_api->introspector->get_posts($exclude_post_archive_query);
            $response['count'] = count($exclude_post_archive);
            $response['count_total'] = (int)$wp_query->found_posts;
            $response['pages'] = $wp_query->max_num_pages;
            $response['posts'] = $exclude_post_archive;
        }

        foreach ($response['posts'] as $response_post) {
            if ($response_post->type == 'tls_articles') {
                // Books
                $response_post->books = get_field('field_54edde1e60d80', $response_post->id);

                // Images
                $hero_image = get_field('field_54e4d4b3b009c', $response_post->id);
                $response_post->custom_fields->hero_image_url = $hero_image['url'];

                $full_image = get_field('field_54e4d4a5b009b', $response_post->id);
                $response_post->custom_fields->full_image_url = $full_image['url'];

                $thumbnail_image = get_field('field_54e4d481b009a', $response_post->id);
                $response_post->custom_fields->thumbnail_image_url = $thumbnail_image['url'];
            }
        }

    }

    /**
     * Page Template Specific JSON API Responses
     */
    if (isset($response['page'])) {
        // Add page template slug to JSON API Response
        $response['page_template_slug'] = get_page_template_slug($response['page']->id);
    }

    /**
     * Page with template-page-with-accordion.php Page template JSON API Response
     */
    if (isset($response['page']) && $response['page_template_slug'] == 'template-page-with-accordion.php') { // If it is template-page-with-accordion.php Page template
        $response['page']->accordion_items = get_field('accordion', $response['page']->id);
    }

    return $response;
}

add_filter('json_api_encode', 'tls_json_api_encode');