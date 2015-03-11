<?php

namespace Tls\TlsHubSubscriber\Library;

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
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Method to parse the XML Feed coming from the PHP Input stream in the TlsHubSubscriberFE Callback URL page parsing
     *
     * @param $feed
     *
     * @return mixed
     */
    public function parseFeed( $feed )
    {
        // Start Time counter to use for calculation of the time it takes to parse the feed
        $start_time = microtime( true );

        // Replace & with the HTML entity of &amp; which was causing an error to load the string
        $feed = preg_replace( '/&(?!#?[a-z0-9]+;)/', '&amp;', $feed );
        // Turn on Simple XML Internal Errors to catch any errors that appear
        libxml_use_internal_errors( true );
        $xml = simplexml_load_string( $feed, null, LIBXML_NOCDATA );

        // If there are errors then add them to the error logs
        if ( $xml === false ) {
            $error_msg = "Failed loading XML\n";
            foreach ( libxml_get_errors() as $error ) {
                $error_msg .= "\t" . $error->message;
            }
            HubLogger::error( $error_msg );
        }

        // Parse the article entries [Separate method so this method doesn't become extremely long]
        $articlesResult = $this->parseArticles( $xml );

        // Stop Time Counter
        $end_time = microtime( true );
        // Calculate time it took to run through the import
        $execution_time = ( $end_time - $start_time );

        // Add Log of the time it took and how many articles it imported
        $articles = ( $articlesResult[ 'articleCount' ] == 1 ) ? 'article' : 'articles';
        echo 'It took ' . number_format( $execution_time, 2 ) . ' seconds to ' . $articlesResult[ 'import_type' ] . ' ' . $articlesResult[ 'articleCount' ] . ' ' . $articles;
        HubLogger::log( 'It took ' . number_format( $execution_time, 2 ) . ' seconds to ' . $articlesResult[ 'import_type' ] . ' ' . $articlesResult[ 'articleCount' ] . ' ' . $articles );
    }

    /**
     * Method to parse each article entry from the XML feed and save all its data, taxonomy terms and custom fields
     *
     * @param $article
     *
     * @return int $articleCount    The count of how many articles were parsed to be returned back to parseFeed
     */
    protected function parseArticles( $article )
    {
        // Start the articleCount variable
        $articleCount = 0;

        // Get all cpi: nodes from the XML
        $cpiNamespace = $article->children( 'cpi', true );

        // Get Article Entry ID from the URL in the id node
        // ID after the last slash /
        $article_id_url = explode('/', $article->id);
        $article_entry_id = array_pop($article_id_url);

        $article_entry_published = new Carbon( $article->published );
        $article_entry_updated = new Carbon( $article->updated );

        // Add all the Article Data into an array
        $article_data = array(
            'post_content' => $cpiNamespace->copy, // The full text of the post.
            'post_title' => wp_strip_all_tags( $cpiNamespace->headline ), // The title of your post.
            'post_status' => 'publish', // Default 'draft'.
            'post_type' => 'tls_articles', // Default 'post'.
            'post_author' => 1, // The user ID number of the author. Default is the current user ID.
            'ping_status' => 'closed', // Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
            'comment_status' => 'closed', // Default is the option 'default_comment_status', or 'closed'.
        );

        /*
         * Prepare and make WP_Query to check if the article being parsed already exists in WP based on the ID in the XML Feed entry
         */
        $articleMatches_args = array( 'posts_per_page' => 1, 'post_type' => 'tls_articles', 'meta_key' => 'article_feed_id', 'meta_value' => (string) $article_entry_id );
        $articleMatches = new WP_Query( $articleMatches_args );

        /*
         * Check to see if there wasn't any matches. If no match is found then send $article_data to saveArticleData method to insert new article
         * Otherwise if a match was found the send $article_data to saveArticleData method with update parameter true to update article instead
         */
        if ( !$articleMatches->found_posts > 0 ) {

            $article_data['post_date'] = $article_entry_published->toDateTimeString(); // Published Date
            $article_data['post_date_gmt'] = $article_entry_published->toDateTimeString(); // Published Date GMT
            $article_data['post_modified'] = $article_entry_updated->toDateTimeString(); // Updated Date
            $article_data['post_modified_gmt'] = $article_entry_updated->toDateTimeString(); // Updated Date GMT

            $article_id = $this->saveArticleData( $article_data );
            $import_type = 'import';
        } else {
            $last_updated_date = new Carbon(get_field('field_54eb50af14d87', $articleMatches->posts[0]->ID));

            if ( $article_entry_updated->toDayDateTimeString() <= $last_updated_date->toDayDateTimeString()) {
                die('This article will not be imported because this article seems older than another one we have');
            }

            $article_data[ 'ID' ] = (int) $articleMatches->posts[ 0 ]->ID;
            $article_id = $this->saveArticleData( $article_data, true );
            $import_type = 'update';
        }

        // Save Article Section Taxonomy Term
        $article_section = $this->saveArticleTaxonomyTerms( $article_id, $cpiNamespace->section, 'article_section' );
        // Save Article Visibility Taxonomy Term
        $article_visibility = $this->saveArticleTaxonomyTerms( $article_id, $cpiNamespace->visibility, 'article_visibility' );
        // Save Article Tags Taxonomy Terms
        $article_tags = $this->saveArticleTaxonomyTerms( $article_id, $cpiNamespace->tag, 'article_tags' );

        // TODO: Import of images into the local installation of WP
        // Add all the Custom Fields' data into an array
        $article_custom_fields = array(
            'field_54e4d372b0093' => (string) $article_entry_id, // Article Feed ID
            'field_54eb50af14d87' => (string) $article_entry_updated->toDateTimeString(), // Last Updated Date
            'field_54e4d3b1b0094' => (string) $cpiNamespace->byline, // Author Name
            'field_54e4d3c3b0095' => tls_make_post_excerpt($cpiNamespace->copy, 20), // Teaser Summary
            'field_54e4d481b009a' => '', // Thumbnail Image
            'field_54e4d4a5b009b' => '', // Full Image
            'field_54e4d4b3b009c' => '' // Hero Image
        );
        // Send Custom Fields Data to saveArticleCustomFields method to be saved using the $article_id that came out of the saving or updating method
        $this->saveArticleCustomFields( $article_custom_fields, $article_id );

        // Article Review Books
        $books = array();
        foreach ( $article->xpath( '//cpi:book' ) as $book ) {

            $books[ ] = array( 'book_title' => (string) $book->title, 'book_author' => (string) $book->author, 'book_info' => (string) $book->info, 'book_isbn' => (string) $book->isbn );

        }
        $this->saveArticleCustomFields( $books, $article_id, 'field_54edde1e60d80' ); // Books

        // Add 1 to the articleCount after parsing the article
        $articleCount++;

        // Returns the number of articles parsed back into the parseFeed method to add a log of how many articles were imported
        return array( 'import_type' => $import_type, 'articleCount' => $articleCount );
    }

    /**
     * Method to save the Article Data and in case there is a WP_Error that comes out an error log will be written
     *
     * @param      $article_data
     * @param bool $update
     *
     * @return int|\WP_Error
     */
    private function saveArticleData( $article_data, $update = false )
    {

        /*
         * If $update parameter is true call update wordpress function, otherwise call the insert function
         * Both update and insert function will have the second parameter for the WP_Error to be true which
         * will throw a WP_Error if there is any error in updating or inserting the posts
         */
        if ( $update == true ) {
            $article_id = wp_update_post( $article_data, true );
        } else {
            $article_id = wp_insert_post( $article_data, true );
        }

        /*
         * If any WP_Error is thrown when inserting or updating the article then
         * Log those errors
         */
        if ( is_wp_error( $article_id ) ) {
            $errors = $article_id->get_error_messages();
            $error_msg = "Failed Importing Article" . $article_data[ 'post_title' ] . " \n";
            foreach ( $errors as $error ) {
                $error_msg .= "\t" . $error;
            }
            HubLogger::error( $error_msg );
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
    private function saveArticleCustomFields( $article_custom_fields, $article_id, $repeater = '' )
    {
        if ( !empty( $repeater ) ) {
            $repeater_obj = get_field( $repeater, $article_id );
            $repeater_obj = $article_custom_fields;
            update_field( $repeater, $repeater_obj, $article_id );
        } else {

            foreach ( $article_custom_fields as $custom_field_key => $custom_field_value ) {

                update_field( $custom_field_key, $custom_field_value, $article_id );

            }
        }

    }

    /**
     * Method to save all tax terms into the article being passed from parseArticles method
     *
     * @param int          $article_id
     * @param string|array $terms
     * @param string       $taxonomy
     *
     * @return bool
     */
    private function saveArticleTaxonomyTerms( $article_id, $terms, $taxonomy )
    {
        $tax_terms = array(); // Start an empty array of tax terms

        foreach ( $terms as $term ) {

            /*
             * If the Taxonomy is Article Section perform a string replacement on 'Reviews - '
             * This is because Sections coming from methode used to have that before the real name of each section
             */
            if ( $taxonomy == 'article_section' ) {
                $term = str_replace( 'Reviews - ', '', $term );
            }

            /*
             * If the taxonomy term does not exist then create it
             */
            $tax_term = term_exists( $term, $taxonomy );
            if ( $tax_term == 0 || $tax_term == null ) {
                wp_insert_term( $term, $taxonomy );
            }

            $tax_terms[ ] = (string) $term; // Add the the current looped term into the $tax_terms array

        }

        $tax_terms_ids = wp_set_object_terms( $article_id, $tax_terms, $taxonomy ); // Assign the terms to a variable to test for errors

        /*
         * If there is a WP_Error thrown when setting a section to the article
         * Log the errors that were thrown
         */
        if ( is_wp_error( $tax_terms_ids ) ) {
            $article = get_post( $article_id ); // Get Article to use its Title in the Error Message

            $errors = $tax_terms_ids->get_error_messages();
            $error_msg = "Failed setting Article Section for article: " . $article->post_title . "\n";
            foreach ( $errors as $error ) {
                $error_msg .= "\t" . $error;
            }
            HubLogger::error( $error_msg );

            return false;
        }

        return true;
    }

}
