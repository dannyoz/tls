<?php

namespace Tls\TlsHubSubscriber;

/**
 * Class TlsHubSubscriberFE
 *
 * This Class Deals with the "Front End" specific actions for the TLS Hub Subscriber (i.e. Rewrite Rules for end point URL, etc)
 *
 * @package Tls\TlsHubSubscriber
 */
class TlsHubSubscriberFE {


    /**
     * [__construct Start TlsHubSubscriberFE actions, hooks, etc.]
     *
     */
    public function __construct() {

        // init Action Hook to add hub callback rewrite rules
        add_action('init', array($this, 'pushfeed_hub_callback_rewrite'));

        // query_vars filter to add subscription_id to the query vars
        add_filter('query_vars', array($this, 'pushfeed_hub_callback_query_vars'));

        // parse_request action hook to create the parse request for hub callback page visits
        add_action('parse_request', array($this, 'pushfeed_hub_callback_parser'));

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
     * Method to handle and parse request to PuSH Feed Hub Callbacks
     *
     * @param object $wp Pass WordPress Object by reference
     */
    public function pushfeed_hub_callback_parser(&$wp) {

        if (array_key_exists('subscription_id', $wp->query_vars) && preg_match("/^[0-9]+$/", $wp->query_vars['subscription_id'])) {

            if (isset($_GET['manual_pull']) && isset($_GET['pull_url'])) {
                include_once 'simplexml-feed.php';
            }

            $domain = site_url();
            $subscriber_id = $wp->query_vars['subscription_id'];

            $sub = PuSHSubscriber::instance($domain, $subscriber_id, 'PuSHSubscription', new PuSHEnvironment());

            $sub->handleRequest(array($this, 'pushfeed_notification'));
            exit();

        }

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