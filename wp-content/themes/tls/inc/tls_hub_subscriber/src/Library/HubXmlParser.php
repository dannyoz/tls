<?php

namespace Tls\TlsHubSubscriber\Library;

use SimpleXMLElement;
use WP_Query;

/**
 * Class HubXmlParser
 *
 * @package Tls\TlsHubSubscriber\Library
 * @author Vitor Faiante
 */
class HubXmlParser implements FeedParser {

    /**
     * @var $option_name
     */
    private $option_name;

    /**
     * @var $current_options
     */
    protected $current_options;

    /**
     * Constructor
     *
     * @param $option_name
     * @param $current_options
     */
    public function __construct($option_name, $current_options) {
        $this->current_options = $current_options;
        $this->option_name = $option_name;
    }

    /**
     * Method to parse the XML Feed coming from the PHP Input stream in the TlsHubSubscriberFE Callback URL page parsing
     *
     * @param $feed
     * @return mixed
     */
    public function parseFeed($feed) {
        // Start Time counter to use for calculation of the time it takes to parse the feed
        $start_time = microtime(true);

        // Replace & with the HTML entity of &amp; which was causing an error to load the string
        $feed=preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $feed);
        // Turn on Simple XML Internal Errors to catch any errors that appear
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($feed);

        // If there are errors then add them to the error logs
        if ($xml === false) {
            $error_msg = "Failed loading XML\n";
            foreach(libxml_get_errors() as $error) {
                $error_msg .= "\t" . $error->message;
            }
            HubLogger::error($error_msg);
        }

        // Parse the article entries [Separate method so this method doesn't become extremely long]
        $articlesResult = $this->parseArticles($xml);

        // Stop Time Counter
        $end_time = microtime(true);
        // Calculate time it took to run through the import
        $execution_time = ($end_time - $start_time);

        // Add Log of the time it took and how many articles it imported
        $articles = ( $articlesResult['articleCount'] == 1 ) ? 'article' : 'articles';
        echo 'It took ' . $execution_time . ' seconds to ' . $articlesResult['import_type'] . ' ' . $articlesResult['articleCount'] . ' ' . $articles;
        HubLogger::log('It took ' . $execution_time . ' seconds to ' . $articlesResult['import_type'] . ' ' . $articlesResult['articleCount'] . ' ' . $articles);
    }

    /**
     * Method to parse each article entry from the XML feed and save all its data, taxonomy terms and custom fields
     *
     * @param object $xml   The XML Object being passed from the parseFeed method
     * @return int $articleCount    The count of how many articles were parsed to be returned back to parseFeed
     */
    protected function parseArticles($xml) {
        // Start the articleCount variable
        $articleCount = 0;

        foreach ($xml->entry as $article) {

            // Get all cpi: nodes from the XML
            $cpiNamespace = $article->children('cpi', true);

            // Add all the Article Data into an array
            $article_data = array(
                'post_content'   => $article->content->saveXml(), // The full text of the post.
                'post_title'     => wp_strip_all_tags( $article->title ), // The title of your post.
                'post_status'    => 'publish', // Default 'draft'.
                'post_type'      => 'tls_articles', // Default 'post'.
                'post_author'    => 1, // The user ID number of the author. Default is the current user ID.
                'ping_status'    => 'closed', // Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
                'post_date'      => date( "Y-m-d H:i:s", strtotime( $article->published ) ), // Published Date
                'post_date_gmt'  => date( "Y-m-d H:i:s", strtotime( $article->published ) ), // Published Date GMT
                'post_modified'      => date( "Y-m-d H:i:s", strtotime( $article->updated ) ), // Updated Date
                'post_modified_gmt'  => date( "Y-m-d H:i:s", strtotime( $article->updated ) ), // Updated Date GMT
                'comment_status' => 'closed', // Default is the option 'default_comment_status', or 'closed'.
            );

            // Prepare and make WP_Query to check if the article being parsed already exists in WP based on the ID in the XML Feed entry
            $articleMatches_args = array(
                'posts_per_page' => 1,
                'post_type' => 'tls_articles',
                'meta_key' => 'article_feed_id',
                'meta_value' => (string)$article->id
            );
            $articleMatches = new WP_Query($articleMatches_args);

            // If there is no article match then send the article data into saveArticleData method
            if (!$articleMatches->found_posts > 0) {
                $article_id = $this->saveArticleData($article_data);
                $import_type = 'import';
            } else { // If a match is found then add ID into the Article Data array and then send the data to the saveArticleData and with second parameter true to indicate an update and not an insert
                $article_data['ID'] = (int) $articleMatches->posts[0]->ID;
                $article_id = $this->saveArticleData($article_data, true);
                $import_type = 'update';
            }

            // Save Article Section Taxonomy Term
            $article_section = $this->saveArticleTaxonomyTerms($article_id, $cpiNamespace->section, 'article_section');
            // Save Article Visibility Taxonomy Term
            $article_visibility = $this->saveArticleTaxonomyTerms($article_id, $cpiNamespace->visibility, 'article_visibility');
            // Save Article Tags Taxonomy Terms
            $article_tags = $this->saveArticleTaxonomyTerms($article_id, $cpiNamespace->tag, 'article_tags');

            // TODO: Import of images into the local installation of WP
            // TODO: Import books into the repeater field of WP
            // Add all the Custom Fields' data into an array
            $article_custom_fields = array(
                'article_feed_id'       => (string) $article->id,
                'article_author_name'   => (string)$article->author->name,
                'article_author_email'  => (string)$article->author->email,
                'article_author_uri'    => (string)$article->author->uri,
                'headline'              => (string) $cpiNamespace->xpath('//cpi:headline')[0]->div[0]->p[0],
                'teaser_summary'        => (string) $cpiNamespace->xpath('//cpi:teasersummary')[0]->div->p[0],
                'thumbnail_image_url'   => '',
                'full_image_url'        => '',
                'hero_image_url'        => ''
            );
            // Send Custom Fields Data to saveArticleCustomFields method to be saved using the $article_id that came out of the saving or updating method
            $this->saveArticleCustomFields($article_custom_fields, $article_id);

            // Add 1 to the articleCount after parsing the article
            $articleCount++;
        }

        // Returns the number of articles parsed back into the parseFeed method to add a log of how many articles were imported
        return array( 'import_type' => $import_type, 'articleCount' => $articleCount );
    }

    /**
     * Method to save the Article Data and in case there is a WP_Error that comes out an error log will be written
     *
     * @param $article_data
     * @param bool $update
     * @return int|\WP_Error
     */
    private function saveArticleData($article_data, $update = false) {

        if ($update == true) {
            $article_id = wp_update_post($article_data, true);
        } else {
            $article_id = wp_insert_post($article_data, true);
        }

        if ( is_wp_error($article_id) ) {
            $errors = $article_id->get_error_messages();
            $error_msg = "Failed Importing Article" . $article_data['post_title'] .  " \n";
            foreach($errors as $error) {
                $error_msg .= "\t" . $error;
            }
            HubLogger::error($error_msg);
        }

        return $article_id;
    }

    /**
     * Method to save all the Custom Fields for each article
     *
     * @param $article_custom_fields
     * @param $article_id
     */
    private function saveArticleCustomFields($article_custom_fields, $article_id) {

        foreach($article_custom_fields as $custom_field_key => $custom_field_value) {
            update_field($custom_field_key, $custom_field_value, $article_id);
        }

    }

    /**
     * Method to save all tax terms into the article being passed from parseArticles method
     *
     * @param int $article_id
     * @param string|array $terms
     * @param string $taxonomy
     * @return bool
     */
    private function saveArticleTaxonomyTerms($article_id, $terms, $taxonomy) {
        $tax_terms = array();

        foreach ($terms as $term) {
            if ($taxonomy == 'article_section') {
                $term = str_replace('Reviews - ', '', $term);
            }
            $tax_term = term_exists($term, $taxonomy);
            if ($tax_term == 0 || $tax_term == null) {
                wp_insert_term($term, $taxonomy);
            }
            $tax_terms[] = (string) $term;
        }

        $tax_terms_ids = wp_set_object_terms($article_id, $tax_terms, $taxonomy);

        // If there is an error setting Object Terms then log those errors and return false
        if ( is_wp_error($tax_terms_ids) ) {
            $article = get_post($article_id); // Get Article to use its Title in the Error Message

            $errors = $tax_terms_ids->get_error_messages();
            $error_msg = "Failed setting Article Section for article: " . $article->post_title  . "\n";
            foreach($errors as $error) {
                $error_msg .= "\t" . $error;
            }
            HubLogger::error($error_msg);
            return false;
        }

        return true;
    }

}