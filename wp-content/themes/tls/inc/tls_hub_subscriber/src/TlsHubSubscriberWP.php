<?php namespace TlsHubSubscriber;

use TlsHubSubscriber\PuSHSubscriber as PuSHSubscriber;
use TlsHubSubscriber\PuSHSubscription as PuSHSubscription;
use TlsHubSubscriber\PuSHEnvironment as PuSHEnvironment;

class TlsHubSubscriberWP {

	// Name of the array
	protected $option_name = 'tls_hub_sub';

	// Default values
	protected $data = array(
		'subscription_id'		=> null,
		'topic_url'				=> null,
		'log_messages'			=> null,
		'error_messages'		=> null,
		'subscription_status'	=> null
	);


	/**
	 * [__construct Start PuSHSubscriberWP actions, hooks, etc.]
	 */
	public function __construct() {

		// Add TLS Hub Subscriber Setting Menu to Admin Menu
		add_action( 'admin_menu', array( $this, 'tls_hub_subscriber_settings' ) );

		// Start TLS Hub Subscriber Settings
		add_action( 'admin_init', array( $this, 'tls_hub_subscriber_settings_init' ) );

		// admin head edit.php action hook for PuSH Feed
		// Subscribe and Unsubscribe
		add_action( 'edit_post', array( $this, 'pushfeed_custom_subscribe_unsubscribe' ) );

		// init Action Hook to add hub callback rewrite rules
		add_action( 'init', array( $this, 'pushfeed_hub_callback_rewrite' ) );

		// query_vars filter to add subscription_id to the query vars
		add_filter( 'query_vars', array( $this, 'pushfeed_hub_callback_query_vars' ) );

		// parse_request action hook to create the parse request for
		// hub callback page visits
		add_action( 'parse_request', array( $this, 'pushfeed_hub_callback_parser' ) );
	}

	public function tls_hub_subscriber_settings_init() {

		register_setting(
			'tls_hub_subscriber_options',
			$this->option_name,
			array($this, 'tls_hub_sub_validate')
		);

	}

	public function tls_hub_subscriber_settings() {
		add_menu_page(
			'TLS Hub Subscriber',									// Text displayed in the browser title bar
			'Hub Subscriber', 										// Text used for the menu item
			'manage_options', 										// Minimum required capability of users to access this menu
			'tls-hub-subscriber', 									// Slug used to access this menu item
			array( $this, 'render_tls_hub_subscriber_settings'), 	// Name of the function used to display the page content
			'dashicons-rss' 										// Icon to display in the admin menu
		);
	}

	public function render_tls_hub_subscriber_settings(){
		$options = get_option($this->option_name);
		?>
		<div class="wrap">
			<h2>TLS Hub Subscriber Settings</h2>
			<form method="post" action="options.php">
				<div class="poststuff">
					<!-- run the settings_errors() function here. -->
					<?php settings_errors(); ?>
					<?php settings_fields('tls_hub_subscriber_options'); ?>
					<div id="post-body" class="metabox-holder">
						<div id="postbox-container-1" class="postbox-container">
							<div class="postbox">
								<div class="inside">

									<table class="form-table">

										<tr valign="top"><th scope="row">Topic URL:</th>
											<td>
												<input type="text" name="<?php echo $this->option_name?>[topic_url]" value="<?php echo $options['topic_url']; ?>" />
											</td>
										</tr>

										<tr valign="top"><th scope="row">Subscription Status:</th>
											<td>
												<input type="text" name="<?php echo $this->option_name?>[subscription_status]" value="<?php echo $options['subscription_status']; ?>" disabled />
											</td>
										</tr>

										<tr valign="top"><th scope="row">Log Messages:</th>
											<td>
												<textarea name="<?php echo $this->option_name; ?>[log_messages]" id="<?php echo $this->option_name; ?>[log_messages]" cols="30" rows="10"><?php echo $options['log_messages']; ?></textarea>
											</td>
										</tr>

										<tr valign="top"><th scope="row">Error Messages:</th>
											<td>
												<textarea name="<?php echo $this->option_name; ?>[error_messages]" id="<?php echo $this->option_name; ?>[error_messages]" cols="30" rows="10"><?php echo $options['error_messages']; ?></textarea>
											</td>
										</tr>

									</table>

									<p class="submit">
										<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
									</p>

								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<?php

	}


	public function tls_hub_sub_validate($input) {
		//die( var_dump($input) );
		$valid = array();
		$valid['topic_url'] = sanitize_text_field($input['topic_url']);
		$valid['subscription_status'] = sanitize_text_field($input['subscription_status']);
		$valid['log_messages'] = sanitize_text_field($input['log_messages']);
		$valid['error_messages'] = sanitize_text_field($input['error_messages']);

		/**
		 * URL Validation Reg Ex
		 * https://mathiasbynens.be/demo/url-regex
		 */
		$urlRegEx = '_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS';

		if ( !preg_match( $urlRegEx, $valid['topic_url'] ) ) {
			add_settings_error(
				'topic_url', 												// Setting Title
				'topic_url_error',											// Error ID
				'Please enter a valid URL',									// Error Message
				'error'														// Type of Message
			);

			$valid['topic_url'] = $this->data['topic_url'];
		}

//		if ( !$valid['subscription_status'] == 'Unsubscribed' || !$valid['subscription_status'] == 'Subscribed' || !$valid['subscription_status'] == 'Unsubscribing' || !$valid['subscription_status'] == 'Subscribing' ) {
//			add_settings_error(
//				'subscription_status',										// Setting Title
//				'subscription_status_error',								// Error ID
//				'Please do not manually change the Subscription Status',	// Error Message
//				'error'														// Type of Message
//			);
//
//			$valid['subscription_status'] = $this->data['subscription_status'];
//		}

		return $valid;
	}


	public function pushfeed_custom_subscribe_unsubscribe( $post_id ) {

	    if( isset( $_GET['pubsub_subscribe'] ) ) {
	        echo 'Yo bro';
	    }
	}

	/**
	 * Method to add PuSH Feed Hub Callback Rewrite Rule
	 * @return rewrite_rule Adds a new rewrite_rule to WordPress
	 */
	public function pushfeed_hub_callback_rewrite() {
		add_rewrite_rule('^pushfeed/([^/]*)/?','index.php?pagename=pushfeed&subscription_id=$matches[1]','top');

		flush_rewrite_rules();
	}

	/**
	 * Method to add subscription_id to query_vars array
	 * @param  array $query_vars WordPress $query_vars array
	 * @return array             Returns the new $query_vars 
	 * with subscription_id added
	 */
	public function pushfeed_hub_callback_query_vars( $query_vars ) {
	    $query_vars[] = 'subscription_id';
	    return $query_vars;
	}

	/**
	 * Method to handle and parse request to PuSH Feed Hub Callbacks
	 * @param object Pass WordPress Object by reference
	 */
	public function pushfeed_hub_callback_parser( &$wp ) {

	    if ( array_key_exists( 'subscription_id', $wp->query_vars ) && preg_match( "/^[0-9]+$/", $wp->query_vars['subscription_id'] ) ) {

			if ( isset( $_GET['manual_pull'] ) && isset( $_GET['pull_url'] ) ) {
				include_once 'simplexml-feed.php';
			}

	    	$domain = site_url();
		    $subscriber_id = $wp->query_vars['subscription_id'];

		    $sub = PuSHSubscriber::instance($domain, $subscriber_id, 'PuSHSubscription', new PuSHEnvironment());
  			
  			$sub->handleRequest(array($this, 'pushfeed_notification'));
  			exit();

	    }

	}

	public function pushfeed_notification($raw = '', $domain = '', $subscriber_id ='') {
		include_once 'simplexml-feed.php';
	}

}