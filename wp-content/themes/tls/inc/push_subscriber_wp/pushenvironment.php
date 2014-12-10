<?php

/**
 * Provide environmental functions to the PuSHSubscriber library.
 */
class PuSHEnvironment implements PuSHSubscriberEnvironmentInterface {
  /**
   * Singleton.
   */
  // public static function instance() {
  //   static $env;
  //   if (empty($env)) {
  //     $env = new PuSHEnvironment();
  //   }
  //   return $env;
  // }

  public static $msg_file = 'messages.log';
  public static $log_file = 'system.log';

  /**
   * A message to be displayed to the user on the current page load.
   *
   * @param $msg
   *   A string that is the message to be displayed.
   * @param $level
   *   A string that is either 'status', 'warning' or 'error'.
   */
  public function msg($msg, $level = 'status') {
    $data = "$level :: $msg\n";
    file_put_contents(self::$msg_file, $data, FILE_APPEND);
  }

  /**
   * A log message to be logged to the database or the file system.
   *
   * @param $msg
   *   A string that is the message to be displayed.
   * @param $level
   *   A string that is either 'status', 'warning' or 'error'.
   */
  public function log($msg, $level = 'status') {
    $data = "$level :: $msg\n";
    file_put_contents(self::$log_file, $data, FILE_APPEND);
  }
}