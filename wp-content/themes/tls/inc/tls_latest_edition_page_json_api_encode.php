<?php

/**
 * Latest Edition Page Template JSON API Response Modifications
 *
 * @param $response     The JSON API Response Object
 *
 * @return $response    Update JSON API Response Object
 */
function tls_latest_edition_page_json_api_encode($response)
{

    if ((isset($response['page_template_slug']) && $response['page_template_slug'] == "template-latest-edition.php") || isset($response['post']) && $response['post']->type == 'tls_editions') {

        if ((isset($response['page_template_slug']) && $response['page_template_slug'] == "template-latest-edition.php")) {
            $latest_edition = new WP_Query(array(
                'post_type' => 'tls_editions',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'orderby ' => 'date'
            ));
            wp_reset_query();
        } else {
            if (isset($response['post']) && $response['post']->type == 'tls_editions') {
                $latest_edition = new WP_Query(array(
                    'p' => $response['post']->id,
                    'post_type' => 'tls_editions',
                    'post_status' => 'publish',
                    'posts_per_page' => 1,
                    'orderby ' => 'date'
                ));
                wp_reset_query();
            }
        }
        $latest_edition = $latest_edition->posts[0];

        global $post;
        $oldGlobal = $post;
        $post = get_post($latest_edition->ID);

        $response['latest_edition'] = array(
            'id' => $latest_edition->ID,
            'title' => get_the_title($latest_edition->ID),
            'url' => get_permalink($latest_edition->ID),

        );

        if (get_previous_post()) {
            $previousPost = get_previous_post();
            $response['latest_edition']['previous_post_info'] = array(
                'previous' => $previousPost,
                'id' => $previousPost->ID,
                'title' => get_the_title($previousPost->ID),
                'url' => get_permalink($previousPost->ID),
            );
        }

        if (get_next_post()) {
            $nextPost = get_next_post();
            $response['latest_edition']['next_post_info'] = array(
                'next' => $nextPost,
                'id' => $nextPost->ID,
                'title' => get_the_title($nextPost->ID),
                'url' => get_permalink($nextPost->ID),
            );
        }

        $latest_edition_articles = get_fields($latest_edition->ID);

        $response['latest_edition']['content']['featured'] = array(
            'issue_no' => $latest_edition_articles['edition_number'],
            'image_url' => $latest_edition_articles['feautured_image']['url']


        );


        $response['latest_edition']['content']['public']['title'] = __('Public content', 'tls');
        $public_articles = get_field('public_articles', $latest_edition->ID);
        foreach ($public_articles as $public_article) {

            $section = wp_get_post_terms($public_article->ID, 'article_section');
            $postAuthor = get_field('field_54e4d3b1b0094', $public_article->ID);

            $response['latest_edition']['content']['public']['articles'][$public_article->post_name] = array(
                'id' => $public_article->ID,
                'author' => $postAuthor,
                'title' => get_the_title($public_article->ID),
                'section' => array(
                    'name' => $section[0]->name,
                    'link' => get_term_link($section[0]->term_id, $section[0]->taxonomy)
                ),
                'url' => get_permalink($public_article->ID),

            );
        }

        $response['latest_edition']['content']['regulars']['title'] = __('Regular Features', 'tls');
        $regular_articles = get_field('edition_regular_articles', $latest_edition->ID);
        foreach ($regular_articles as $regular_article) {

            $postAuthor = get_field('field_54e4d3b1b0094', $regular_article->ID);
            $section = wp_get_post_terms($regular_article->ID, 'article_section');
            $article_section = wp_get_post_terms($regular_article->ID, 'article_visibility');

            $response['latest_edition']['content']['regulars']['articles'][$regular_article->post_name] = array(
                'type' => 'article',
                'id' => $regular_article->ID,
                'author' => $postAuthor,
                'title' => get_the_title($regular_article->ID),
                'section' => array(
                    'name' => $section[0]->name,
                    'link' => get_term_link($section[0]->term_id, $section[0]->taxonomy)
                ),
                'taxonomy_article_visibility' => $article_section,
                'url' => get_permalink($regular_article->ID),
            );

        }


        $response['latest_edition']['content']['subscribers']['title'] = __('Subscriber Exclusive');
        $subscriber_only_articles = get_field('subscriber_only_articles', $latest_edition->ID);
        foreach ($subscriber_only_articles as $subscriber_only_article) {

            $section = wp_get_post_terms($subscriber_only_article->ID, 'article_section');
            $postAuthor = get_field('field_54e4d3b1b0094', $subscriber_only_article->ID);

            $response['latest_edition']['content']['subscribers']['articles'][$section[0]->name]['section']->name = $section[0]->name;
            $response['latest_edition']['content']['subscribers']['articles'][$section[0]->name]['section']->link = get_term_link($section[0]->term_id,
                $section[0]->taxonomy);
            $response['latest_edition']['content']['subscribers']['articles'][$section[0]->name]['posts'][$subscriber_only_article->post_name] = array(
                'id' => $subscriber_only_article->ID,
                'author' => $postAuthor,
                'title' => get_the_title($subscriber_only_article->ID),
                'section' => array(
                    'name' => $section[0]->name,
                    'link' => get_term_link($section[0]->term_id, $section[0]->taxonomy)
                ),
                'url' => get_permalink($subscriber_only_article->ID),

            );
        }

    }

    return $response;
}

add_filter('json_api_encode', 'tls_latest_edition_page_json_api_encode');