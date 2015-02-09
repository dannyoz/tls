<?php
/*
Template Name: Hub Pos Test
*/

$options = get_option(\Tls\TlsHubSubscriber\TlsHubSubscriberWP::get_option_name());
$guzzleClient = new \GuzzleHttp\Client();

// Start empty $hubUrl variable
$hubUrl = '';
$callbackUrl = site_url() . '/pushfeed/' . $options['subscription_id'];

// Check if the Hub URL finishes with a / or not to be able to create the correct subscribe URL
if (preg_match("/\/$/", $options['hub_url'])) {
    $hubUrl = $options['hub_url'];
} else {
    $hubUrl = $options['hub_url'];
}

// Json Data needed to send to the Hub for Subscription
$subscribeJson = json_encode(array(
    "callbackUrl" => esc_url($callbackUrl),               // URL Where the Hub will communicate with the WP
    "topicId" => esc_url($options['topic_url'])   // URL of topic we are subscribing to
), JSON_UNESCAPED_SLASHES);

$subscribeRequest = $guzzleClient->createRequest('POST', $hubUrl);
$subscribeRequest->setHeader('Content-Type', 'application/json');
$subscribeRequest->setBody(\GuzzleHttp\Stream\Stream::factory($subscribeJson));

$subscribeResponse = $guzzleClient->send($subscribeRequest);
