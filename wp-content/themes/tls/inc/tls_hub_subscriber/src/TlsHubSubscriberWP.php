<?php namespace Tls\TlsHubSubscriber;

/**
 * Class TlsHubSubscriberWP
 * @package Tls\TlsHubSubscriber
 */
class TlsHubSubscriberWP {

	/**
	 * @var string $option_name		Option Name used for the options page
     */
	protected $option_name = 'tls_hub_sub';

	/**
	 * @var array $data		Default Values for the Data
     */
	protected $data = array(
		'subscription_id'		=> '',
		'topic_url'				=> '',
		'hub_url'				=> '',
		'log_messages'			=> '',
		'error_messages'		=> '',
		'subscription_status'	=> 'Unsubscribed'
	);

	/**
	 * @var array $current_options	Current Options saved
     */
	protected $current_options;

	/**
	 * @var TlsHubSubscriberFE
     */
	protected $TlsHubSubscriberFE;


	/**
	 * [__construct Start PuSHSubscriberWP actions, hooks, etc.]
	 */
	public function __construct() {

		// Add TLS Hub Subscriber Setting Menu to Admin Menu
		add_action( 'admin_menu', array( $this, 'tls_hub_subscriber_settings' ) );

		// Start TLS Hub Subscriber Settings
		add_action( 'admin_init', array( $this, 'tls_hub_subscriber_settings_init' ) );

		// Enqueue JavaScript File to handle Ajax Call on Settings Page for the Subscribe and Unsubscribe Button
		add_action( 'admin_init', array( $this, 'hub_action_javascript') );

		// Ajax Callback Function to handle the Ajax Response on Settings Page for Subscribe and Unsubscribe actions
		add_action( 'wp_ajax_hub_action', array( $this, 'hub_action_callback' ) );

		// Grab all current options and add them to the $current_options variable
		$this->current_options = $this->get_current_options();

		// Start TLS Hub Subscriber FE Class
		$TlsHubSubscriberFE = new TlsHubSubscriberFE($this->option_name, $this->current_options);
		$this->TlsHubSubscriberFE = $TlsHubSubscriberFE;
	}

	/**
	 * Get current saved Options
	 *
	 * @return array	Returns the current saved Options
     */
	protected function get_current_options() {
		return get_option( $this->option_name );
	}

	/**
	 * Initialize Settings
     */
	public function tls_hub_subscriber_settings_init() {

		register_setting(
			'tls_hub_subscriber_options',
			$this->option_name,
			array($this, 'tls_hub_sub_validate')
		);

	}

	/**
	 * Add WP Menu Page for the Hub Settings
     */
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

	/**
	 * Display the Settings Form on the Hub Settings Page
     */
	public function render_tls_hub_subscriber_settings(){
		?>
		<div class="wrap" xmlns="http://www.w3.org/1999/html">
			<h2>TLS Hub Subscriber Settings</h2>
			<form method="post" action="<?php echo admin_url('options.php'); ?>">
				
				<div class="poststuff">
					<!-- run the settings_errors() function here. -->
					<?php settings_errors(); ?>
					<?php settings_fields('tls_hub_subscriber_options'); ?>
					<div id="post-body" class="metabox-holder">
						<div id="tls_hub_container" class="postbox-container">
							<div class="postbox">
								<div class="inside">

									<table class="form-table">

										<tr valign="top"><th scope="row">Topic URL:</th>
											<td>
												<input id="topic_url" class="widefat" type="text" name="<?php echo $this->option_name?>[topic_url]" value="<?php echo ( isset( $this->current_options['topic_url'] ) ) ? $this->current_options['topic_url'] : ''; ?>" />
												<p class="description">Please include the http:// in the Topic URL</p>
											</td>
										</tr>

										<tr valign="top"><th scope="row">Hub URL:</th>
											<td>
												<input id="hub_url" class="widefat" type="text" name="<?php echo $this->option_name?>[hub_url]" value="<?php echo ( isset( $this->current_options['hub_url'] ) ) ? $this->current_options['hub_url'] : ''; ?>" />
												<p class="description">Please include the http:// in the Hub URL</p>
											</td>
										</tr>

										<tr valign="top"><th scope="row">Subscription Status:</th>
											<td>
												<p class="subscription_status"><?php echo ( isset($this->current_options['subscription_status']) ) ? $this->current_options['subscription_status'] : ''; ?></p>
											</td>
										</tr>

										<tr valign="top">
											<th scope="row">Log Messages:
												<p class="description">If you want to clear all Log messages select all the messages and delete them before saving changes</p>
											</th>
											<td>
												<textarea class="widefat" name="<?php echo $this->option_name; ?>[log_messages]" id="<?php echo $this->option_name; ?>[log_messages]" cols="30" rows="10"><?php echo ( isset( $this->current_options['log_messages'] ) ) ? $this->current_options['log_messages'] : ''; ?></textarea>
											</td>
										</tr>

										<tr valign="top">
											<th scope="row">Error Messages:
												<p class="description">If you want to clear all Error messages select all the messages and delete them before saving changes</p>
											</th>
											<td>
												<textarea class="widefat" name="<?php echo $this->option_name; ?>[error_messages]" id="<?php echo $this->option_name; ?>[error_messages]" cols="30" rows="10"><?php echo ( isset( $this->current_options['error_messages'] ) ) ? $this->current_options['error_messages'] : ''; ?></textarea>
											</td>
										</tr>

									</table>
									<p class="description">NOTE: Make sure you have all the settings saved first before you click subscribe</p>
									<p class="submit">
										<input type="submit" class="button-primary right" value="<?php _e('Save Changes') ?>" />

										<input class="button-secondary left tls_hub_action subscribe" type="button" name="tls_hub_action" value="Subscribe"/>
									</p>
									<div id="show_loading_update">
										<img class="alignnone" title="WordPress Loading Animation Image" src="<?php echo admin_url('/../wp-includes/js/thickbox/loadingAnimation.gif'); ?>" alt="WordPress Loading Animation Image" width="208" height="13"/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<?php

	}


	/**
	 * Settings Validation
	 *
	 * @param array $input		Settings being posted by the settings form
	 * @return array $valid		Validated settings
     */
	public function tls_hub_sub_validate($input) {
		$options = $this->get_current_options();

		//  Start empty $valid variable and do initial text field sanitization
		$valid = array();

		$valid['subscription_id'] = ( isset($input['subscription_id']) ) ? sanitize_text_field($input['subscription_id']) : '';
		$valid['subscription_status'] = (isset($input['subscription_status'])) ? sanitize_text_field($input['subscription_status']) : '';

		$valid['topic_url'] = sanitize_text_field($input['topic_url']);
		$valid['hub_url'] = sanitize_text_field($input['hub_url']);
		$valid['log_messages'] = esc_textarea($input['log_messages']);
		$valid['error_messages'] = esc_textarea($input['error_messages']);


		$valid['topic_url'] = $this->validate_url( $valid['topic_url'], 'topic_url', 'Topic URL' );
		$valid['hub_url'] = $this->validate_url( $valid['hub_url'], 'hub_url', 'Hub URL' );

		if ( empty($this->current_options['subscription_id']) && empty($valid['subscription_id']) ) {
			$random_number = substr(number_format(time() * mt_rand(),0,'',''),0,10);
			$valid['subscription_id'] = $random_number;
		} else {
			$valid['subscription_id'] = $this->current_options['subscription_id'];
		}

		if ( empty($this->current_options['subscription_status']) && empty($valid['subscription_status']) ) {
			$valid['subscription_status'] = $this->data['subscription_status'];
		} else {
			$valid['subscription_status'] = $this->current_options['subscription_status'];
		}

		return $valid;
	}

	/**
	 * Validate URL
	 *
	 * @param string $url			URL to be validate
	 * @param string $url_field		URL field name to be validated
	 * @param string $label			Label for the URL Field being validated
	 * @return string				Validated URL
	 */
	protected function validate_url($url, $url_field, $label) {
		// URL Validation Regex
		// https://mathiasbynens.be/demo/url-regex
		$urlRegEx = '_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS';

		if ( !empty($url) && !preg_match($urlRegEx, $url)) {
			add_settings_error(
				$url_field,                                        	// Setting Title
				$url_field . '_error',								// Error ID
				'Please enter a valid URL for ' . $label,           // Error Message
				'error'                                             // Type of Message
			);

			return $this->data[$url_field];
		}

		return $url;
	}

	/**
	 * Enqueue JS File for Ajax Functionality on the Settings Page
     */
	public function hub_action_javascript() {

		wp_enqueue_script('tls-hub-action-js', TLS_THEME_URI . '/inc/tls_hub_subscriber/src/js/tls-hub-action.js');
		wp_localize_script('tls-hub-action-js', 'tls_hub_action', array(
				'ajax_admin' => admin_url( 'admin-ajax.php', 'relative' )
			)
		);
		wp_enqueue_style('tls-hub-sub-styles', TLS_THEME_URI . '/inc/tls_hub_subscriber/src/css/tls-hub-sub-style.css', array(), '', 'all');
	}

	/**
	 * Ajax Callback Function for the Settings Page
     */
	public function hub_action_callback() {
	
		$tls_hub_action = wp_strip_all_tags( $_POST['tls_hub_action'] );

		$message = '';

		if ( strtolower( $tls_hub_action ) == 'subscribe' ) {
			$this->current_options['subscription_status'] = 'Subscribing';
			update_option($this->option_name, $this->current_options);
			$message = "<div id=\"message\" class=\"updated\">Your Hub Subscription is being processed. Check back later to see if you are fully subscribed<p></p></div>";
		} else if ( strtolower( $tls_hub_action ) == 'unsubscribe' ) {
			$this->current_options['subscription_status'] = 'Unsubscribing';
			update_option($this->option_name, $this->current_options);
			$message = "<div id=\"message\" class=\"updated\">Your Hub Unsubscription is being processed. Check back later to see if you are fully unsubscribed<p></p></div>";
		}

		echo $message;

		wp_die(); // this is required to terminate immediately and return a proper response
	}
	
}