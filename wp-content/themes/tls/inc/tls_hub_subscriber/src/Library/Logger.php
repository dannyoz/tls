<?php

namespace Tls\TlsHubSubscriber\Library;

/**
 * Interface PuSHSubscriberEnvironmentInterface
 * @package Tls\TlsHubSubscriber\Library
 */
interface Logger {

    /**
     * Method to log messages
     *
     * @param $msg
     * @param $code
     * @return mixed
     */
    public function log($msg, $code = null);

    /**
     * Method to log Error Messages
     *
     * @param $msg
     * @param $code
     * @return mixed
     */
    public function error($msg, $code = null);

}