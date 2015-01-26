<?php

namespace Tls\TlsHubSubscriber;
use Tls\TlsHubSubscriber\Library\HubSubscriber as HubSubscriber;

/**
 * Class TlsHubSubscriberFE
 *
 * This Class Deals with the "Front End" specific actions for the TLS Hub Subscriber (i.e. Rewrite Rules for end point URL, etc)
 *
 * @package Tls\TlsHubSubscriber
 */
class TlsHubSubscriberFE {

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
     * @param array $current_options Current Options for this Subscription. (Passed through the constructor by TlsHubSubscriberWP)
     */
    public function __construct($current_options) {

        // init Action Hook to add hub callback rewrite rules
        add_action('init', array($this, 'pushfeed_hub_callback_rewrite'));

        // query_vars filter to add subscription_id to the query vars
        add_filter('query_vars', array($this, 'pushfeed_hub_callback_query_vars'));

        // parse_request action hook to create the parse request for hub callback page visits
        add_action('parse_request', array($this, 'pushfeed_hub_callback_parser'));

        // Assign The Current Options Passed bythe TlsHubSubscriberWP to the class variable $current_options
        $this->current_options = $current_options;

        // Instantiate HubSubscriber and assign it to a class variable to be used later on
        $hubSubscriber = new HubSubscriber($current_options);
        $this->hubSubscriber = $hubSubscriber;
    }


    /**
     * Method to add PuSH Feed Hub Callback Rewrite Rule
     *
     */
    public function pushfeed_hub_callback_rewrite()
    {
        add_rewrite_rule('^pushfeed/([^/]*)/?', 'index.php?pagename=pushfeed&subscription_id=$matches[1]', 'top');

        flush_rewrite_rules();
    }

    /**
     * Method to handle and parse request to PuSH Feed Hub Callbacks
     *
     * @param object $wp Pass WordPress Object by reference
     */
    public function pushfeed_hub_callback_parser(&$wp) {

        if (array_key_exists('subscription_id', $wp->query_vars) && preg_match("/^[0-9]+$/", $wp->query_vars['subscription_id'])) {

            if ( $this->current_options['subscription_id'] != $wp->query_vars['subscription_id'] ) {
                wp_redirect( site_url('404') );
            }

            if ( isset($_GET['manual_pull']) && isset($_GET['pull_url']) ) {
                require_once TLS_TEMPLATE_DIR . '/inc/tls_hub_subscriber/src/simplexml-feed.php';
            }

            $this->hubSubscriber->subscribe();

            exit();

        }

    }

    /**
     * Method to add subscription_id to query_vars array
     *
     * @param  array $query_vars            WordPress $query_vars array
     * @return array $query_vars            Returns the new $query_vars with subscription_id added
     */
    public function pushfeed_hub_callback_query_vars($query_vars)
    {
        $query_vars[] = 'subscription_id';
        return $query_vars;
    }

    /**
     * @return string
     */
    public function getallheaders() {
        $headers = '';
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

/**
     * Method for the handleRequest method being called by the Subscription Class
     *
     * @param string $raw
     * @param string $domain
     * @param string $subscriber_id
     */
    public function pushfeed_notification($raw = '', $domain = '', $subscriber_id = '') {
        include_once 'simplexml-feed.php';
    }

}