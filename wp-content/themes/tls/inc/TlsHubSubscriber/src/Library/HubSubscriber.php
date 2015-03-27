<?php

namespace Tls\TlsHubSubscriber\Library;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Stream\Stream;
use Tls\TlsHubSubscriber\TlsHubSubscriberWP;

/**
 * Class HubSubscriber
 *
 * @package Tls\TlsHubSubscriber\Library
 * @author  Vitor Faiante
 */
class HubSubscriber
{

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
    public function __construct($option_name, $current_options)
    {

        // Option Name for the Hub Integration Settings
        $this->option_name = $option_name;

        // Current Options for the Hub Integration
        $this->current_options = $current_options;

        // Domain to be used for the Callback uRL
        $this->domain = site_url('/');

        // Callback URL endpoint base to add at the end of the URL before the Subscription ID
        $this->endpoint_base = 'pushfeed';

        // Create Callback URL with the {Site URL}/pushfeed/{Subscription ID}
        if (isset($this->current_options['subscription_id'])) {
            $this->callbackUrl = $this->domain . $this->endpoint_base . '/' . $this->current_options['subscription_id'];
        }

        // Instantiate Guzzle Client to handle the HTTP Requests to be made
        $this->guzzleClient = new GuzzleClient();
    }


    /**
     * Method to handle the Subscribe Request to the Hub
     */
    public function subscribe()
    {

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
            "callbackUrl" => esc_url($this->callbackUrl),
            // URL Where the Hub will communicate with the WP
            "topicId" => esc_url($this->current_options['topic_url'])
            // URL of topic we are subscribing to
        ), JSON_UNESCAPED_SLASHES);

        $subscribeRequest = $this->guzzleClient->createRequest('POST', $hubUrl);
        $subscribeRequest->setHeader('Content-Type', 'application/json');
        $subscribeRequest->setBody(Stream::factory($subscribeJson));

        $subscribeResponse = $this->guzzleClient->send($subscribeRequest);

        // Check if Response is 200, 202 or 204 and add a log message otherwise log error message
        if (in_array($subscribeResponse->getStatusCode(), array(
            200,
            202,
            204
        ))) {
            HubLogger::log('Your Subscription request is being processed. Check back later to see if you are fully Subscribed',
                $subscribeResponse->getStatusCode());

            return true;
        } else {
            HubLogger::error('Error issuing Subscribe request to the Hub. Please make sure all your details are correct',
                $subscribeResponse->getStatusCode());

            return false;
        }
    }

    /**
     * Method to handle the requests coming into the endpoint callback URL
     */
    public function handleRequest()
    {

        // Allow for manual pull method for Debugging ONLY
        // TODO: Remove this before going live
        if (isset($_GET['tls_hub_debug']) && $_GET['tls_hub_debug'] == true) {
            if (isset($_GET['manual_pull']) && isset($_GET['pull_url'])) {
                $feed = file_get_contents(esc_url($_GET['pull_url']));
                $feedParser = new HubXmlParser($this->option_name, $this->current_options);
                $feedParser->parseFeed($feed);
                header('HTTP/1.1 200 "Found"', null, 200);
                exit();
            }

            // Make sure the request is POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('HTTP/1.1 404 "Not Found"', null, 404);
                exit();
            }

            switch ($_GET['HTTP_X_AMZ_SNS_MESSAGE_TYPE']) {

                case "SubscriptionConfirmation":
                    $this->verifySubscription();
                    break;

                case "Notification":
                    $this->receive();
                    break;

                default:
                    header('HTTP/1.1 404 "Not Found"', null, 404);
                    break;
            }
        } // END of Debugging section that needs to be removed before going live

        // Make sure the request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 404 "Not Found"', null, 404);
            exit();
        }

        // If HTTP Header does not contain the HTTP_X_AMZ_SNS_MESSAGE_TYPE header that the Hub sends then give a 404
        if (!isset($_SERVER['HTTP_X_AMZ_SNS_MESSAGE_TYPE'])) {
            header('HTTP/1.1 404 "Not Found"', null, 404);
            exit();
        }

        // Handle the different types of messages sent by the Hub
        switch ($_SERVER['HTTP_X_AMZ_SNS_MESSAGE_TYPE']) {

            case "SubscriptionConfirmation":
                $this->verifySubscription();
                break;

            case "Notification":
                $this->receive();
                break;

            default:
                header('HTTP/1.1 404 "Not Found"', null, 404);
                break;
        }

        exit();
    }

    /**
     * Method to handle receiving the XML Atom Feed
     */
    public function receive()
    {
        $feedPayload = file_get_contents('php://input');

        $feedParser = new HubXmlParser();
        $feedParser->parseFeed($feedPayload);

        header('HTTP/1.1 200 "Found"', null, 200);
        exit();
    }


    /**
     * Method to handle the Subscription Verification
     */
    public function verifySubscription()
    {

        // Grab the JSON Payload being sent from the Hub and Decode it
        $jsonPayload = file_get_contents('php://input');
        $json = json_decode($jsonPayload);

        // Perform a GET Request using Guzzle on the SubscribeURL that JSON Payload sent
        $verificationResponse = $this->guzzleClient->get($json->SubscribeURL);

        // Check if Response is 200, 202 or 204 and add a log message otherwise log error message
        if (in_array($verificationResponse->getStatusCode(), array(
            200,
            202,
            204
        ))) {
            $this->current_options['subscription_status'] = 'Subscribed';
            update_option($this->option_name, $this->current_options);

            HubLogger::log('Positive answer from Subscription Verification. You are now Subscribed',
                $verificationResponse->getStatusCode());

            header('HTTP/1.1 200 "Found"', null, 200);
        } else {
            HubLogger::error('Error from Subscription Verification', $verificationResponse->getStatusCode());

            header('HTTP/1.1 404 "Not Found"', null, 404);
        }
    }
}
