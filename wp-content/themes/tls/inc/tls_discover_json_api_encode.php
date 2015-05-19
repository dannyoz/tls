<?php

/**
 * Discover Articles Archive Page Template JSON API Response Modifications
 *
 * @param $response     The JSON API Response Object
 *
 * @return $response    Update JSON API Response Object
 */
function tls_discover_json_api_encode($response)
{

    if (isset($response['page']) && $response['page_template_slug'] == 'template-articles-archive.php') {
        // Globals to be used with many of the specific searches
        global $json_api, $wp_query;

        /**
         * Top Article from each Article Section
         */

        // Variable to store Top Articles IDs to later on remove from the second list of articles
        $top_section_articles = array();

        // Get all the terms from Article Section Taxonomy
        $article_sections_args = array(
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC',
        );
        $article_sections = get_terms('article_section',
            $article_sections_args); // Get all terms for Article Section taxonomy

        $show_sections = array(); // Start empty array to inject sections that can be shown in discover page
        $show_sections_ids = array(); // empty array to use later for wp query to search posts in these terms only
        foreach ($article_sections as $show_section) {
            $show_in_discover_page = get_field('show_in_discover_page',
                'article_section_' . $show_section->term_id); // get the show in discover page for current term

            /*
             * If the term has the option set to 'yes' for show in discover page then add
             * Term Object to the show sections and term ids to show_section_ids
             */
            if ($show_in_discover_page == 'yes') {
                $show_sections[] = $show_section;
                $show_sections_ids[] = $show_section->term_id;
            }
        }
        $article_sections = $show_sections; // Reassign article_sections to use later on

        /**
         * Spotlight Article Section Category
         * Get the latest post and add it to the top section articles with
         * a spotlight attribute to use in FE
         */
        $spotlight_article_section = get_field('spotlight_category', $response['page']->id);

        // Arguments for the WP_Query
        $spotlight_article_args = array(
            'post_type' => 'tls_articles',
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'article_section',
                    'field' => 'term_id',
                    'terms' => $spotlight_article_section
                )
            )
        );
        // WP_Query for the Top Article in current section term
        $spotlight_article_query = new WP_Query($spotlight_article_args);

        // Get the first article returned from Query
        $spotlight_article = $spotlight_article_query->posts[0];

        // Add Article ID into the spotlight_articles array
        $top_section_articles[] = $spotlight_article->ID;

        // Get Taxonomies
        $spotlight_article_sections = wp_get_post_terms($spotlight_article->ID, 'article_section');
        $spotlight_article_visibility = wp_get_post_terms($spotlight_article->ID, 'article_visibility');

        // Get all custom fields
        $spotlight_article_custom_fields = get_post_custom($spotlight_article->ID);
        // Get Specific Custom Fields from the Custom Fields
        $spotlight_article_teaser = $spotlight_article_custom_fields['teaser_summary'][0];
        $spotlight_full_image = get_field('field_54e4d4a5b009b', $spotlight_article->ID);
        $spotlight_thumbnail_image = get_field('field_54e4d481b009a', $spotlight_article->ID);
        $spotlight_image = '';
         if ($spotlight_thumbnail_image) {
             $spotlight_image = $spotlight_thumbnail_image['url'];
         } else if ($spotlight_full_image) {
             $spotlight_image = $spotlight_full_image['url'];
         }

        // Add this article into top_articles array from the JSON Response Object
        $response['top_articles'][] = array(
            'spotlight' => true,
            'type' => 'article',
            'id' => $spotlight_article->ID,
            'url' => get_permalink($spotlight_article->ID),
            'title' => get_the_title($spotlight_article->ID),
            //'excerpt' => tls_make_post_excerpt($spotlight_article->post_content, 30),
            'author' => array(
                'name' => get_the_author_meta('display_name', $spotlight_article->post_author),
                'slug' => get_the_author_meta('slug', $spotlight_article->post_author),
            ),
            'custom_fields' => array(
                'thumbnail_image_url' => $spotlight_image,
                'teaser_summary' => (!empty($spotlight_article_teaser)) ?: tls_make_post_excerpt($spotlight_article, 30),
            ),
            'taxonomy_article_section' => $spotlight_article_sections,
            'taxonomy_article_section_url' => get_term_link($spotlight_article_sections[0]->term_id,
                $spotlight_article_sections[0]->taxonomy),
            'taxonomy_article_visibility' => $spotlight_article_visibility,
            'books' => get_field('books', $spotlight_article->ID),
        );

        /**
         * Get All Other Top Articles per Article Section Category
         * Removing the article from the spotlight section
         */
        // Loop through each of the sections to make a WP_Query
        foreach ($article_sections as $section) {

            if ($section->term_id != $spotlight_article_section) {
                // Arguments for the WP_Query
                $top_section_article_args = array(
                    'post_type' => 'tls_articles',
                    'posts_per_page' => 1,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'article_section',
                            'field' => 'term_id',
                            'terms' => $section->term_id
                        )
                    )
                );

                // WP_Query for the Top Article in current section term
                $top_section_article_query = new WP_Query($top_section_article_args);

                // Get the first article returned from Query
                $top_section_article = $top_section_article_query->posts[0];

                // Add Article ID into the top_section_articles array
                $top_section_articles[] = $top_section_article->ID;

                // Get Taxonomies
                $top_section_article_section = wp_get_post_terms($top_section_article->ID, 'article_section');
                $top_section_article_visibility = wp_get_post_terms($top_section_article->ID, 'article_visibility');

                // Get all custom fields
                $top_section_article_custom_fields = get_post_custom($top_section_article->ID);
                // Get Specific Custom Fields from the Custom Fields
                $top_section_article_teaser = $top_section_article_custom_fields['teaser_summary'][0];
                if (empty($top_section_article_teaser)) {
                    $top_section_article_teaser = tls_make_post_excerpt($top_section_article->post_content, 30);
                }
                $top_section_full_image = get_field('field_54e4d4a5b009b', $top_section_article->ID);
                $top_section_thumbnail_image = get_field('field_54e4d481b009a', $top_section_article->ID);
                $top_section_image = '';
                if ($top_section_thumbnail_image) {
                    $top_section_image = $top_section_thumbnail_image['url'];
                } else if ($top_section_full_image) {
                    $top_section_image = $top_section_full_image['url'];
                }

                // Add this article into top_articles array from the JSON Response Object
                $response['top_articles'][] = array(
                    'type' => 'article',
                    'id' => $top_section_article->ID,
                    'url' => get_permalink($top_section_article->ID),
                    'title' => get_the_title($top_section_article->ID),
                    //'excerpt' => $top_section_article_teaser,
                    'author' => array(
                        'name' => get_the_author_meta('display_name', $top_section_article->post_author),
                        'slug' => get_the_author_meta('slug', $top_section_article->post_author),
                    ),
                    'custom_fields' => array(
                        'thumbnail_image_url' => $top_section_image,
                        'teaser_summary' => $top_section_article_teaser,
                    ),
                    'taxonomy_article_section' => $top_section_article_section,
                    'taxonomy_article_section_url' => get_term_link($top_section_article_section[0]->term_id,
                        $top_section_article_section[0]->taxonomy),
                    'taxonomy_article_visibility' => $top_section_article_visibility,
                    'books' => get_field('field_54edde1e60d80', $top_section_article->ID),
                );

            }

        } // END Loop of top Articles from each Article Section

        /**
         * All articles excluding the ones in the Top Articles Section
         */

        // Set up paged variable for the query
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        // WP_Query Arguments
        $articles_archive_args = array(
            'post_type' => array('tls_articles'),
            'orderby' => 'date',
            'order' => 'DESC',
            'paged' => $paged,
            'post__not_in' => $top_section_articles,
            // Array of ID's of Top Articles to remove
            'tax_query' => array(
                array(
                    'taxonomy' => 'article_section',
                    'field' => 'term_id',
                    'terms' => $show_sections_ids
                )
            )
        );

        // Override query inside the $wp_query global to be able to use the JSON API get_posts
        $wp_query->query = $articles_archive_args;
        $articles_archive = $json_api->introspector->get_posts($wp_query->query);

        foreach ($articles_archive as $article_post) {
            $article_section_terms = wp_get_post_terms($article_post->id, 'article_section');
            $article_custom_fields = get_post_custom($article_post->id);
            $article_post_full_image = get_field('field_54e4d4a5b009b', $article_post->id);
            $article_post_thumbnail_image = get_field('field_54e4d481b009a', $article_post->id);
            $article_post_image = '';
            if ($article_post_thumbnail_image) {
                $article_post_image = $article_post_thumbnail_image['url'];
            } else if ($article_post_full_image) {
                $article_post_image = $article_post_full_image['url'];
            }
            $article_post->custom_fields->thumbnail_image_url = $article_post_image;
            //$article_post->excerpt = (empty($article_custom_fields['teaser_summary'][0])) ? tls_make_post_excerpt($article_post->content, 30) : wp_strip_all_tags($article_custom_fields['teaser_summary'][0]); // Teaser Summary
            unset($article_post->excerpt);
            $article_post->type = 'article';
            $article_post->taxonomy_article_section_url = get_term_link($article_section_terms[0]->term_id,
                $article_section_terms[0]->taxonomy);
            $article_post->books = get_field('field_54edde1e60d80', $article_post->id);
        }

        // Update JSON Response Object for the main Articles Archive
        $response['count'] = count($articles_archive);
        $response['count_total'] = (int)$wp_query->found_posts;
        $response['pages'] = $wp_query->max_num_pages;
        $response['posts'] = $articles_archive;

    }

    return $response;
}

add_filter('json_api_encode', 'tls_discover_json_api_encode');