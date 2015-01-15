<?php

/**
 * Latest Edition Page Template JSON API Response Modifications
 *
 * @param $response     The JSON API Response Object
 * @return $response    Update JSON API Response Object
 */
function tls_latest_edition_page_json_api_encode($response) {

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

    if ( (isset( $response['page_template_slug'] ) && $response['page_template_slug'] == 'template-latest-edition.php') || $response['post']->type == 'tls_editions' ) {

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

        $previousPost = get_previous_post();
        $response['latest_edition']['previous_post_info'] = array(
            'id'        => $previousPost->ID,
            'title'     => $previousPost->post_title,
            'url'       => get_permalink($previousPost->ID),

        );

        $nextPost = get_next_post();
        $response['latest_edition']['next_post_info'] = array(
            'id'        => $nextPost->ID,
            'title'     => $nextPost->post_title,
            'url'       => get_permalink($nextPost->ID),

        );

        $latest_edition_articles = get_fields($latest_edition->ID);
        //$response['latest_edition']['test_content'] =  $latest_edition_articles['edition_number'];

        $response['latest_edition']['content']['featured'] = array(
            'issue_no'  => $latest_edition_articles['edition_number'],
            'image_url' => $latest_edition_articles['feautured_image']['url']


        );


        $response['latest_edition']['content']['public']['title'] = 'Public content';

        foreach ($latest_edition_articles['public_articles'] as $key => $value) {

            $section = get_the_terms($value->ID,'article_section');


            foreach ($section as $termkey => $termvalue) {
                $section = $termvalue->name;
            }

            $postAuthor = get_fields($value->ID);
            $response['latest_edition']['content']['public']['articles'][$value->post_name] = array(
                'id'        => $value->ID,
                'author'    => $postAuthor['article_author_name'],
                'title'     => $value->post_title,
                'section'   => $section,
                'url'       => get_permalink($value->ID),

            );
        }

        $response['latest_edition']['content']['regulars']['title'] = 'Regulars';
        foreach ($latest_edition_articles['regular_articles'] as $key => $value) {
            $section = get_the_terms($value->ID,'article_section');
            foreach ($section as $termkey => $termvalue) {
                $section = $termvalue->name;
            }
            $postAuthor = get_fields($value->ID);
            $response['latest_edition']['content']['regulars']['articles'][$value->post_name] = array(
                'id'        => $value->ID,
                'author'    => $postAuthor['article_author_name'],
                'title'     => $value->post_title,
                'section'   => $section,
                'url'       => get_permalink($value->ID),

            );
        }


        $response['latest_edition']['content']['subscribers']['title'] = 'Subscriber Exclusive';
        foreach ($latest_edition_articles['subscriber_only_articles'] as $key => $value) {

            $section = get_the_terms($value->ID,'article_section');
            foreach ($section as $termkey => $termvalue) {
                $section = $termvalue->name;

            }
            $postAuthor = get_fields($value->ID);
            $response['latest_edition']['content']['subscribers']['articles'][$section]['section'] = $section;
            $response['latest_edition']['content']['subscribers']['articles'][$section]['posts'][$value->post_name] = array(
                'id'        => $value->ID,
                'author'    => $postAuthor['article_author_name'],
                'title'     => $value->post_title,
                'section'   => $section,
                'url'       => get_permalink($value->ID),

            );
        }

        //$response['latest_edition']['content'] = get_the_terms('2433','article_section');

    }

    return $response;
}
add_filter( 'json_api_encode', 'tls_latest_edition_page_json_api_encode' );