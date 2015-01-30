<?php

namespace Tls\TlsHubSubscriber\Library;
use DateTimeZone;
use DateTime;

/**
 * Class HubLogger
 * @package Tls\TlsHubSubscriber\Library
 */
class HubLogger implements Logger {

    /**
     * @var
     */
    private static $instance;

    /**
     * @var \DateTime
     */
    protected $date_time;

    /**
     * @var string
     */
    protected $logs_date_time;

    /**
     * @var string
     */
    private $option_name;

    /**
     * @var array
     */
    private $current_options;

    /**
     * @param $option_name
     * @param $current_options
     * @return HubLogger
     */
    public static function instance($option_name, $current_options) {
        if( null == self::$instance ) {
            self::$instance = new HubLogger($option_name, $current_options);
        } // end if
        return self::$instance;
    } // end getInstance

    /**
     * @param $option_name
     * @param $current_options
     */
    private function __construct($option_name, $current_options){
        // Option Name for the Hub Integration Settings
        $this->option_name = $option_name;

        // Current Options for the Hub Integration
        $this->current_options = $current_options;

        // Start New Date Time
        $this->date_time = new DateTime(null, new DateTimeZone('Europe/London'));

        $this->logs_date_time = $this->date_time->format('d M Y H:i') . ': ' ;
    }

    /**
     * Method to log messages
     *
     * @param $msg
     * @param null $code
     * @return mixed
     */
    public function log($msg, $code = null) {

        $logMessage = $this->logs_date_time . $msg . "\n";

        if ( $code != null ) {
            $logMessage = $this->logs_date_time . $msg . " (Code: " . $code . ")" . "\n";
        }
        $logMessage .= "--------------\n";
        $this->current_options['log_messages'] = $this->current_options['log_messages'] . $logMessage ;
        update_option($this->option_name, $this->current_options);
    }

    /**
     * Method to log Error Messages
     *
     * @param $msg
     * @param null $code
     * @return mixed
     */
    public function error($msg, $code = null) {

        $errorMessage = $this->logs_date_time . $msg . "\n";

        if ( $code != null ) {
            $errorMessage = $this->logs_date_time . $msg . " (Code: " . $code . ")" . "\n";
        }
        $errorMessage .= "--------------\n";

        $this->current_options['error_messages'] = $this->current_options['error_messages'] . $errorMessage ;
        update_option($this->option_name, $this->current_options);
    }
}