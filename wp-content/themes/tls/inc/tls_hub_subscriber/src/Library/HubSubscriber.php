<?php

namespace Tls\TlsHubSubscriber\Library;

use GuzzleHttp\Client as GuzzleClient;

/**
 *
 */
class HubSubscriber {
    protected $endpoint_base;
    protected $current_options;
    protected $domain;
    protected $guzzleClient;
    protected $callbackUrl;

    /**
     * @param $current_options
     */
    public function __construct($current_options) {
        $this->current_options = $current_options;
        $this->domain = site_url('/');
        $this->endpoint_base = 'pushfeed';

        $this->callbackUrl = $this->domain . $this->current_options['subscription_id'];

        $this->guzzleClient = new GuzzleClient();
    }

    /**
     *
     */
    public function subscribe() {
        if ( !$this->current_options['hub_url'] ) {
            //return 'Please fill in the Hub URL first';
        }
        $subcribeUrl = '';

        if ( preg_match("/\/$/", $this->current_options['hub_url'] ) ) {
            $subcribeUrl = $this->current_options['hub_url'] . 'subscribe';
        } else {
            $subcribeUrl = $this->current_options['hub_url'] . '/subscribe';
        }

        $subscribeJson = json_encode(array(
            "callbackUrl" => esc_url($this->callbackUrl),
            "topicId" => esc_url($this->current_options['topic_url'])
        ), JSON_UNESCAPED_SLASHES);

        $response = $this->guzzleClient->get($this->current_options['hub_url']);

        echo $subscribeJson;
    }

    public function unsubscribe($topic_url, $callback_url) {
        if ($sub = $this->subscription()) {
            $this->request($sub->hub, $sub->topic, 'unsubscribe', $callback_url);
            $sub->delete();
        }
    }


    public function handleRequest($callback) {
        if (isset($_GET['hub_challenge'])) {
            $this->verifyRequest();
        }
        // No subscription notification has ben sent, we are being notified.
        else {
            if ($raw = $this->receive()) {
                $callback($raw, $this->domain, $this->subscriber_id);
            }
        }
    }

    public function receive($ignore_signature = FALSE) {
        /**
         * Verification steps:
         *
         * 1) Verify that this is indeed a POST reuest.
         * 2) Verify that posted string is XML.
         * 3) Per default verify sender of message by checking the message's
         *    signature against the shared secret.
         */
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $raw = file_get_contents('php://input');
            if (@simplexml_load_string($raw)) {
                if ($ignore_signature) {
                    return $raw;
                }
                if (isset($_SERVER['HTTP_X_HUB_SIGNATURE']) && ($sub = $this->subscription())) {
                    $result = array();
                    parse_str($_SERVER['HTTP_X_HUB_SIGNATURE'], $result);
                    if (isset($result['sha1']) && $result['sha1'] == hash_hmac('sha1', $raw, $sub->secret)) {
                        return $raw;
                    }
                    else {
                        $this->log('Could not verify signature.', 'error');
                    }
                }
                else {
                    $this->log('No signature present.', 'error');
                }
            }
        }
        return FALSE;
    }

    public function verifyRequest() {
        if (isset($_GET['hub_challenge'])) {
            /**
             * If a subscription is present, compare the verify token. If the token
             * matches, set the status on the subscription record and confirm
             * positive.
             *
             * If we cannot find a matching subscription and the hub checks on
             * 'unsubscribe' confirm positive.
             *
             * In all other cases confirm negative.
             */
            if ($sub = $this->subscription()) {
                if ($_GET['hub_verify_token'] == $sub->post_fields['hub.verify_token']) {
                    if ($_GET['hub_mode'] == 'subscribe' && $sub->status == 'subscribe') {
                        $sub->status = 'subscribed';
                        $sub->post_fields = array();
                        $sub->save();
                        $this->log('Verified "subscribe" request.');
                        $verify = TRUE;
                    }
                    elseif ($_GET['hub_mode'] == 'unsubscribe' && $sub->status == 'unsubscribe') {
                        $sub->status = 'unsubscribed';
                        $sub->post_fields = array();
                        $sub->save();
                        $this->log('Verified "unsubscribe" request.');
                        $verify = TRUE;
                    }
                }
            }
            elseif ($_GET['hub_mode'] == 'unsubscribe') {
                $this->log('Verified "unsubscribe" request.');
                $verify = TRUE;
            }
            // Added this for testing. Need to remove
            elseif ($_GET['hub_mode'] == 'subscribe') {
                $this->log('Verified "subscribe" request.');
                $verify = TRUE;
            }
            if ($verify) {
                header('HTTP/1.1 200 "Found"', NULL, 200);
                print $_GET['hub_challenge'];
                exit();
            }
        }
        header('HTTP/1.1 404 "Not Found"', NULL, 404);
        $this->log('Could not verify subscription.', 'error');
        exit();
    }
}