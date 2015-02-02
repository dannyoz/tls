<?php

namespace Tls\TlsHubSubscriber;
use Tls\TlsHubSubscriber\Library\HubSubscriber as HubSubscriber;

/**
 * Class TlsHubSubscriberFE
 *
 * This Class Deals with the "Front End" specific actions for the TLS Hub Subscriber (i.e. Rewrite Rules for end point URL, etc)
 *
 * @package Tls\TlsHubSubscriber
 * @author Vitor Faiante
 */
class TlsHubSubscriberFE {

    /**
     * @var string
     */
    private $option_name;

    /**
     * @var array
     */
    private $current_options;
    /**
     * @var Library\HubSubscriber
     */
    private $hubSubscriber;


    /**
     * [__construct Start TlsHubSubscriberFE actions, hooks, etc.]
     * @param $option_name
     * @param array $current_options Current Options for this Subscription. (Passed through the constructor by TlsHubSubscriberWP)
     */
    public function __construct( $option_name, $current_options) {

        // init Action Hook to add hub callback rewrite rules
        add_action('init', array($this, 'tls_hub_callback_rewrite'));

        // query_vars filter to add subscription_id to the query vars
        add_filter('query_vars', array($this, 'tls_hub_callback_query_vars'));

        // parse_request action hook to create the parse request for hub callback page visits
        add_action('parse_request', array($this, 'tls_hub_callback_parser'));

        // Assign Option Name passed into the constructor by TlsHubSubscriberWP to variable $option_name
        $this->option_name = $option_name;

        // Assign The Current Options passed into the constructor TlsHubSubscriberWP to variable $current_options
        $this->current_options = $current_options;

        // Instantiate HubSubscriber and assign it to a class variable to be used later on
        $hubSubscriber = new HubSubscriber($option_name, $current_options);
        $this->hubSubscriber = $hubSubscriber;
    }


    /**
     * Method to add PuSH Feed Hub Callback Rewrite Rule
     *
     */
    public function tls_hub_callback_rewrite()
    {
        add_rewrite_rule('^pushfeed/([^/]*)/?', 'index.php?pagename=pushfeed&subscription_id=$matches[1]', 'top');

        flush_rewrite_rules();
    }

    /**
     * Method to handle and parse request to PuSH Feed Hub Callbacks
     *
     * @param object $wp Pass WordPress Object by reference
     */
    public function tls_hub_callback_parser(&$wp) {

        if (array_key_exists('subscription_id', $wp->query_vars) && preg_match("/^[0-9]+$/", $wp->query_vars['subscription_id'])) {

            // Make sure the subscription ID matches the one in the database otherwise service 404
            if ( $this->current_options['subscription_id'] != $wp->query_vars['subscription_id'] ) {
                header('HTTP/1.1 404 "Not Found"', NULL, 404);
                exit();
            }

            $this->hubSubscriber->handleRequest();
            exit();

        }

    }

    /**
     * Method to add subscription_id to query_vars array
     *
     * @param  array $query_vars            WordPress $query_vars array
     * @return array $query_vars            Returns the new $query_vars with subscription_id added
     */
    public function tls_hub_callback_query_vars($query_vars)
    {
        $query_vars[] = 'subscription_id';
        return $query_vars;
    }

}