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

        add_action('admin_footer', array($this, 'post_featured_image_action_ajax'));

        add_action('wp_ajax_post_image_importer_action', array($this, 'post_image_importer_action_callback'));

        add_action('wp_ajax_post_featured_image_action', array($this, 'post_featured_image_action_callback'));
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
                                            <td colspan="2">
                                                <input class="button-primary tls_post_image_importer_action" type="submit" name="post_image_importer_action" id="post_image_importer_action" value="Search And Import Images"/>

                                                <input class="button-primary tls_post_featured_image_action" type="submit" name="post_featured_image_action" id="post_featured_image_action" value="Search And Set Featured Image"/>
                                                <p class="description">
                                                    The <strong>"Search And Import Images"</strong> button will trigger an action that will find all external images inside Posts with the category selected and then download those images to WordPress.
                                                    <br/>
                                                    The <strong>"Search And Set Featured Image"</strong> will search for all images in Posts from the selected Category which are not external and will set the first one as the Featured Image.
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
     * Ajax POST Action for Post Image Importer
     */
    public function post_image_importer_action_ajax()
    {
        ?>
        <script type="text/javascript" >
            jQuery(document).ready(function($) {

                jQuery('.tls_post_featured_image_action').click(function (e) {

                    e.preventDefault();
                    var ajax_message = jQuery('#ajax-message');
                    var show_loading_update = jQuery('#show_loading_update');

                    var data = {
                        'action': 'post_featured_image_action',
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
        $posts_with_external_images = 0;

        foreach ($post_query->posts as $single_post) {
            $post_content_images = $this->search_content($single_post->post_content);
            $new_post_content_images = array();

            if (!empty($post_content_images['urls'])) {
                $message .= "Post: {$single_post->post_title} (ID: {$single_post->ID}) has " . count($post_content_images['imgs']) . " external images <br />";

                foreach ($post_content_images['urls'] as $old_content_image_url) {
                    $message .= "Old Image URL: <a href=\"" . $old_content_image_url . "\" target=\"_blank\">" . $old_content_image_url . "</a><br />";

                    $new_content_image_url = $this->download_images($old_content_image_url, $single_post->ID);

                    $new_post_content_images[] = '<img src="' . $new_content_image_url . '" alt="" />';

                    $message .= "New Image: <a href=\"" . $new_content_image_url . "\" target=\"_blank\">" . $new_content_image_url . "</a><br />";
                }

                $updated_content = $this->search_replace_content_images($post_content_images['imgs'], $new_post_content_images, $single_post->post_content);

                wp_update_post( array(
                        'ID'            => $single_post->ID,
                        'post_content'  => $updated_content,
                        'post_status'   => 'publish'
                    )
                );

                $posts_with_external_images++;
            }
        }

        if (!$posts_with_external_images > 0) {
            $message .= "No External Images Found in any of the posts";
        }

        echo $message;

        wp_die();
    }

    /**
     * Ajax POST Action for Post Featured Image
     */
    public function post_featured_image_action_ajax()
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
     * Post Featured Images Ajax Callback Method
     */
    public function post_featured_image_action_callback()
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
        $posts_with_internal_images = 0;
        $featured_images_set = 0;

        foreach ($post_query->posts as $single_post) {
            // Search for internal images. Needs to set second parameter as true
            $post_content_images = $this->search_content($single_post->post_content, true);
            echo 'Post Content Images<br />';var_dump($post_content_images);echo '<br />';
            if (!empty($post_content_images['urls'])) {
                echo 'Has Thumbnail<br />';var_dump(has_post_thumbnail($single_post->ID));echo '<br />';
                if (has_post_thumbnail($single_post->ID) === false) {
                    $first_internal_img = (string) $post_content_images['urls'][0];
                    echo 'First Image<br />';var_dump($first_internal_img);echo '<br />';

                    $attached_images = get_attached_media('image', $single_post->ID);
                    echo 'Attached Images<br />';var_dump($attached_images);echo '<br />';

                    foreach ($attached_images as $attached_image) {
                        if ($attached_image->guid == $first_internal_img) {
                            // Set Featured Image
                            update_post_meta($single_post->ID, '_thumbnail_id', $attached_image->ID);

                            $message .= "Featured image set for the Post: <a href=\"" . get_permalink($single_post->ID) . "\" target=\"_blank\">" . $single_post->post_title . "</a><br />";

                            $featured_images_set++;
                        }
                    }
                }

                $posts_with_internal_images++;
            }
        }

        if (!$featured_images_set > 0) {
            $message .= "All posts with images have Featured images set <br />";
        }

        if (!$posts_with_internal_images > 0) {
            $message .= "No Local Images Found in any of the posts";
        }

        echo $message;
        wp_die();
    }

    /**
     * Search Content for Images
     *
     * @param string    $post_content   Post's Content Text
     * @param bool      $internal       Search for internal images or not
     *
     * @return array|void
     */
    private function search_content($post_content, $internal = false)
    {
        $content_images = array();

        // Get all of the <a> tags
        preg_match_all("|<a(.+?)\/a>|mis", (string) $post_content, $content_a_tag_matches);

        if (!empty($content_a_tag_matches[0])) { // If there are <a> tags
            foreach ($content_a_tag_matches[0] as $content_a_tag_match) {
                // Search for <img> tags inside the <a> tags
                preg_match_all("|<img(.+?)\/>|mis", (string) $content_a_tag_match, $content_a_tag_images_matches);
                if (!empty($content_a_tag_images_matches[0])) { // If
                    $content_images[] = $content_a_tag_match;
                }
            }
        }

        if (!empty($content_images)) {
            $post_content = str_replace($content_images, '', (string) $post_content);
        }

        preg_match_all("|<img(.+?)\/>|mis", (string) $post_content, $content_images_matches);
        if (!empty($content_images_matches[0])) {
            $content_images = array_merge($content_images, $content_images_matches[0]);
        }

        $site_url = parse_url(site_url());
        echo 'Site URL<br />';var_dump($site_url);echo '<br />';
        $external_images_search = array();
        $external_images_urls = array();

        $internal_images_search = array();
        $internal_images_urls = array();

        if (empty($content_images)) {
            return;
        }

        foreach ($content_images as $content_image) {

            $image = strip_tags($content_image, '<img>');
            $image_simple_xml = simplexml_load_string($image);
            $image_url = (string) $image_simple_xml->attributes()->src;

            if (!strpos($image_url, $site_url['host'])) {
                $external_images_search[] = $content_image;
                $external_images_urls[] = $image_url;
            } else {
                $internal_images_search[] = $content_image;
                $internal_images_urls[] = $image_url;
            }

        }

        if ($internal === true && empty($internal_images_search)) {
            return;
        }

        if ($internal === true && !empty($internal_images_search)) {
            return array(
                'urls' => $internal_images_urls,
                'imgs' => $internal_images_search
            );
        }

        if (empty($external_images_search)) {
            return;
        }

        return array(
            'urls' => $external_images_urls,
            'imgs' => $external_images_search
        );
    }

    /**
     * Download Images from content
     *
     * @param $image_url    Image URL
     * @param $post_id      Post ID
     *
     * @return bool|string
     */
    private function download_images($image_url, $post_id)
    {
        $tmp = download_url( $image_url );

        $url_array = explode('/', $image_url);
        $image_name = array_pop($url_array);
        $image_mime_type = image_type_to_mime_type(exif_imagetype($image_url));
        $image_mime_type_array = explode('/', $image_mime_type);
        $image_type = array_pop($image_mime_type_array);

        // Set variables for storage
        $file_array = array(
            'name'      => $image_name . '.' . $image_type,
            'type'      => $image_mime_type,
            'tmp_name'  => $tmp,
            'error'     => 0,
            'size'      => filesize($tmp),
        );

        // If error storing temporarily, unlink
        if ( is_wp_error( $tmp ) ) {
            @unlink($file_array['tmp_name']);
            $file_array['tmp_name'] = '';

            return false;
        }

        // do the validation and storage stuff
        $uploaded_image_id = media_handle_sideload($file_array, $post_id, $image_name);

        // If error storing permanently, unlink
        if ( is_wp_error($uploaded_image_id) ) {
            @unlink($file_array['tmp_name']);

            return false;
        }

        $uploaded_image_url = wp_get_attachment_url($uploaded_image_id);

        return $uploaded_image_url;
    }

    /**
     * Search Old Images and Replace with new images in the content
     *
     * @param array     $old_images   Array of Old Images to be replaced
     * @param array     $new_images   Array of New Images to replace with
     * @param string    $content      Post Content
     *
     * @return string   $updated_content   Updated Post Content
     */
    private function search_replace_content_images($old_images, $new_images, $content)
    {
        $updated_content = str_replace($old_images, $new_images, $content);

        return $updated_content;
    }

}