<?php

namespace Tls\TlsHubSubscriber\Library;

/**
 * Interface PuSHSubscriberEnvironmentInterface
 *
 * @package Tls\TlsHubSubscriber\Library
 * @author  Vitor Faiante
 */
interface Logger
{

    /**
     * Method to log messages
     *
     * @param $msg
     * @param $code
     *
     * @return mixed
     */
    public static function log($msg, $code = null);

    /**
     * Method to log Error Messages
     *
     * @param $msg
     * @param $code
     *
     * @return mixed
     */
    public static function error($msg, $code = null);
}
