<?php

/**
 * Home Page Template JSON API Response Modifications
 *
 * @param $response     JSON API Response Object
 *
 * @return Object       Updated JSON API Response Object
 */
function tls_home_page_json_api_encode($response)
{

    if (isset($response['page']) && $response['page_template_slug'] == 'template-home.php') {

        // Get Home Page ID for this page using Template Home Page Template
        $home_page_id = $response['page']->id;

        // Get Featured Post Details
        $featured_article = get_field('featured_article', $home_page_id);

        $hero_image_url = '';
        if (get_field('hero_image_url', $featured_article[0]->ID)) {
            $hero_image = get_field('hero_image_url', $featured_article[0]->ID);
            $hero_image_url = $hero_image['url'];
        } else if (get_field('full_image_url', $featured_article[0]->ID)) {
            $hero_image = get_field('full_image_url', $featured_article[0]->ID);
            $hero_image_url = $hero_image['url'];
        }

        $response['featured_article'] = array(
            'id' => $featured_article[0]->ID,
            'title' => $featured_article[0]->post_title,
            'author' => get_the_author_meta('display_name', $featured_article[0]->post_author),
            'text' => tls_make_post_excerpt($featured_article[0]->post_content, 30),
            'link' => get_permalink($featured_article[0]->ID),
            'hero_image' => esc_url($hero_image_url)
        );

        /**
         * Home Page Blog Cards (For the 2 first Blog Cards)
         */
        $blog_cards = get_field('blogs_cards', $home_page_id);
        foreach ((array)$blog_cards as $blog_card) {
            // Get the categories for the Blog Post
            $categories = wp_get_post_terms($blog_card->ID, 'category');
            // Get the blog post featured image as thumbnail and then get the URL for it
            $thumbnail_id = get_post_thumbnail_id($blog_card->ID);
            $thumbnail_array = wp_get_attachment_image_src($thumbnail_id, 'thumbnail');

            // Add Blog Post Cards to JSON Response in the first card slot
            $response['home_page_cards']['card_0'][] = array(
                'type' => 'blog',
                'id' => $blog_card->ID,
                'title' => $blog_card->post_title,
                'author' => get_the_author_meta('display_name', $blog_card->post_author),
                'text' => tls_make_post_excerpt($blog_card, 15),
                'link' => get_permalink($blog_card->ID),
                'section' => array(
                    'name' => $categories[0]->name,
                    'link' => get_term_link($categories[0]->term_id, $categories[0]->taxonomy)
                ),
                'thumbnail' => $thumbnail_array[0]
            );
        }

        /**
         * Home Page Cards (For the rest of the home page cards)
         */
        $home_page_cards = get_field('home_page_cards', $home_page_id);

        // Start the Cards at 1 since the Home Page Blog Cards already took the place 0
        $home_page_card_count = 1;
        foreach ((array)$home_page_cards as $home_page_card) {

            // Variable that grabs Card Type from Custom fields
            $card_type = $home_page_card['card_type'];
            // Variable that gets the post for the Card Type and adds _post to match the Post Object Custom field in the backend
            $card_post = $home_page_card[$card_type . '_post'][0];

            // If Card Type is Article the create variable $section with the article-section taxonomy otherwise use the taxonomy category
            if ($card_type == 'article') {
                $section = wp_get_post_terms($card_post->ID, 'article_section');
                $visibility = wp_get_post_terms($card_post->ID, 'article_visibility');
            } else {
                $section = wp_get_post_terms($card_post->ID, 'category');
            }

            // Get Custom Field Value using WP get_post_custom to return correct HTML output
            // because ACF get_field in the JSON API was returning all the tags with HTML stripped and encoded
            $card_post_custom_fields = get_post_custom($card_post->ID);
            $card_post_soundcloud_custom_field = $card_post_custom_fields['soundcloud_embed_code'];
            $teaserSummary = $card_post_custom_fields['teaser_summary'];

            // IF there is a teaser use it otherwise make one
            if (!empty($teaserSummary)) {
                $articleText = $teaserSummary;
            } else {
                $articleText = tls_make_post_excerpt($card_post->post_content, 30);
            }

            if (!empty($card_post_custom_fields['thumbnail_image_url'][0])) {
                $thumbnail_image = wp_get_attachment_url($card_post_custom_fields['thumbnail_image_url'][0]);
            } else if (!empty($card_post_custom_fields['full_image_url'][0])) {
                $thumbnail_image = wp_get_attachment_url($card_post_custom_fields['full_image_url'][0]);
            }

            // Add Cards to the JSON Response in the specific count slot
            $response['home_page_cards']['card_' . $home_page_card_count] = array(
                'type' => $card_type,
                'id' => $card_post->ID,
                'title' => $card_post->post_title,
                'author' => get_the_author_meta('display_name', $card_post->post_author),
                'excerpt' => $articleText,
                'link' => get_permalink($card_post->ID),
                'section' => array(
                    'name' => $section[0]->name,
                    'link' => get_term_link($section[0]->term_id, $section[0]->taxonomy)
                ),
                'soundcloud' => $card_post_soundcloud_custom_field[0],
                'custom_fields' => array(
                    'thumbnail_image_url' => $thumbnail_image,
                ),
                'books' => get_field('field_54edde1e60d80', $card_post->ID),
                'taxonomy_article_visibility' => $visibility
            );

            // If the current Home Page card is a Blog Post
            if (get_post_type($card_post->ID) == "post") {
                // Get a blog post's featured image as a thumbnail and then get its URL
                $card_thumbnail_id = get_post_thumbnail_id($card_post->ID);
                $card_thumbnail_array = wp_get_attachment_image_src($card_thumbnail_id, 'thumbnail');

                // Add a thumbnail to the JSON Response only for the Blog posts
                $response['home_page_cards']['card_' . $home_page_card_count]['thumbnail'] = $card_thumbnail_array[0];
            } // END If

            // Iterate to next count slot
            $home_page_card_count++;
        }

    }

    return $response;
}

add_filter('json_api_encode', 'tls_home_page_json_api_encode');