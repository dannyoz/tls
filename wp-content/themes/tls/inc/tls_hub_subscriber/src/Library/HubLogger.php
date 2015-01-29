<?php

namespace Tls\TlsHubSubscriber\Library;

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
     * @var string
     */
    private $option_name;

    /**
     * @var array
     */
    private $current_options;

    public static function instance($option_name, $current_options) {
        if( null == self::$instance ) {
            self::$instance = new HubLogger($option_name, $current_options);
        } // end if
        return self::$instance;
    } // end getInstance

    private function __construct($option_name, $current_options){
        // Option Name for the Hub Integration Settings
        $this->option_name = $option_name;

        // Current Options for the Hub Integration
        $this->current_options = $current_options;
    }

    /**
     * Method to log messages
     *
     * @param $msg
     * @param null $code
     * @return mixed
     */
    public function log($msg, $code = null) {

        $logMessage = $msg . "\n";

        if ( $code != null ) {
            $logMessage = $msg . "(Code: " . $code . ")\n";
        }
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

        $errorMessage = $msg . "\n";

        if ( $code != null ) {
            $errorMessage = $msg . "(Code: " . $code . ")\n";
        }

        $this->current_options['error_messages'] = $this->current_options['error_messages'] . $errorMessage ;
        update_option($this->option_name, $this->current_options);
    }
}