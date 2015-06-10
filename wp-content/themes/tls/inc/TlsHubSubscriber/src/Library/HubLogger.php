<?php

namespace Tls\TlsHubSubscriber\Library;

use DateTimeZone;
use DateTime;
use Tls\TlsHubSubscriber\TlsHubSubscriberWP;

/**
 * Class HubLogger
 *
 * @package Tls\TlsHubSubscriber\Library
 * @author  Vitor Faiante
 */
class HubLogger implements Logger
{

    protected static function logs_date_time()
    {
        $date_time = new DateTime(null, new DateTimeZone('Europe/London'));

        $logs_date_time = $date_time->format('d M Y H:i') . ': ';

        return $logs_date_time;
    }

    /**
     * Method to log messages
     *
     * @param      $msg
     * @param null $code
     *
     * @return mixed
     */
    public static function log($msg, $code = null)
    {
        $current_options = get_option(TlsHubSubscriberWP::get_option_name());

//        if (isset($current_options['log_messages_switch']) && $current_options['log_messages_switch'] != 1) {
//            return;
//        }

        $logMessage = self::logs_date_time() . $msg . "\n";

        if ($code != null) {
            $logMessage = self::logs_date_time() . $msg . " (Code: " . $code . ")" . "\n";
        }
        $logMessage .= "--------------\n";

        $current_options['log_messages'] = $current_options['log_messages'] . $logMessage;
        update_option(TlsHubSubscriberWP::get_option_name(), $current_options);
    }

    /**
     * Method to log Error Messages
     *
     * @param      $msg
     * @param null $code
     *
     * @return mixed
     */
    public static function error($msg, $code = null)
    {
        $current_options = get_option(TlsHubSubscriberWP::get_option_name());

//        if (isset($current_options['error_messages_switch']) && $current_options['log_messages_switch'] != 1) {
//            return;
//        }

        $errorMessage = self::logs_date_time() . $msg . "\n";

        if ($code != null) {
            $errorMessage = self::logs_date_time() . $msg . " (Code: " . $code . ")" . "\n";
        }
        $errorMessage .= "--------------\n";

        $current_options['error_messages'] = $current_options['error_messages'] . $errorMessage;
        update_option(TlsHubSubscriberWP::get_option_name(), $current_options);
    }
}
