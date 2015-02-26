<?php

namespace Tls\TlsHubSubscriber\Library;

/**
 * Interface FeedParser
 *
 * @package Tls\TlsHubSubscriber\Library
 * @author  Vitor Faiante
 */
interface FeedParser
{

    /**
     * @param $feed
     *
     * @return mixed
     */
    public function parseFeed( $feed );

}