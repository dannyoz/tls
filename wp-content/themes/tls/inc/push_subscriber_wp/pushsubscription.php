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

  // Variables to be used with WordPress Posts of the PuSH Feed Post Type
  protected $post_id;
  protected $post_title;

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

    if ( $pushfeed_query->have_posts() ) : 
      
      while ( $pushfeed_query->have_posts() ) : $pushfeed_query->the_post();
      
        global $post;

        $this->post_id = $post->ID;
        $this->post_title = get_the_title();

        $subscription_domain = get_post_meta( $post->ID , 'pushfeed-domain' , true );
        $subscription_id = get_post_meta( $post->ID, 'pushfeed-subscription-id', true );
        $subscription_hub = get_post_meta( $post->ID, 'pushfeed-hub-url', true );
        $subscription_topic = get_post_meta( $post->ID, 'pushfeed-feed-url', true );
        $subscription_secret = get_post_meta ( $post->ID, 'pushfeed-secret', true );
        $subscription_status = get_post_meta( $post->ID, 'pushfeed-status', true );
        $subscription_post_fields = get_post_meta( $post->ID, 'pushfeed-post-fields' );

        return new PuSHSubscription($subscription_domain, $subscription_id, $subscription_hub, $subscription_topic, $subscription_secret, $subscription_status, $subscription_post_fields);

      endwhile;
    
    else:

      exit();
          
    endif;

    /*
    if ($v = db_query("SELECT * FROM {feeds_push_subscriptions} WHERE domain = :domain AND subscriber_id = :sid", array(':domain' => $domain, ':sid' => $subscriber_id))->fetchAssoc()) {
      $v['post_fields'] = unserialize($v['post_fields']);
      return new PuSHSubscription($v['domain'], $v['subscriber_id'], $v['hub'], $v['topic'], $v['secret'], $v['status'], $v['post_fields'], $v['timestamp']);
    }
    */
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
    
    wp_update_post(array(
      'ID' => $this->post_id,
      'post_title'     => wp_strip_all_tags($this->post_title)
    ));

    update_post_meta( $this->post_id , 'pushfeed-secret' , $this->secret );
    update_post_meta( $this->post_id , 'pushfeed-status' , $this->status );
    update_post_meta( $this->post_id , 'pushfeed-post-fields' , $this->post_fields );

  }

  /**
   * Delete a subscription.
   */
  public function delete() {
    
    wp_delete_post( $this->post_id );

  }
  
}