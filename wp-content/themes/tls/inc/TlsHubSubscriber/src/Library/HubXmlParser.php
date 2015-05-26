<?php

namespace Tls\TlsHubSubscriber\Library;

require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

use Carbon\Carbon;
use WP_Query;

/**
 * Class HubXmlParser
 *
 * @package Tls\TlsHubSubscriber\Library
 * @author  Vitor Faiante
 */
class HubXmlParser implements FeedParser
{
    /**
     * @var string $admin_email
     */
    private $admin_email;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->admin_email = get_option('admin_email');
    }

    /**
     * Method to parse the XML Feed coming from the PHP Input stream in the TlsHubSubscriberFE Callback URL page parsing
     *
     * @param $feed
     *
     * @return mixed
     */
    public function parseFeed($feed)
    {
        // Start Time counter to use for calculation of the time it takes to parse the feed
        $start_time = microtime(true);

        // Replace & with the HTML entity of &amp; which was causing an error to load the string
        $feed = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $feed);
        // Turn on Simple XML Internal Errors to catch any errors that appear
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($feed, null, LIBXML_NOCDATA);

        // If there are errors then add them to the error logs
        if ($xml === false) {
            $error_msg = "Failed loading Article XML\n";
            foreach (libxml_get_errors() as $error) {
                $error_msg .= "\t" . $error->message;
            }
            HubLogger::error($error_msg);

            // Send Email to admin
            $email_subject = "TLS Article Importer Error - Failed Loading Article XML";
            wp_mail($this->admin_email, $email_subject, $error_msg);

            exit();
        }

        // Parse the article entries [Separate method so this method doesn't become extremely long]
        $articlesResult = $this->parseArticles($xml);

        // Stop Time Counter
        $end_time = microtime(true);
        // Calculate time it took to run through the import
        $execution_time = ($end_time - $start_time);

        // Add Log of the time it took and how many articles it imported
        $articles = ($articlesResult['articleCount'] == 1) ? 'article' : 'articles';

        echo 'It took ' . number_format($execution_time, 2) . ' seconds to '
            . $articlesResult['import_type'] . ' Article ID: ' . $articlesResult['original_article_id'] . ' & Title: ' . $articlesResult['original_article_title'];

        HubLogger::log('It took ' . number_format($execution_time, 2) . ' seconds to '
            . $articlesResult['import_type'] . ' Article ID: ' . $articlesResult['original_article_id'] . ' & Title: ' . $articlesResult['original_article_title']);

    }

    /**
     * Method to parse each article entry from the XML feed and save all its data, taxonomy terms and custom fields
     *
     * @param $article
     *
     * @return int $articleCount    The count of how many articles were parsed to be returned back to parseFeed
     */
    protected function parseArticles($article)
    {
        // Start the articleCount variable
        $articleCount = 0;

        // Get all cpi: nodes from the XML
        $cpiNamespace = $article->children('cpi', true);

        // Get Article Entry ID from the URL in the id node
        // ID after the last slash /
        $article_id_url = explode('/', $article->id);
        $article_entry_id = trim(array_pop($article_id_url));

        $article_entry_published = new Carbon($article->published);
        $article_entry_updated = new Carbon($article->updated);

        $article_content_copy = html_entity_decode(htmlspecialchars_decode($cpiNamespace->copy), ENT_QUOTES, 'UTF-8');
        $article_content_copy = trim(preg_replace('/\s+/', ' ', $article_content_copy));

        /*
         * Prepare and make WP_Query to check if the article being parsed already exists in WP
         * based on the ID in the XML Feed entry
         */
        $articleMatches_args = array(
            'posts_per_page' => 1,
            'post_type' => 'tls_articles',
            'meta_key' => 'article_feed_id',
            'meta_value' => (string)$article_entry_id
        );
        $articleMatches = new WP_Query($articleMatches_args);

        if ($articleMatches->found_posts > 0) {
            $last_updated_date = new Carbon(get_field('field_54eb50af14d87', $articleMatches->posts[0]->ID));
            if ($article_entry_updated->toDayDateTimeString() <= $last_updated_date->toDayDateTimeString()) {
                die('This article will not be imported because this article seems older than another one we have');
            }
        }

        // Get all of the Inline Images
        preg_match_all("|<link(.+?)\/>|mis", (string) $article_content_copy, $content_inline_images_matches);

        if ($content_inline_images_matches > 0) {
            $inline_images = array();
            $inline_image_counter = 0;
            foreach ($content_inline_images_matches[0] as $content_inline_images_match) {
                $inline_image_obj = simplexml_load_string($content_inline_images_match);

                $inline_img = $this->handleImageUpload($inline_image_obj->attributes()->href, true,
                    $inline_image_counter);
                $inline_images += $inline_img;

                $inline_image_counter++;
            }
        }

        // Download related images
        $related_images = array();
        foreach ($article->link as $link) {
            if ($link->attributes()->rel == 'related') {

                $image = $this->handleImageUpload($link->attributes()->href);
                if ($image != false || $image != null) {
                    $related_images += $image;
                }

            }
        }

        // Add all the Article Data into an array
        $article_data = array(
            'post_content' => $article_content_copy,
            // The full text of the post.
            'post_title' => wp_strip_all_tags($cpiNamespace->headline),
            // The title of your post.
            'post_status' => 'publish',
            // Default 'draft'.
            'post_type' => 'tls_articles',
            // Default 'post'.
            'post_author' => 1,
            // The user ID number of the author. Default is the current user ID.
            'ping_status' => 'closed',
            // Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
            'comment_status' => 'closed',
            // Default is the option 'default_comment_status', or 'closed'.
        );

        /*
         * Check to see if there wasn't any matches. If no match is found
         * then send $article_data to saveArticleData method to insert new article
         * Otherwise if a match was found the send $article_data to saveArticleData method
         * with update parameter true to update article instead
         */
        if (!$articleMatches->found_posts > 0) {
            $article_data['post_date'] = $article_entry_published->toDateTimeString(); // Published Date
            $article_data['post_date_gmt'] = $article_entry_published->toDateTimeString(); // Published Date GMT
            $article_data['post_modified'] = $article_entry_updated->toDateTimeString(); // Updated Date
            $article_data['post_modified_gmt'] = $article_entry_updated->toDateTimeString(); // Updated Date GMT

            $article_id = $this->saveArticleData($article_data, $article_entry_id);

            $teaser_summary_custom_field = array(
                // Teaser Summary
                'field_54e4d3c3b0095' => tls_make_post_excerpt($cpiNamespace->copy, 30),
            );
            $this->saveArticleCustomFields($teaser_summary_custom_field, $article_id);

            $import_type = 'import';
        } else {

            if ($article_entry_updated->toDayDateTimeString() > $last_updated_date->toDayDateTimeString()) {

                $article_data['ID'] = (int)$articleMatches->posts[0]->ID;
                $article_id = $this->saveArticleData($article_data, $article_entry_id, true);
                $import_type = 'update';
            }

        }

        // Save Article Section Taxonomy Term
        $article_section = $this->saveArticleTaxonomyTerms($article_id, $cpiNamespace->section, 'article_section');
        // Save Article Visibility Taxonomy Term
        $article_visibility = $this->saveArticleTaxonomyTerms($article_id, $cpiNamespace->visibility,
            'article_visibility');
        // Save Article Tags Taxonomy Terms
        $article_tags = $this->saveArticleTaxonomyTerms($article_id, $cpiNamespace->tag, 'article_tags');

        // Article Review Books
        $books = array();
        foreach ($cpiNamespace->books->book as $book) {
            $books[] = array(
                'book_title' => (string)$book->title,
                'book_author' => (string)$book->authorname,
                'book_info' => (string)$book->info,
                'book_isbn' => (string)$book->isbn
            );
        }
        $this->saveArticleCustomFields($books, $article_id, 'field_54edde1e60d80'); // Books

        // Add all the Custom Fields' data into an array
        $article_custom_fields = array(
            // Article Feed ID
            'field_54e4d372b0093' => (string)$article_entry_id,
            // Last Updated Date
            'field_54eb50af14d87' => (string)$article_entry_updated->toDateTimeString(),
            // Author Name
            'field_54e4d3b1b0094' => (string)$cpiNamespace->byline,
        );
        // Send Custom Fields Data to saveArticleCustomFields method to be saved
        // using the $article_id that came out of the saving or updating method
        $this->saveArticleCustomFields($article_custom_fields, $article_id);

        /*
         * Save All Related Images Custom Fields
         */
        // Get Main Full Image based upon either the size or being main_image
        if (isset($related_images['full_image_attachment_id'])) {
            $full_image_attachment_id = (int)$related_images['full_image_attachment_id'];
        } else if (isset($related_images['main_image_attachment_id'])) {
            $full_image_attachment_id = (int)$related_images['main_image_attachment_id'];
        } else {
            $full_image_attachment_id = '';
        }
        $thumb_image = isset($related_images['thumbnail_image_attachment_id']) ? (int)$related_images['thumbnail_image_attachment_id'] : '';
        $hero_image = isset($related_images['hero_image_attachment_id']) ? (int)$related_images['hero_image_attachment_id'] : '';

        $related_images_custom_fields = array(
            // Thumbnail Image
            'field_54e4d481b009a' => $thumb_image,
            // Full Image
            'field_54e4d4a5b009b' => $full_image_attachment_id,
            // Hero Image
            'field_54e4d4b3b009c' => $hero_image,
        );
        $this->saveArticleCustomFields($related_images_custom_fields, $article_id);

        /*
         * Search and replace all Inline Images with downloaded images.
         */
        if ($content_inline_images_matches[0] > 0) {
            $content_with_inline_images = $this->searchReplaceInlineImages(
                $article_content_copy,
                $content_inline_images_matches[0],
                $inline_images
            );

            $inline_images_updated_article_data = array(
                'ID'            => $article_id,
                'post_content'  => $content_with_inline_images
            );

            $this->saveArticleData($inline_images_updated_article_data, $article_entry_id, true);

        }

        // Attach all images to post
        if (!empty($inline_images) || !empty($related_images)) {
            $attach_images = array_merge($inline_images, $related_images);
            $attach_images_ids = array_values($attach_images);

            $this->attachImagesToPost($attach_images_ids, $article_id);
        }

        // Add 1 to the articleCount after parsing the article
        $articleCount++;

        // Returns the number of articles parsed back into the parseFeed method
        // to add a log of how many articles were imported
        return array(
            'import_type'               => $import_type,
            'articleCount'              => $articleCount,
            'original_article_id'       => (string) $article_entry_id,
            'original_article_title'    => wp_strip_all_tags($cpiNamespace->headline)
        );
    }

    /**
     * Method to save the Article Data and in case there is a WP_Error that comes out an error log will be written
     *
     * @param      $article_data
     * @param      $original_article_id
     * @param bool $update
     *
     * @return int|\WP_Error
     */
    private function saveArticleData($article_data, $original_article_id, $update = false)
    {

        /*
         * If $update parameter is true call update wordpress function, otherwise call the insert function
         * Both update and insert function will have the second parameter for the WP_Error to be true which
         * will throw a WP_Error if there is any error in updating or inserting the posts
         */
        if ($update == true) {
            $article_id = wp_update_post($article_data, true);
        } else {
            $article_id = wp_insert_post($article_data, true);
        }

        /*
         * If any WP_Error is thrown when inserting or updating the article then
         * Log those errors
         */
        if (is_wp_error($article_id)) {
            $errors = $article_id->get_error_messages();
            $error_msg = "Failed Importing Article - Article ID: " . $original_article_id . " & Title: " . $article_data['post_title'] . " \n";
            foreach ($errors as $error) {
                $error_msg .= "\t" . $error;
            }
            HubLogger::error($error_msg);

            // Send Email to admin
            $email_subject = "TLS Article Importer Error - Failed Importing Article \"" . $article_data['post_title'] . "\"";
            wp_mail($this->admin_email, $email_subject, $error_msg);

        }

        return $article_id; // Returns the ID of the post that was inserted/updated
    }

    /**
     * Method to save all the Custom Fields for each article
     *
     * @param        $article_custom_fields
     * @param        $article_id
     * @param string $repeater
     */
    private function saveArticleCustomFields($article_custom_fields, $article_id, $repeater = '')
    {
        if (!empty($repeater)) {
            $repeater_obj = get_field($repeater, $article_id);
            $repeater_obj = $article_custom_fields;
            update_field($repeater, $repeater_obj, $article_id);
        } else {
            foreach ($article_custom_fields as $custom_field_key => $custom_field_value) {
                update_field($custom_field_key, $custom_field_value, $article_id);
            }
        }
    }

    /**
     * Method to save all tax terms into the article being passed from parseArticles method
     *
     * @param int $article_id
     * @param string|array $terms
     * @param string $taxonomy
     *
     * @return bool
     */
    private function saveArticleTaxonomyTerms($article_id, $terms, $taxonomy)
    {
        $tax_terms = array(); // Start an empty array of tax terms

        foreach ($terms as $term) {

            /*
             * If the Taxonomy is Article Section perform a string replacement on 'Reviews - '
             * This is because Sections coming from methode used to have that before the real name of each section
             */
            if ($taxonomy == 'article_section') {
                $term = str_replace('Reviews - ', '', $term);
            }

            /*
             * If the taxonomy term does not exist then create it
             */
            $tax_term = term_exists($term, $taxonomy);
            if ($tax_term == 0 || $tax_term == null) {
                wp_insert_term($term, $taxonomy);
            }

            $tax_terms[] = (string)$term; // Add the the current looped term into the $tax_terms array
        }

        $tax_terms_ids = wp_set_object_terms($article_id, $tax_terms,
            $taxonomy); // Assign the terms to a variable to test for errors

        /*
         * If there is a WP_Error thrown when setting a section to the article
         * Log the errors that were thrown
         */
        if (is_wp_error($tax_terms_ids)) {
            $article = get_post($article_id); // Get Article to use its Title in the Error Message

            $errors = $tax_terms_ids->get_error_messages();
            $error_msg = "Failed setting Article Section for WordPress Article: " . $article->post_title . "\n";
            foreach ($errors as $error) {
                $error_msg .= "\t" . $error;
            }
            HubLogger::error($error_msg);

            // Send Email to admin
            $email_subject = "TLS Article Importer Error - Failed setting Article Section for WordPress Article: \"" . $article->post_title . "\"";
            wp_mail($this->admin_email, $email_subject, $error_msg);

            return false;
        }

        return true;
    }

    /**
     * Download Images from URL
     *
     * @param string     $href
     * @param bool       $inline_image
     * @param string|int $image_counter
     *
     * @return array
     */
    private function handleImageUpload($href, $inline_image = false, $image_counter = '')
    {

        if ($inline_image === false) {

            switch (true) {
                case strpos($href, '440x220'):
                    $image_option = 'thumbnail_image';
                    break;
                case strpos($href, '1280x490'):
                    $image_option = 'hero_image';
                    break;
                case strpos($href, '760x320'):
                    $image_option = 'full_image';
                    break;
                case strpos($href, '.main_image'):
                    $image_option = 'main_image';
                    break;
            }

        }

        // Image XML
        $image_xml = simplexml_load_file((string) $href, null, LIBXML_NOCDATA);
        if ($image_xml === false) {
            $error_msg = "Failed loading Image XML\n";
            foreach (libxml_get_errors() as $error) {
                $error_msg .= "\t" . $error->message;
            }
            HubLogger::error($error_msg);

            // Send Email to admin
            $email_subject = "TLS Article Importer Error - Failed loading Image XML";
            wp_mail($this->admin_email, $email_subject, $error_msg);

            return false;
        }
        $imageCpiNamespace = $image_xml->children('cpi', true);

        $image_url = (string) $image_xml->link->attributes()->href;

        $image_type = explode('/', $image_xml->link->attributes()->type);
        $image_extension = array_pop($image_type);

        $temp_file = download_url($image_url, 500);
        $file_array = array(
            'name'      => $image_xml->title . '.' . $image_extension,
            'type'      => (string) $image_xml->link->attributes()->type,
            'tmp_name'  => $temp_file,
            'error'     => 0,
            'size'      => filesize($temp_file),
        );

        // Check for download errors
        if ( is_wp_error( $temp_file ) ) {
            @unlink( $file_array[ 'tmp_name' ] );

            $errors = $temp_file->get_error_messages();
            $error_msg = "Failed downloading image from url: " . $image_url . "\n";
            foreach ($errors as $error) {
                $error_msg .= "\t" . $error;
            }
            HubLogger::error($error_msg);

            // Send Email to admin
            $email_subject = "TLS Article Importer Error - Failed downloading image from url: " . $image_url;
            wp_mail($this->admin_email, $email_subject, $error_msg);

        }

        // Sideload Image to Media
        $image_title = (!empty($image_xml->title)) ? (string) $image_xml->title : (string) $imageCpiNamespace->description;
        $image_upload_id = media_handle_sideload($file_array, 0, $image_title);

        // Check for handle sideload errors.
        if ( is_wp_error( $image_upload_id ) ) {
            @unlink( $file_array['tmp_name'] );

            $errors = $image_upload_id->get_error_messages();
            $error_msg = "Failed downloading image: " . $image_url . "\n";
            foreach ($errors as $error) {
                $error_msg .= "\t" . $error;
            }
            HubLogger::error($error_msg);

            // Send Email to admin
            $email_subject = "TLS Article Importer Error - Failed downloading image: " . $image_url;
            wp_mail($this->admin_email, $email_subject, $error_msg);

        }

        if ($inline_image === false && empty($image_option)) {
            return null;
        }

        // Save Image Alt Text
        $image_alttext = (!empty($imageCpiNamespace->alttext)) ? (string) $imageCpiNamespace->alttext : $image_title;
        update_post_meta( $image_upload_id, '_wp_attachment_image_alt', $image_alttext);

        // Save Custom Attachment Metadata
        $attachment_custom_metadata = array(
            // Attachment Caption
            'field_5523b88f78941'   => (string)$imageCpiNamespace->caption,
            // Attachment Copyright
            'field_5523b8017893e'   => (string)$imageCpiNamespace->copyright,
            // Attachment Credit
            'field_5523b84b7893f'   => (string)$imageCpiNamespace->credit,
            // Attachment Photographer
            'field_5523b85578940'   => (string)$imageCpiNamespace->photographer
        );
        $this->saveArticleCustomFields($attachment_custom_metadata, $image_upload_id);

        // If it is an inline image and the counter is not null then return Attachment ID with image counter
        if ($inline_image === true && $image_counter !== '') {
            return array(
                $image_counter . '_attachment_id' => $image_upload_id
            );
        }

        return array(
            $image_option . '_attachment_id' => $image_upload_id
        );

    }

    /**
     * @param string    $article_content
     * @param array     $inline_images_search
     * @param array     $inline_images_replace
     *
     * @return string   $updated_content
     */
    private function searchReplaceInlineImages($article_content, $inline_images_search, $inline_images_replace)
    {
        $inline_images_replace_array = array();

        foreach ($inline_images_replace as $inline_image_id) {
            $image_url = wp_get_attachment_url($inline_image_id);
            $image_alttext = get_post_meta( $inline_image_id, '_wp_attachment_image_alt', true);

            $inline_images_replace_array[] = "<img src=\"{$image_url}\" alt=\"{$image_alttext}\" />";
        }

        $updated_content = str_replace($inline_images_search, $inline_images_replace_array, $article_content);

        return $updated_content;
    }

    /**
     * @param $attach_images_ids
     * @param $article_id
     */
    private function attachImagesToPost($attach_images_ids, $article_id)
    {
        foreach ($attach_images_ids as $attach_images_id) {
            wp_update_post( array(
                    'ID' => $attach_images_id,
                    'post_parent' => $article_id
                )
            );
        }
    }
}
