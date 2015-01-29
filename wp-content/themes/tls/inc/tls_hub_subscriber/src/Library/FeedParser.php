<?php

namespace Tls\TlsHubSubscriber\Library;

/**
 * Interface FeedParser
 * @package Tls\TlsHubSubscriber\Library
 */
interface FeedParser {

    /**
     * @param $feed
     * @return mixed
     */
    public function parseFeed($feed);

}