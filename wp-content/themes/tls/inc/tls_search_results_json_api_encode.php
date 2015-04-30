<?php

/**
 * Search Results JSON API Response Modifications
 *
 * @param $response     JSON API Response Object
 *
 * @return Object       Updated JSON API Response Object
 */
function tls_search_results_json_api_encode($response)
{
    // Globals to be used with many of the specific searches
    global $json_api, $wp_query;

    /**
     * Search Page Specific
     */
    if (isset($response['posts']) && $wp_query->is_search == true) {

        // URL parsing to use with custom JSON API queries
        $url = parse_url($_SERVER['REQUEST_URI']);
        $url_query = wp_parse_args($url['query']);

        // Get Search Query to be used in all the queries
        $search_query = get_search_query();
        $response['search_query'] = $search_query;

        // Get Current Page and add it to the response
        $page_number = $wp_query->query_vars['paged'];
        $response['page_number'] = $page_number;

        // Get Current WP Query;
        $current_query = (array)$wp_query->query;
        $response['wp_query'] = $wp_query;

        /**
         * Add Date filtering with custom date_filter option in GET request
         * then return the new posts with the date filtering
         */
        if (isset($_GET['date_filter']) && $_GET['date_filter'] != '') {

            $date_posts_archive_args = array(
                'date_query' => array(
                    array(
                        'column' => 'post_date',
                        'after' => $url_query['date_filter'],
                        'inclusive' => true
                    ),
                ),
            );
            $date_posts_archive_query = array_merge((array)$current_query, $date_posts_archive_args);

            $date_posts_archive = $json_api->introspector->get_posts($date_posts_archive_query);
            $response['count'] = count($date_posts_archive);
            $response['count_total'] = (int)$wp_query->found_posts;
            $response['pages'] = $wp_query->max_num_pages;
            $response['posts'] = $date_posts_archive;
        }

        /**
         * ===========================================================
         *                 Search Filters + Filter Count
         * ===========================================================
         */

        $current_query = (isset($_GET['date_filter']) && $_GET['date_filter'] != '') ? array_merge((array)$current_query, $date_posts_archive_args) : $current_query;

        /**
         * Public Articles Taxonomy Term Filter
         */
        $public_articles_args = array('article_visibility' => 'public');
        $public_articles_query = array_merge((array)$current_query, $public_articles_args);
        $public_articles = new WP_Query($public_articles_query);
        wp_reset_query();

        $response['content_type_filters']['2_public_articles'] = array(
            'item_label' => __('Free To Non Subscribers', 'tls'),
            'json_query' => 'post_type=tls_articles&article_visibility=public',
            'search_count' => (int)$public_articles->found_posts
        );

        /**
         * Reviews (Articles) Post Type
         */
        $reviews_args = array(
            'post_type' => 'tls_articles'
        );
        $reviews_query = array_merge((array)$current_query, $reviews_args);
        $reviews = new WP_Query($reviews_query);
        wp_reset_query();

        $response['content_type_filters']['1_reviews'] = array(
            'item_label' => __('Reviews', 'tls'),
            'json_query' => 'post_type=tls_articles',
            'search_count' => ($current_query['post_type'] == 'tls_articles' || $wp_query->query_vars['post_type'] == 'any') ? $reviews->found_posts : 0,
        );

        /**
         * TLS Blogs Post Type
         */
        $blogs_args = array(
            'post_type' => 'post'
        );
        $blogs_query = array_merge((array)$current_query, $blogs_args);
        $blogs = new WP_Query($blogs_query);
        wp_reset_query();

        $response['content_type_filters']['3_blogs'] = array(
            'item_label' => __('Blogs', 'tls'),
            'json_query' => 'post_type=post',
            'search_count' => (int)($current_query['post_type'] == 'post' || $wp_query->query_vars['post_type'] == 'any') ? $blogs->found_posts : 0,
        );

        /**
         * FAQs Post Type
         */
        $faqs_args = array(
            'post_type' => 'tls_faq',
            'posts_per_page' => 1
        );
        $faqs_query = array_merge((array)$current_query, $faqs_args);
        $faqs = new WP_Query($faqs_query);
        wp_reset_query();

        $response['content_type_filters']['4_faqs'] = array(
            'item_label' => __('FAQs', 'tls'),
            'json_query' => 'post_type=tls_faq',
            'search_count' => (int)($current_query['post_type'] == 'tls_faq' || $wp_query->query_vars['post_type'] == 'any') ? $faqs->found_posts : 0,
        );


        /**
         * Date Filters Options with post count
         */
        // Past 7 Days
        $past_7_days_args = array(
            'date_query' => array(
                array(
                    'column' => 'post_date',
                    'after' => '7 days ago',
                    'inclusive' => true
                ),
            ),
        );
        $past_7_days_query = array_merge((array)$current_query, $past_7_days_args);
        $past_7_days = new WP_Query($past_7_days_query);
        wp_reset_query();
        $response['date_filters']['1_past_7_days'] = array(
            'item_label' => __('Past 7 Days'),
            'search_term' => '7 days ago',
            'search_count' => (int)$past_7_days->found_posts
        );

        // Past 30 Days
        $past_30_days_args = array(
            'date_query' => array(
                array(
                    'column' => 'post_date',
                    'after' => '30 days ago',
                    'inclusive' => true
                ),
            ),
        );
        $past_30_days_query = array_merge((array)$current_query, $past_30_days_args);
        $past_30_days = new WP_Query($past_30_days_query);
        wp_reset_query();
        $response['date_filters']['2_past_30_days'] = array(
            'item_label' => __('Past 30 Days'),
            'search_term' => '30 days ago',
            'search_count' => (int)$past_30_days->found_posts
        );

        // Past 1 Year
        $past_1_year_args = array(
            'date_query' => array(
                array(
                    'column' => 'post_date',
                    'after' => '1 year ago',
                    'inclusive' => true
                ),
            ),
        );
        $past_1_year_query = array_merge((array)$current_query, $past_1_year_args);
        $past_1_year = new WP_Query($past_1_year_query);
        wp_reset_query();
        $response['date_filters']['3_past_1_year'] = array(
            'item_label' => __('Past Year'),
            'search_term' => '1 year ago',
            'search_count' => (int)$past_1_year->found_posts
        );

        // Past 5 Years
        $past_5_years_args = array(
            'date_query' => array(
                array(
                    'column' => 'post_date',
                    'after' => '5 years ago',
                    'inclusive' => true
                ),
            ),
        );
        $past_5_years_query = array_merge((array)$current_query, $past_5_years_args);
        $past_5_years = new WP_Query($past_5_years_query);
        wp_reset_query();
        $response['date_filters']['4_past_5_years'] = array(
            'item_label' => __('Past 5 Years'),
            'search_term' => '5 years ago',
            'search_count' => (int)$past_5_years->found_posts
        );

        // Past 10 Years
        $past_10_years_args = array(
            'date_query' => array(
                array(
                    'column' => 'post_date',
                    'after' => '10 years ago',
                    'inclusive' => true
                ),
            ),
        );
        $past_10_years_query = array_merge((array)$current_query, $past_10_years_args);
        $past_10_years = new WP_Query($past_10_years_query);
        wp_reset_query();
        $response['date_filters']['5_past_10_years'] = array(
            'item_label' => __('Past 10 Years'),
            'search_term' => '10 years ago',
            'search_count' => (int)$past_10_years->found_posts
        );

        /**
         * Article Categories
         */

        $sections = get_terms('article_section');
        foreach ($sections as $key => $value) {

            $sections_count_args = array('article_section' => $value->slug);
            $sections_count_query = array_merge((array)$current_query, $sections_count_args);

            $sections_count = new WP_Query($sections_count_query);
            wp_reset_query();


            $response['articles_sections'][$value->slug] = array(
                'item_label' => $value->name,
                'type' => 'taxonomy',
                'json_query' => $value->slug,
                'taxonomy' => $value->taxonomy,
                'slug' => $value->slug,
                'search_count' => $sections_count->found_posts
            );
        }


        // Add book to all the results before they are returned
        foreach ((array)$response['posts'] as $search_post) {
            if (get_post_type($search_post->id) == 'tls_articles') {
                $search_post->books = get_field('field_54edde1e60d80', $search_post->id);
            }
        }

    } // END of Search Specific JSON API queries

    return $response;
}

add_filter('json_api_encode', 'tls_search_results_json_api_encode');