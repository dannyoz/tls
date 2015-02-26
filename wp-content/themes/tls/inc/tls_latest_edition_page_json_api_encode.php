<?php

/**
 * Latest Edition Page Template JSON API Response Modifications
 *
 * @param $response     The JSON API Response Object
 *
 * @return $response    Update JSON API Response Object
 */
function tls_latest_edition_page_json_api_encode( $response )
{

    /*if ($response['post']->type == 'tls_editions') {
        
         $latest_edition = new WP_Query( array(
            'post_type'         => 'tls_editions',
            'post_status'       => 'publish',
            'posts_per_page'    => 1,
            'orderby '          => 'date'
        ) ); wp_reset_query();
        $latest_edition = $latest_edition->posts[0];

        global $post;
        $oldGlobal = $post;
        $post = get_post( $latest_edition->ID );
        // $response['debug'] = get_previous_post();

        $response['latest_edition'] = array(
            'id'        => $latest_edition->ID,
            'title'     => $latest_edition->post_title,
            'url'       => get_permalink($latest_edition->ID),

        );
    }*/

    if ( ( isset( $response[ 'page_template_slug' ] ) && $response[ 'page_template_slug' ] == "template-latest-edition.php" ) || isset( $response[ 'post' ] ) && $response[ 'post' ]->type == 'tls_editions' ) {

        if ( ( isset( $response[ 'page_template_slug' ] ) && $response[ 'page_template_slug' ] == "template-latest-edition.php" ) ) {
            $latest_edition = new WP_Query( array( 'post_type' => 'tls_editions', 'post_status' => 'publish', 'posts_per_page' => 1, 'orderby ' => 'date' ) );
            wp_reset_query();
        } else if ( isset( $response[ 'post' ] ) && $response[ 'post' ]->type == 'tls_editions' ) {
            $latest_edition = new WP_Query( array( 'p' => $response[ 'post' ]->id, 'post_type' => 'tls_editions', 'post_status' => 'publish', 'posts_per_page' => 1, 'orderby ' => 'date' ) );
            wp_reset_query();
        }
        $latest_edition = $latest_edition->posts[ 0 ];

        global $post;
        $oldGlobal = $post;
        $post = get_post( $latest_edition->ID );

        $response[ 'latest_edition' ] = array( 'id' => $latest_edition->ID, 'title' => $latest_edition->post_title, 'url' => get_permalink( $latest_edition->ID ),

        );

        if ( get_previous_post() ) {
            $previousPost = get_previous_post();
            $response[ 'latest_edition' ][ 'previous_post_info' ] = array( 'previous' => $previousPost, 'id' => $previousPost->ID, 'title' => $previousPost->post_title, 'url' => get_permalink( $previousPost->ID ), );
        }

        if ( get_next_post() ) {
            $nextPost = get_next_post();
            $response[ 'latest_edition' ][ 'next_post_info' ] = array( 'next' => $nextPost, 'id' => $nextPost->ID, 'title' => $nextPost->post_title, 'url' => get_permalink( $nextPost->ID ), );
        }

        $latest_edition_articles = get_fields( $latest_edition->ID );

        $response[ 'latest_edition' ][ 'content' ][ 'featured' ] = array( 'issue_no' => $latest_edition_articles[ 'edition_number' ], 'image_url' => $latest_edition_articles[ 'feautured_image' ][ 'url' ]


        );


        $response[ 'latest_edition' ][ 'content' ][ 'public' ][ 'title' ] = 'Public content';

        foreach ( $latest_edition_articles[ 'public_articles' ] as $key => $value ) {

            $section = wp_get_post_terms( $value->ID, 'article_section' );

            $postAuthor = get_fields( $value->ID );
            $response[ 'latest_edition' ][ 'content' ][ 'public' ][ 'articles' ][ $value->post_name ] = array( 'id' => $value->ID, 'author' => $postAuthor[ 'article_author_name' ], 'title' => $value->post_title, 'section' => array( 'name' => $section[ 0 ]->name, 'link' => get_term_link( $section[ 0 ]->term_id, $section[ 0 ]->taxonomy ) ), 'url' => get_permalink( $value->ID ),

            );
        }

        $response[ 'latest_edition' ][ 'content' ][ 'regulars' ][ 'title' ] = 'Regulars';
        while ( have_rows( 'edition_regular_articles', $latest_edition->ID ) ) {
            the_row();
            $article_type = get_sub_field( 'regular_article_type' );
            $regular_article = get_sub_field( 'regular_article' );
            $postAuthor = get_field( 'article_author_name', $value->ID );

            $section = wp_get_post_terms( $regular_article->ID, 'article_section' );
            $artile_section = wp_get_post_terms( $regular_article->ID, 'article_visibility' );

            $response[ 'latest_edition' ][ 'content' ][ 'regulars' ][ 'articles' ][ $regular_article->post_name ] = array( 'type' => $article_type, 'id' => $regular_article->ID, 'author' => $postAuthor, 'title' => $regular_article->post_title, 'section' => array( 'name' => $section[ 0 ]->name, 'link' => get_term_link( $section[ 0 ]->term_id, $section[ 0 ]->taxonomy ) ), 'taxonomy_article_visibility' => $artile_section, 'url' => get_permalink( $regular_article->ID ), );

        }


        $response[ 'latest_edition' ][ 'content' ][ 'subscribers' ][ 'title' ] = 'Subscriber Exclusive';
        foreach ( $latest_edition_articles[ 'subscriber_only_articles' ] as $key => $value ) {

            $section = wp_get_post_terms( $value->ID, 'article_section' );

            $postAuthor = get_fields( $value->ID );
            $response[ 'latest_edition' ][ 'content' ][ 'subscribers' ][ 'articles' ][ $section[ 0 ]->name ][ 'section' ]->name = $section[ 0 ]->name;
            $response[ 'latest_edition' ][ 'content' ][ 'subscribers' ][ 'articles' ][ $section[ 0 ]->name ][ 'section' ]->link = get_term_link( $section[ 0 ]->term_id, $section[ 0 ]->taxonomy );
            $response[ 'latest_edition' ][ 'content' ][ 'subscribers' ][ 'articles' ][ $section[ 0 ]->name ][ 'posts' ][ $value->post_name ] = array( 'id' => $value->ID, 'author' => $postAuthor[ 'article_author_name' ], 'title' => $value->post_title, 'section' => array( 'name' => $section[ 0 ]->name, 'link' => get_term_link( $section[ 0 ]->term_id, $section[ 0 ]->taxonomy ) ), 'url' => get_permalink( $value->ID ),

            );
        }

    }

    return $response;
}

add_filter( 'json_api_encode', 'tls_latest_edition_page_json_api_encode' );