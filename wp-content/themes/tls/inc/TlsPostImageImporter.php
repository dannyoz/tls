<?php

namespace Tls;

use WP_Query;


/**
 * Class TlsPostImageImporter
 * @package Tls
 * @author  Vitor Faiante
 */
class TlsPostImageImporter
{

    /**
     * Constructor
     */
    public function __construct()
    {
        // Add TLS Post Image Importer Page
        add_action('admin_menu', array($this, 'tls_image_importer_page'));

        add_action('admin_footer', array($this, 'post_image_importer_action_ajax'));

        add_action('wp_ajax_post_image_importer_action', array($this, 'post_image_importer_action_callback'));
    }

    /**
     * Add WordPress Menu Page for Post Image Importer
     */
    public function tls_image_importer_page()
    {
        add_menu_page(
            'TLS Post Image Importer',
            'TLS Post Image Importer',
            'manage_options',
            'tls-post-image-importer',
            array(
                $this,
                'render_post_importer_page'
            ),
            'dashicons-editor-break'
        );
    }

    /**
     * Render Post Importer Page
     */
    public function render_post_importer_page()
    {
        $post_categories = get_terms('category', array(
            'orderby'       => 'name',
            'hide_empty'    => true,
        ));
        ?>
        <div class="wrap">
            <h2>TLS Post Image Importer</h2>
            <p class="description">This feature is to be used only by Admin. This will search inside the content of each of the Blog Posts and if finds an image that is not hosted by this website it will download it and then replace the original image with the locally downloaded image</p>

            <form method="post">

                <div class="poststuff">
                    <div id="post-body" class="metabox-holder">
                        <div id="tls_post_image_importer_container" class="postbox-container widefat">
                            <div class="postbox">
                                <div class="inside">

                                    <table class="form-table">
                                        <tr valign="top">
                                            <th scope="row">Post Category:
                                                <p class="description">Please choose a Post Category to narrow down the search</p>
                                            </th>
                                            <td>
                                                <select name="post_category" id="post_category">
                                                    <option value="">-- Choose a Category --</option>
                                                    <?php
                                                    foreach ($post_categories as $post_category) {
                                                        echo '<option value="' . $post_category->term_id . '">' . $post_category->name . '</option>';
                                                    }

                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td colspan="2">
                                                <div id="ajax-message" class="widefat" style="height: auto;"></div>
                                                <div id="show_loading_update">
                                                    <img class="alignnone" title="WordPress Loading Animation Image"
                                                         src="<?php echo admin_url('/../wp-includes/js/thickbox/loadingAnimation.gif');
                                                         ?>"
                                                         alt="WordPress Loading Animation Image" width="208" height="13"/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <td>
                                                <input class="button-primary left tls_post_image_importer_action" type="submit" name="post_image_importer_action" id="post_image_importer_action" value="Search And Import Images"/>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }

    /**
     * Ajax POST Action for Post Image Importer
     */
    public function post_image_importer_action_ajax()
    {
        ?>
        <script type="text/javascript" >
            jQuery(document).ready(function($) {

                jQuery('.tls_post_image_importer_action').click(function (e) {

                    e.preventDefault();
                    var ajax_message = jQuery('#ajax-message');
                    var show_loading_update = jQuery('#show_loading_update');

                    var data = {
                        'action': 'post_image_importer_action',
                        'post_category': jQuery('#post_category').val()
                    };

                    ajax_message.css('display', 'none');
                    ajax_message.removeClass('success');
                    ajax_message.removeClass('fail');
                    show_loading_update.css('display', 'block');

                    $.post(ajaxurl, data, function (response) {
                        show_loading_update.css('display', 'none');

                        if (response == 'No Category') {
                            ajax_message.addClass('fail').css('display', 'block');
                            ajax_message.html('Sorry no category found! Please select a category.');
                        } else {
                            ajax_message.addClass('success').css('display', 'block');
                            ajax_message.html(response);
                        }
                    });

                });

            });
        </script>
        <?php
    }

    /**
     * Post Image Importer Ajax Callback Method
     *
     */
    public function post_image_importer_action_callback()
    {
        $category_term_id = (isset($_POST['post_category'])) ? intval($_POST['post_category']) : null;

        if ($category_term_id == null) {
            echo 'No Category';
            wp_die();
        }

        $post_query = new WP_Query(array(
            'post_type'         => 'post',
            'posts_per_page'    => '-1',
            'tax_query'         => array(
                array(
                    'taxonomy'  => 'category',
                    'field'     => 'term_id',
                    'terms'     => (int) $category_term_id
                )
            )
        ));

        $message = "Found " . $post_query->found_posts . " Posts with that Category <br /><br />";

        foreach ($post_query->posts as $single_post) {
            $post_content_images = $this->search_content($single_post);

            $message .= $post_content_images;
        }

        echo $message;

        wp_die();
    }

    private function search_content($single_post)
    {
        // Get all of the Inline Images
        preg_match_all("|<img(.+?)\/>|mis", (string) $single_post->post_content, $content_images_matches);

        $outside_images = 0;
        $site_url = parse_url(site_url());
        foreach ($content_images_matches[0] as $content_image) {
            $image_simple_xml = simplexml_load_string($content_image);

            if (!strpos($image_simple_xml->attributes()->src, $site_url['host'])) {
                $outside_images++;
            }
        }

        if (!count($content_images_matches[0]) > 0) {
            return;
        }

        if (!$outside_images > 0) {
            return;
        }

        return "Post: {$single_post->post_title} (ID: {$single_post->ID}) has " . count($content_images_matches[0]) . " Images in the content. Of which it has " . $outside_images . " external images <br />";

    }

}