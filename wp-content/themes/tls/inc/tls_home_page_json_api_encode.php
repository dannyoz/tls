<?php

/**
 * Home Page Template JSON API Response Modifications
 *
 * @param $response     JSON API Response Object
 * @return Object       Updated JSON API Response Object
 */
function tls_home_page_json_api_encode($response) {

    if ( isset( $response['page'] ) && $response['page_template_slug'] == 'template-home.php' ) {

        // Get Home Page ID for this page using Template Home Page Template
        $home_page_id = $response['page']->id;

        // Get Featured Post Details
        $featured_article = get_field( 'featured_article', $home_page_id );

        $images = array(
            'hero_image'        => get_field( 'hero_image_url', $featured_article->ID ),
            'full_image'        => get_field( 'full_image_url', $featured_article->ID ),
            'thumbnail_image'   => get_field( 'thumbnail_image_url', $featured_article->ID )
        );

        $response['featured_article'] = array(
            'id'                => $featured_article->ID,
            'title'             => $featured_article->post_title,
            'author'            => get_the_author_meta( 'display_name', $featured_article->post_author ),
            'text'              => tls_make_post_excerpt( $featured_article, 15 ),
            'link'              => get_permalink( $featured_article->ID ),
            'images'            => $images
        );

        /**
         * Home Page Blog Cards (For the 2 first Blog Cards)
         */
        $blog_cards = get_field('blogs_cards', $home_page_id);
        foreach ( (array)$blog_cards as $blog_card) {
            // Get the categories for the Blog Post
            $categories = wp_get_post_terms( $blog_card->ID, 'category' );

            // Add Blog Post Cards to JSON Response in the first card slot
            $response['home_page_cards']['card_0'][] = array(
                'type'          => 'blog',
                'id'            => $blog_card->ID,
                'title'         => $blog_card->post_title,
                'author'        => get_the_author_meta( 'display_name', $blog_card->post_author ),
                'text'          => tls_make_post_excerpt( $blog_card ),
                'link'          => get_permalink( $blog_card->ID ),
                'section'       => array(
                    'name'      => $categories[0]->name,
                    'link'      => get_term_link( $categories[0]->term_id, $categories[0]->taxonomy )
                ),
            );
        }

        /**
         * Home Page Cards (For the rest of the home page cards)
         */
        $home_page_cards = get_field('home_page_cards', $home_page_id);

        // Start the Cards at 1 since the Home Page Blog Cards already took the place 0
        $home_page_card_count = 1;
        foreach ((array)$home_page_cards as $home_page_card) {

            // Variable that grabs Card Type
            $card_type = $home_page_card['card_type'];
            // Variable that gets the post for the Card Type
            $card_post = $home_page_card[$card_type . '_post'];

            // If Card Type is Article the create variable $section with the article-section taxonomy otherwise use the taxonomy category
            if ( $card_type == 'article' ) {
                $section = wp_get_post_terms( $card_post->ID, 'article_section' );
                $thumbnail_image = get_field( 'thumbnail_image_url', $card_post->ID );
            } else {
                $section = wp_get_post_terms( $card_post->ID, 'category' );
                $thumbnail_image = '';
            }

            // Get Soundcloud Embed Code Custom Field Value using WP get_post_custom to return correct HTML output
            // because ACF get_field in the JSON API was returning all the tags with HTML stripped and encoded
            $card_post_custom_fields = get_post_custom( $card_post->ID );
            $card_post_soundcloud_custom_field = $card_post_custom_fields['soundcloud_embed_code'];

            // Add Cards to the JSON Response in the specific count slot
            $response['home_page_cards']['card_' . $home_page_card_count] = array(
                'type'          => $card_type,
                'id'            => $card_post->ID,
                'title'         => $card_post->post_title,
                'author'        => get_the_author_meta( 'display_name', $card_post->post_author ),
                'text'          => tls_make_post_excerpt( $card_post ),
                'link'          => get_permalink( $card_post->ID ),
                'section'       => array(
                    'name'      => $section[0]->name,
                    'link'      => get_term_link( $section[0]->term_id, $section[0]->taxonomy )
                ),
                'image'         => $thumbnail_image,
                'soundcloud'    => $card_post_soundcloud_custom_field[0]
            );

            // Iterate to next count slot
            $home_page_card_count++;
        }

    }

    return $response;
}
add_filter( 'json_api_encode', 'tls_home_page_json_api_encode' );