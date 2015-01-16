<?php

/**
 * Discover Articles Archive Page Template JSON API Response Modifications
 *
 * @param $response     The JSON API Response Object
 * @return $response    Update JSON API Response Object
 */
function tls_discover_json_api_encode($response) {

    if ( isset( $response['page'] ) &&  $response['page_template_slug'] == 'template-articles-archive.php' ) {
        // Globals to be used with many of the specific searches
        global $json_api, $wp_query;

        /**
         * Top Article from each Article Section
         */

        // Variable to store Top Articles IDs to later on remove from the second list of articles
        $top_section_articles = array();

        // Get all the terms from Article Section Taxonomy
        $article_sections_args = array(
            'hide_empty'    => false,
            'orderby'           => 'name',
            'order'             => 'ASC',
        );
        $article_sections = get_terms( 'article_section', $article_sections_args );

        /**
         * Spotlight Article Section Category
         * Get the latest post and add it to the top section articles with
         * a spotlight attribute to use in FE
         */
        $spotlight_article_section = get_field( 'spotlight_category', $response['page']->id );

        // Arguments for the WP_Query
        $spotlight_article_args = array(
            'post_type'         => 'tls_articles',
            'posts_per_page'    => 1,
            'orderby'           => 'date',
            'order'             => 'DESC',
            'tax_query'         => array( array(
                'taxonomy'      => 'article_section',
                'field'         => 'term_id',
                'terms'         => $spotlight_article_section->term_id
            ) )
        );
        // WP_Query for the Top Article in current section term
        $spotlight_article_query = new WP_Query($spotlight_article_args);

        // Get the first article returned from Query
        $spotlight_article = $spotlight_article_query->posts[0];

        // Add Article ID into the spotlight_articles array
        $top_section_articles[] = $spotlight_article->ID;

        // Get Taxonomies
        $spotlight_article_sections = wp_get_post_terms( $spotlight_article->ID, 'article_section' );
        $spotlight_article_visibility = wp_get_post_terms( $spotlight_article->ID, 'article_visibility' );

        // Get all custom fields
        $spotlight_article_custom_fields = get_post_custom( $spotlight_article->ID );
        // Get Specific Custom Fields from the Custom Fields
        $spotlight_article_teaser = $spotlight_article_custom_fields['teaser_summary'];
        $spotlight_article_thumbnail = $spotlight_article_custom_fields['thumbnail_image_url'][0];

        // Add this article into top_articles array from the JSON Response Object
        $response['top_articles'][] = array(
            'spotlight'                     => true,
            'type'                          => 'article',
            'id'                            => $spotlight_article->ID,
            'url'                           => get_permalink( $spotlight_article->ID ),
            'title'                         => $spotlight_article->post_title,
            'excerpt'                       => tls_make_post_excerpt( $spotlight_article, 15 ),
            'author'                        => array(
                'name'                      => get_the_author_meta( 'display_name', $spotlight_article->post_author ),
                'slug'                      => get_the_author_meta( 'slug', $spotlight_article->post_author ),
            ),
            'custom_fields'                 => array(
                'thumbnail_image_url'       => $spotlight_article_thumbnail,
                'teaser_summary'            => ( $spotlight_article_teaser ) ? $spotlight_article_teaser : tls_make_post_excerpt( $spotlight_article, 15 ),
                'book_title'                => $spotlight_article_custom_fields['book_title'],
                'book_author'               => $spotlight_article_custom_fields['book_author'],
                'book_info_1'               => $spotlight_article_custom_fields['book_info_1'],
                'book_info_2'               => $spotlight_article_custom_fields['book_info_2'],
                'book_isbn'                 => $spotlight_article_custom_fields['book_isbn']
            ),
            'taxonomy_article_section'      => $spotlight_article_sections,
            'taxonomy_article_section_url'  => get_term_link( $spotlight_article_sections[0]->term_id, $spotlight_article_sections[0]->taxonomy),
            'taxonomy_article_visibility'   => $spotlight_article_visibility,
        );

        /**
         * Get All Other Top Articles per Article Section Category
         * Removing the article from the spotlight section
         */
        // Loop through each of the sections to make a WP_Query
        foreach ($article_sections as $section) {

            if (  $section->term_id != $spotlight_article_section->term_id ) {
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
                $top_section_article_teaser = $top_section_article_custom_fields['teaser_summary'];
                $top_section_article_thumbnail = $top_section_article_custom_fields['thumbnail_image_url'][0];

                // Add this article into top_articles array from the JSON Response Object
                $response['top_articles'][] = array(
                    'type' => 'article',
                    'id' => $top_section_article->ID,
                    'url' => get_permalink($top_section_article->ID),
                    'title' => $top_section_article->post_title,
                    'excerpt' => tls_make_post_excerpt($top_section_article, 15),
                    'author' => array(
                        'name' => get_the_author_meta('display_name', $top_section_article->post_author),
                        'slug' => get_the_author_meta('slug', $top_section_article->post_author),
                    ),
                    'custom_fields' => array(
                        'thumbnail_image_url' => $top_section_article_thumbnail,
                        'teaser_summary' => ( $top_section_article_teaser ) ? $top_section_article_teaser : tls_make_post_excerpt($top_section_article, 15),
                        'book_title'                => $top_section_article_custom_fields['book_title'],
                        'book_author'               => $top_section_article_custom_fields['book_author'],
                        'book_info_1'               => $top_section_article_custom_fields['book_info_1'],
                        'book_info_2'               => $top_section_article_custom_fields['book_info_2'],
                        'book_isbn'                 => $top_section_article_custom_fields['book_isbn']
                    ),
                    'taxonomy_article_section' => $top_section_article_section,
                    'taxonomy_article_section_url' => get_term_link($top_section_article_section[0]->term_id, $top_section_article_section[0]->taxonomy),
                    'taxonomy_article_visibility' => $top_section_article_visibility,
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
            'post_type'         => array( 'tls_articles' ),
            'orderby'           => 'date',
            'order'             => 'DESC',
            'paged'             => $paged,
            'post__not_in'      => $top_section_articles // Array of ID's of Top Articles to remove
        );

        // Override query inside the $wp_query global to be able to use the JSON API get_posts
        $wp_query->query = $articles_archive_args;
        $articles_archive = $json_api->introspector->get_posts($wp_query->query);

        foreach ( $articles_archive as $article_post ) {
            $article_section_terms = wp_get_post_terms( $article_post->id, 'article_section' );
            $article_custom_fields = get_post_custom( $article_post->id );
            $article_post->custom_fields->thumbnail_image_url = $article_custom_fields['thumbnail_image_url'][0];
            $article_post->custom_fields->teaser_summary = ( $article_custom_fields['teaser_summary'] ) ? $article_custom_fields['teaser_summary'] : tls_make_post_excerpt( $article_post );
            $article_post->type = 'article';
            $article_post->taxonomy_article_section_url = get_term_link( $article_section_terms[0]->term_id, $article_section_terms[0]->taxonomy );
        }

        // Update JSON Response Object for the main Articles Archive
        $response['count'] = count($articles_archive);
        $response['count_total'] = (int) $wp_query->found_posts;
        $response['pages'] = $wp_query->max_num_pages;
        $response['posts'] = $articles_archive;

    }

    return $response;
}
add_filter( 'json_api_encode', 'tls_discover_json_api_encode' );