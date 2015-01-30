<?php

namespace Tls\TlsHubSubscriber\Library;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Class HubSubscriber
 * @package Tls\TlsHubSubscriber\Library
 */
class HubSubscriber {

    /**
     * @var string
     */
    protected $option_name;

    /**
     * @var array
     */
    protected $current_options;

    /**
     * @var string
     */
    protected $endpoint_base;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var string
     */
    protected $callbackUrl;

    /**
     * @var GuzzleClient
     */
    protected $guzzleClient;


    /**
     * @param $option_name
     * @param $current_options
     */
    public function __construct($option_name, $current_options) {

        // Option Name for the Hub Integration Settings
        $this->option_name = $option_name;

        // Current Options for the Hub Integration
        $this->current_options = $current_options;

        // Domain to be used for the Callback uRL
        $this->domain = site_url('/');

        // Callback URL endpoint base to add at the end of the URL before the Subscription ID
        $this->endpoint_base = 'pushfeed';

        // Create Callback URL with the {Site URL}/pushfeed/{Subscription ID}
        $this->callbackUrl = $this->domain . $this->current_options['subscription_id'];

        // Instantiate Guzzle Client to handle the HTTP Requests to be made
        $this->guzzleClient = new GuzzleClient();

        // Hub Logger Class Instance
        $this->hubLogger = HubLogger::instance($option_name, $current_options);
    }


    /**
     * Method to handle the Subscribe Request to the Hub
     * TODO: Need to connect this with the Ajax from the Backend Subscribe request
     */
    public function subscribe() {

        // Start empty $hubUrl variable
        $hubUrl = '';

        // Check if the Hub URL finishes with a / or not to be able to create the correct subscribe URL
        if (preg_match("/\/$/", $this->current_options['hub_url'])) {
            $hubUrl = $this->current_options['hub_url'] . 'subscribe';
        } else {
            $hubUrl = $this->current_options['hub_url'] . '/subscribe';
        }

        // Json Data needed to send to the Hub for Subscription
        $subscribeJson = json_encode(array(
            "callbackUrl" => esc_url($this->callbackUrl),               // URL Where the Hub will communicate with the WP
            "topicId" => esc_url($this->current_options['topic_url'])   // URL of topic we are subscribing to
        ), JSON_UNESCAPED_SLASHES);

        // Send Post Request with Guzzle to the Hub with the JSON Payload in the body
        $subscribeResponse = $this->guzzleClient->post($hubUrl, array(
            'content-type' => 'application/json',
            'body' => $subscribeJson
        ));

        // Check if Response is 200, 202 or 204 and add a log message otherwise log error message
        if (in_array($subscribeResponse->getStatusCode(), array(200, 202, 204))) {
            $this->hubLogger->log('Positive response to Subscribe request to the Hub', $subscribeResponse->getStatusCode());
        } else {
            $this->hubLogger->error('Error issuing Subscribe request to the Hub', $subscribeResponse->getStatusCode());
        }

    }

    /**
     * Method to handle the requests coming into the endpoint callback URL
     */
    public function handleRequest() {

        // Allow for manual pull method for Testing ONLY
        // TODO: Remove this before going live
        if ( isset($_GET['manual_pull']) && isset($_GET['pull_url']) ) {
            $feed = file_get_contents( esc_url($_GET['pull_url']) );
            $feedParser = new HubXmlParser($this->option_name, $this->current_options);
            $feedParser->parseFeed($feed);
            header('HTTP/1.1 200 "Found"', NULL, 200);
            exit();
        }

        // Make sure the request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 404 "Not Found"', NULL, 404);
            exit();
        }

        // TODO: Change this to $_SERVER before real live testing to test correct HTTP header instead of the testing $_GET variable
        if ( !isset($_GET['HTTP_X_AMZ_SNS_MESSAGE_TYPE']) ) {
            header('HTTP/1.1 404 "Not Found"', NULL, 404);
            exit();
        }

        // TODO: Change the switch case condition to $_SERVER before real live testing to test correct HTTP header instead of the testing $_GET variable
        switch ( $_GET['HTTP_X_AMZ_SNS_MESSAGE_TYPE'] ) {

            case "SubscriptionConfirmation":
                $this->verifySubscription();
                break;

            case "Notification":
                $this->receive();
                break;

            default:
                header('HTTP/1.1 404 "Not Found"', NULL, 404);
                break;
        }

        exit();

    }

    /**
     * Method to handle receiving the XML Atom Feed
     */
    public function receive() {
        $feedPayload = file_get_contents('php://input');

        $feedParser = new HubXmlParser($this->option_name, $this->current_options);
        $feedParser->parseFeed($feedPayload);

        header('HTTP/1.1 200 "Found"', NULL, 200);
        exit();
    }


    /**
     * Method to handle the Subscription Verification
     */
    public function verifySubscription() {

        // Grab the JSON Payload being sent from the Hub and Decode it
        $jsonPayload = file_get_contents('php://input');
        $json = json_decode($jsonPayload);

        // Perform a GET Request using Guzzle on the SubscribeURL that JSON Payload sent
        $verificationResponse = $this->guzzleClient->get($json->SubscribeURL);

        // Check if Response is 200, 202 or 204 and add a log message otherwise log error message
        if (in_array($verificationResponse->getStatusCode(), array(200, 202, 204))) {
            $this->hubLogger->log('Positive answer from Subscription Verification', $verificationResponse->getStatusCode());
            $this->current_options['subscription_status'] = 'Subscribed';
            update_option($this->option_name, $this->current_options);
        } else {
            $this->hubLogger->error('Error from Subscription Verification', $verificationResponse->getStatusCode());
        }

        header('HTTP/1.1 200 "Found"', NULL, 200);
        exit();
    }

}