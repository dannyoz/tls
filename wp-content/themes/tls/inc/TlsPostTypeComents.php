<?php

namespace Tls;

use WP_Query;


/**
 * Class TlsPostTypeComments
 * @package Tls
 * @author  Vitor Faiante
 */
class TlsPostTypeComments
{

    /**
     * Constructor
     */
    public function __construct()
    {
        // Add TLS Post Image Importer Page
        add_action('admin_menu', array($this, 'tls_post_type_comments_page'));

        add_action('admin_footer', array($this, 'post_type_comments_on_action_ajax'));

        add_action('admin_footer', array($this, 'post_type_comments_off_action_ajax'));

        add_action('wp_ajax_post_type_comments_on_action', array($this, 'post_type_comments_on_action_callback'));

        add_action('wp_ajax_post_type_comments_off_action', array($this, 'post_type_comments_off_action_callback'));
    }

    /**
     * Add WordPress Menu Page for Post Image Importer
     */
    public function tls_post_type_comments_page()
    {
        add_menu_page(
            'TLS Post Type Comments',
            'TLS Post Type Comments',
            'manage_options',
            'tls-post-type-comments',
            array(
                $this,
                'render_post_type_comments'
            ),
            'dashicons-editor-break'
        );
    }

    /**
     * Render Post Importer Page
     */
    public function render_post_type_comments()
    {
        $post_categories = get_terms('category', array(
            'orderby'       => 'name',
            'hide_empty'    => true,
        ));
        ?>
        <div class="wrap">
            <h2>TLS Post Type Comments</h2>
            <p class="description">This feature is to be used only by Admin. This will either turn on or off all comments in any given Post Type</p>

            <form method="post">

                <div class="poststuff">
                    <div id="post-body" class="metabox-holder">
                        <div id="tls_post_type_comments_on_container" class="postbox-container widefat">
                            <div class="postbox">
                                <div class="inside">

                                    <table class="form-table">
                                        <tr valign="top">
                                            <th scope="row">Post Type:
                                                <p class="description">Please choose a Post Type to narrow down the search</p>
                                            </th>
                                            <td>
                                                <select name="post_type" id="post_type">
                                                    <option value="">-- Choose a Post Type --</option>
                                                    <option value="tls_articles">Article Posts</option>
                                                    <option value="post">Blog Posts</option>
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
                                            <td colspan="2">
                                                <input class="button-primary tls_post_type_comments_on_action" type="submit" name="post_type_comments_on_action" id="post_type_comments_on_action" value="Turn On Comments"/>

                                                <input class="button-primary tls_post_type_comments_off_action" type="submit" name="post_type_comments_off_action" id="post_type_comments_off_action" value="Turn Off Comments"/>
                                                <p class="description">
                                                    The <strong>"Turn On Comments"</strong> button will trigger an action that will cycle through all posts of a certain Post Type and turn on comments for all those posts.
                                                    <br/>
                                                    The <strong>"Turn Off Comments"</strong> button will trigger an action that will cycle through all posts of a certain Post Type and turn off comments for all those posts.
                                                </p>
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
     * Ajax POST Action for Post Type Comments ON
     */
    public function post_type_comments_on_action_ajax()
    {
        ?>
        <script type="text/javascript" >
            jQuery(document).ready(function($) {

                jQuery('.tls_post_type_comments_on_action').click(function (e) {

                    e.preventDefault();
                    var ajax_message = jQuery('#ajax-message');
                    var show_loading_update = jQuery('#show_loading_update');

                    var data = {
                        'action': 'post_type_comments_on_action',
                        'post_type': jQuery('#post_type').val()
                    };

                    ajax_message.css('display', 'none');
                    ajax_message.removeClass('success');
                    ajax_message.removeClass('fail');
                    show_loading_update.css('display', 'block');

                    $.post(ajaxurl, data, function (response) {
                        show_loading_update.css('display', 'none');

                        if (response == 'No Post Type') {
                            ajax_message.addClass('fail').css('display', 'block');
                            ajax_message.html('Sorry no Post Type chosen. Please choose a Post Type');
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
     * Post Type Comments ON Ajax Callback Method
     *
     */
    public function post_type_comments_on_action_callback()
    {
        $post_type = (isset($_POST['post_type'])) ? wp_strip_all_tags($_POST['post_type']) : null;
        switch ($post_type) {
            case 'post':
                $post_type_name = 'Blog Posts';
                break;
            case 'tls_articles':
                $post_type_name = 'Article Posts';
        }

        if ($post_type == null) {
            echo 'No Post Type';
            wp_die();
        }

        $post_type_comments_query = new WP_Query(array(
            'post_type'         => $post_type,
            'posts_per_page'    => '-1'
        ));

        $message = "Found " . $post_type_comments_query->found_posts . " Posts with in that Post Type <br /><br />";

        $posts_comments_status_changed = 0;
        foreach ($post_type_comments_query->posts as $single_post) {
            $comments_on = wp_update_post(array(
                'ID'                => (int) $single_post->ID,
                'comment_status'    => 'open'
            ), true);

            if (!is_wp_error($comments_on)) {
                $posts_comments_status_changed++;
            }
        }

        $message .= "Comments Are Open for " . $posts_comments_status_changed . " from Post Type: " . $post_type_name;

        echo $message;

        wp_die();
    }

    /**
     * Ajax POST Action for Post Type Comments OFF
     */
    public function post_type_comments_off_action_ajax()
    {
        ?>
        <script type="text/javascript" >
            jQuery(document).ready(function($) {

                jQuery('.tls_post_type_comments_off_action').click(function (e) {

                    e.preventDefault();
                    var ajax_message = jQuery('#ajax-message');
                    var show_loading_update = jQuery('#show_loading_update');

                    var data = {
                        'action': 'post_type_comments_off_action',
                        'post_type': jQuery('#post_type').val()
                    };

                    ajax_message.css('display', 'none');
                    ajax_message.removeClass('success');
                    ajax_message.removeClass('fail');
                    show_loading_update.css('display', 'block');

                    $.post(ajaxurl, data, function (response) {
                        show_loading_update.css('display', 'none');

                        if (response == 'No Post Type') {
                            ajax_message.addClass('fail').css('display', 'block');
                            ajax_message.html('Sorry no Post Type chosen. Please choose a Post Type');
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
     * Post Type Comments OFF Ajax Callback Method
     *
     */
    public function post_type_comments_off_action_callback()
    {
        $post_type = (isset($_POST['post_type'])) ? wp_strip_all_tags($_POST['post_type']) : null;
        switch ($post_type) {
            case 'post':
                $post_type_name = 'Blog Posts';
                break;
            case 'tls_articles':
                $post_type_name = 'Article Posts';
        }

        if ($post_type == null) {
            echo 'No Post Type';
            wp_die();
        }

        $post_type_comments_query = new WP_Query(array(
            'post_type'         => $post_type,
            'posts_per_page'    => '-1'
        ));

        $message = "Found " . $post_type_comments_query->found_posts . " Posts with in that Post Type <br /><br />";

        $posts_comments_status_changed = 0;
        foreach ($post_type_comments_query->posts as $single_post) {
            $comments_on = wp_update_post(array(
                'ID'                => (int) $single_post->ID,
                'comment_status'    => 'closed'
            ), true);

            if (!is_wp_error($comments_on)) {
                $posts_comments_status_changed++;
            }
        }

        $message .= "Comments Are Closed for " . $posts_comments_status_changed . " from Post Type: " . $post_type_name;

        echo $message;

        wp_die();
    }

}