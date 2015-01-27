<?php

namespace Tls\TlsHubSubscriber\Library;

/**
 * Class HubXmlParser
 * @package Tls\TlsHubSubscriber\Library
 */
class HubXmlParser implements FeedParser {

    /**
     * @var
     */
    private $option_name;

    /**
     * @var
     */
    protected $current_options;

    /**
     * @var HubLogger
     */
    protected $hubLogger;

    /**
     * @param $option_name
     * @param $current_options
     */
    public function __construct($option_name, $current_options) {
        $this->current_options = $current_options;
        $this->option_name = $option_name;

        // Hub Logger Class Instance
        $this->hubLogger = HubLogger::instance($option_name, $current_options);
    }

    /**
     * @param $feed
     * @return mixed
     */
    public function parseFeed($feed) {
        // TODO: Implement parseFeed() method.
        var_dump($feed);
    }
}