<?php namespace Tls;

use WP_Query;

/**
 * Class TlsTealium
 * @package Tls
 * @author  Vitor Faiante
 */
class TlsTealium
{


    /**
     * TLS Tealium Constructor
     */
    function __construct()
    {
        // Add Action Hook to add Tealium to the header
        add_action('wp_head', array(
            $this,
            'tls_tealium_head'
        ));
    }

    /**
     * Add Tealium Scripts to the head
     */
    public function tls_tealium_head()
    {
        // Get All options and then get the Tealium Environment Variable
        $theme_options_settings = get_option('theme_options_settings');
        $tealium_environment = $theme_options_settings['tls_tealium'];

        /**
         * Change the tealium data tags for each specific page
         * Use tealium_data function where the parameters are (page_name, page_type, page_section, page_restrictions, extra_options)
         * Extra options is an array for any other options that would be specific to some pages (example: search page has event_internal_search option tag
         */
        $tealium_data = $this->tealium_data(); // By default get
        if (is_front_page()) { // Front Page (Home Page)

            // Front Page (Home Page) Tealium Data Object
            $tealium_data = $this->tealium_data('homepage', 'homepage', 'homepage');

        } else {
            if (is_page_template('template-latest-edition.php')) { // Latest Edition Page

                // Get The latest Edition
                $latest_edition = new WP_Query(array(
                    'post_type' => 'tls_editions',
                    'post_status' => 'publish',
                    'posts_per_page' => 1,
                    'orderby ' => 'date'
                ));
                wp_reset_query();
                $latest_edition = $latest_edition->posts[0];
                // Latest Edition Tealium Data Object
                $tealium_data = $this->tealium_data('latest editions:' . $latest_edition->post_title, 'section level 1',
                    'latest editions');

            } else {
                if (is_singular('tls_editions')) { // Single Page for Editions

                    $edition_id = get_the_ID(); // Get The current Edition's ID
                    $edition_title = get_the_title($edition_id); // Get the current Edition's Post
                    // Single Edition Data Object
                    $tealium_data = $this->tealium_data('latest editions:' . $edition_title, 'section level 1',
                        'latest editions');

                } else {
                    if (is_page_template('template-articles-archive.php')) { // Discover Page

                        // Article Archive (Discover Page) Tealium Data Object
                        $tealium_data = $this->tealium_data('discover', 'section level 1', 'discover');

                    } else {
                        if (is_page_template('template-blogs-archive.php')) { // Blogs Page

                            // Blogs Archive Tealium Data Object
                            $tealium_data = $this->tealium_data('blogs', 'section level 1', 'blogs');

                        } else {
                            if (is_singular('post')) { // Single Blog Page

                                $blog_id = get_the_ID(); // Get Current Blog's ID
                                $blog_title = get_the_title($blog_id); // Get Current Blog Post
                                $blog_category = wp_get_post_terms($blog_id, 'category');
                                // Single Blog Tealium Data Object
                                $tealium_data = $this->tealium_data('blog post:' . $blog_title, 'blog post', 'blogs',
                                    'public', array('page_section_2' => 'blogs:' . $blog_category[0]->name));

                            } else {
                                if (is_search()) { // Search Page

                                    global $wp_query; // Get the Global $wp_query variable
                                    // Search Page Tealium Data Object
                                    $tealium_data = $this->tealium_data('search results', 'search', 'search', 'public',
                                        array(
                                            'event_internal_search' => 'internal search submitted',
                                            'internal_search_term' => $wp_query->query_vars['s'],
                                            'internal_search_results' => $wp_query->found_posts,
                                            'page_number' => "1 of " . $wp_query->max_num_pages
                                        ));

                                } else {
                                    if (is_tax('article_section')) { // Article Section (Article Category) Archive Page

                                        $article_section = get_term_by('slug', get_query_var('term'),
                                            get_query_var('taxonomy')); // Get the Current Section Term being queried
                                        // Article Section Archive Tealium Data Object
                                        $tealium_data = $this->tealium_data(html_entity_decode($article_section->name),
                                            'section level 2', 'info and navigation');

                                    } else {
                                        if (is_category()) { // Blog Post Category Archive

                                            $category = get_queried_object();
                                            // Blog Category Archive Tealium Data Object
                                            $tealium_data = $this->tealium_data('blog:' . html_entity_decode($category->name),
                                                'blog', 'blogs', 'public',
                                                array('page_section_2' => 'blogs:' . html_entity_decode($category->name)));

                                        } else {
                                            if (is_singular('tls_articles')) { // Single Article Page

                                                $article_id = get_the_ID(); // Get Article ID
                                                $article = get_post($article_id); // Get Article Post
                                                $article_visibility = wp_get_post_terms($article_id,
                                                    'article_visibility'); // Get Article Visibility
                                                $article_section = wp_get_post_terms($article_id,
                                                    'article_section'); // Get article Section
                                                $article_custom = get_post_custom($article_id); // Get article's custom fields
                                                $page_type = ($article_visibility[0]->slug == 'public') ? 'public' : 'subscriber'; // If article is public assign 'public' to variable $page_type otherwise assign 'subscriber'. This is used later on for the page_type in the tealium data object
                                                $page_restriction = ($article_visibility[0]->slug == 'public') ? 'public' : 'restricted'; // If article is public assign 'public' to $page_restriction variable otherwise assign 'restricted'. This is used later on the page_restrictions in the tealium data object
                                                // Single Article Tealium Data Object
                                                $tealium_data = $this->tealium_data($article_id . ':' . $article->post_title,
                                                    'article:' . $page_type,
                                                    html_entity_decode($article_section[0]->name), $page_restriction,
                                                    array(
                                                        'article_name' => $article->post_title,
                                                        'article_publish_timestamp' => strtolower(date('Y/m/d H:i l',
                                                            strtotime($article->post_date))),
                                                        'article_id' => $article_id,
                                                        'article_author' => (isset($article_custom['article_author_name'][0])) ? $article_custom['article_author_name'][0] : '',
                                                    ));

                                            } else {
                                                if (is_page_template('template-faqs.php')) { // FAQs Page

                                                    // FAQs Page Tealium Data Object
                                                    $tealium_data = $this->tealium_data('frequently asked questions',
                                                        'info and navigation', 'info and navigation');

                                                } else {
                                                    if (is_page_template('template-page-with-accordion.php')) { // if it is a page with the Accordion Page template

                                                        $page_id = get_the_ID(); // Get the current page ID
                                                        $page = get_post($page_id); // Get current page

                                                        if (strtolower($page->post_title) == 'contact us') { // if it is contact us page

                                                            // Contact Us Page Tealium Data Object
                                                            $tealium_data = $this->tealium_data('contact us',
                                                                'info and navigation', 'info and navigation');

                                                        } else {
                                                            if (strtolower($page->post_name) == 'terms-conditions' || strtolower($page->post_name) == 'terms-and-conditions') { // If the current page is Terms & Conditions

                                                                // Terms & Conditions Page Tealium Page Data Object
                                                                $tealium_data = $this->tealium_data('terms and conditions',
                                                                    'info and navigation', 'info and navigation');

                                                            }
                                                        }

                                                    } else {
                                                        if (is_404()) { // if it is a 404 Page

                                                            // 404 Page Tealium Data Object
                                                            $tealium_data = $this->tealium_data('', 'errors', 'errors',
                                                                'public', array(
                                                                    'error_type' => 'page not found',
                                                                    'error_name' => 'error'
                                                                ));

                                                        } else {
                                                            if (is_singular('page')) { // If it is single page

                                                                $page_id = get_the_ID(); // Get the current age ID
                                                                $page = get_post($page_id); // Get current Page

                                                                // Test Page Based on Title
                                                                if (strtolower($page->post_title) == 'how to advertise') { // If the Current Page is How to Advertise

                                                                    // How To Advertise Page Tealium Data Object
                                                                    $tealium_data = $this->tealium_data('how to advertise',
                                                                        'info and navigation', 'info and navigation');

                                                                }

                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        /**
         * Output TLS Tealium JS Code in the Header
         */
        ?>
        <!-- TEALIUM CODE BLOCK : START -->

        <!-- Tealium Universal Data Object -->
        <script type="text/javascript">
            var utag_data = {
                <?php
                    foreach ($tealium_data as $tealium_key => $tealium_value) {
                        echo "{$tealium_key} : \"" . $tealium_value . "\", \n";
                    }
                ?>
            }
        </script> <!-- END Tealium Universal Data Object -->

        <!-- Tealium Asynchronous Script Call -->
        <script type="text/javascript">
            (function (a, b, c, d) {
                a = '//tags.tiqcdn.com/utag/newsinternational/thetimes.tls/<?php echo $tealium_environment; ?>/utag.js';
                b = document;
                c = 'script';
                d = b.createElement(c);
                d.src = a;
                d.type = 'text/java' + c;
                d.async = true;
                a = b.getElementsByTagName(c)[0];
                a.parentNode.insertBefore(d, a);
            })();
        </script> <!-- END Tealium Asynchronous Script Call -->

        <!-- Tealium Call End -->
    <?php
    }


    /**
     * Method to generate array with tealium data to be used in the tealium tags
     *
     * @param   string $page_name         Page Name Tealium Data Object Property
     * @param   string $page_type         Page Type Tealium Data Object Property
     * @param   string $page_section      Page Section Tealium Data Object Property
     * @param   string $page_restrictions Page Restrictions Tealium Data Object Property
     * @param   array  $extra_options     Extra Options that may be needed on other pages
     *
     * @return  array   $tealium_data           Array of the final tealium data
     */
    function tealium_data(
        $page_name = null,
        $page_type = null,
        $page_section = null,
        $page_restrictions = 'public',
        $extra_options = array()
    ) {
        $tealium_data = array(
            'page_name' => $page_name,
            'page_type' => $page_type,
            'page_section' => $page_section,
            'page_restrictions' => $page_restrictions,
        );

        if (!empty($extra_options)) {

            foreach ($extra_options as $option_key => $option_value) {

                $tealium_data[$option_key] = (string)$option_value;

            }

        }

        return $tealium_data;
    }
}