<?php

/**
 * Implement a PuSHSubscriptionInterface.
 */
class PuSHSubscription implements PuSHSubscriptionInterface {
  public $domain;
  public $subscriber_id;
  public $hub;
  public $topic;
  public $status;
  public $secret;
  public $post_fields;
  public $timestamp;

  /**
   * Load a subscription.
   */
  public static function load($domain, $subscriber_id) {
    
    // WP_Query arguments
    $args = array (
      'post_type'              => 'push_sub_feeds',
      'posts_per_page'         => '1',
      'meta_query'             => array(
        array(
          'key'       => 'pushfeed-subscription-id',
          'value'     => $subscriber_id,
          'compare'   => '=',
          'type'      => 'NUMERIC',
        ),
      ),
    );

    // The Query
    $pushfeed_query = new WP_Query( $args );

    var_dump($pushfeed_query);

    if ($v = db_query("SELECT * FROM {feeds_push_subscriptions} WHERE domain = :domain AND subscriber_id = :sid", array(':domain' => $domain, ':sid' => $subscriber_id))->fetchAssoc()) {
      $v['post_fields'] = unserialize($v['post_fields']);
      return new PuSHSubscription($v['domain'], $v['subscriber_id'], $v['hub'], $v['topic'], $v['secret'], $v['status'], $v['post_fields'], $v['timestamp']);
    }
  }

  /**
   * Create a subscription.
   */
  public function __construct($domain, $subscriber_id, $hub, $topic, $secret, $status = '', $post_fields = '') {
    $this->domain = $domain;
    $this->subscriber_id = $subscriber_id;
    $this->hub = $hub;
    $this->topic = $topic;
    $this->status = $status;
    $this->secret = $secret;
    $this->post_fields = $post_fields;
  }

  /**
   * Save a subscription.
   */
  public function save() {
    $this->timestamp = time();
    $this->delete($this->domain, $this->subscriber_id);
    drupal_write_record('feeds_push_subscriptions', $this);
  }

  /**
   * Delete a subscription.
   */
  public function delete() {
    db_delete('feeds_push_subscriptions')
      ->condition('domain', $this->domain)
      ->condition('subscriber_id', $this->subscriber_id)
      ->execute();
  }
}