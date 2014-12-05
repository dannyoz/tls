<?php

include_once 'PuSHSubscriber.inc';
include_once 'pushsubscription.inc';
include_once 'pushenvironment.inc';

class PuSHSubscriberWP {

	/**
	 * [__construct Start PuSHSubscriberWP actions, hooks, etc.]
	 */
	public function __construct() {

		// init Action hook to add PuSH Feed Custom Post Type
		add_action( 'init', array( $this, 'pushfeed_add_post_type' ) );

		// add_meta_boxes Action hook to add PuSH Feed Meta Boxes
		add_action( 'add_meta_boxes', array( $this, 'pushfeed_add_meta_boxes' ) );

		// save_post Action Hook to save PuSH Feed Meta
		add_action( 'save_post', array( $this, 'pushfeed_save_post_meta' ) );

		// init Action Hook to add hub callback rewrite rules
		add_action( 'init', array( $this, 'pushfeed_hub_callback_rewrite' ) );

		// query_vars filter to add subscription_id to the query vars
		add_filter( 'query_vars', array( $this, 'pushfeed_hub_callback_query_vars' ) );

		// parse_request action hook to create the parse request for
		// hub callback page visits
		add_action( 'parse_request', array( $this, 'pushfeed_hub_callback_parser' ) );
	}

	/**
	 * Method to create PuSH Feeds Custom Post Type
	 */
	public function pushfeed_add_post_type() {

		// PuSH Feeds Labels
		$labels = array(
			'name'                => _x( 'PuSH Feeds', 'Post Type General Name', 'tls' ),
			'singular_name'       => _x( 'PuSH Feed', 'Post Type Singular Name', 'tls' ),
			'menu_name'           => __( 'PuSH Feeds', 'tls' ),
			'parent_item_colon'   => __( 'Parent PuSH Feed:', 'tls' ),
			'all_items'           => __( 'All PuSH Feeds', 'tls' ),
			'view_item'           => __( 'View PuSH Feed', 'tls' ),
			'add_new_item'        => __( 'Add New PuSH Feed', 'tls' ),
			'add_new'             => __( 'Add PuSH Feed', 'tls' ),
			'edit_item'           => __( 'Edit PuSH Feed', 'tls' ),
			'update_item'         => __( 'Update PuSH Feed', 'tls' ),
			'search_items'        => __( 'Search PuSH Feed', 'tls' ),
			'not_found'           => __( 'PuSH Feed Not found', 'tls' ),
			'not_found_in_trash'  => __( 'PuSH Feed Not found in Trash', 'tls' ),
		);
		// PuSH Feeds Post Type Rewrite Rules
		$rewrite = array(
			'slug'                => 'pushfeed',
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => false,
		);

		// PuSH Feeds Post Type Arguments
		$args = array(
			'label'               => __( 'push_feeds', 'tls' ),
			'description'         => __( 'PuSH Subcriber Feeds', 'tls' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'custom-fields'),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_icon'           => 'dashicons-rss',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'post',
		);

		// Register tls_editions Post Type
		register_post_type( 'push_sub_feeds', $args );
	}

	/**
	 * Method to add Meta Box/es to the PuSH Feeds Custom Post Type
	 */
	public function pushfeed_add_meta_boxes() {
		add_meta_box(
			'pushfeed-details-metabox', // HTML 'id' attribute of the edit screen section
			__('PuSH Feeb Subcription Details', 'tls'), // Title of the edit screen section, visible to user
			array($this, 'pushfeed_display_meta_boxes'), // Function that prints out the HTML for the edit screen section
			'push_sub_feeds', // Post Type that Metabox is attached to
			'normal', // The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side')
			'high' // The priority within the context where the boxes should show ('high', 'core', 'default' or 'low')
		);
	}

	/**
	 * Method to display the HTML elements in the Meta Box/es
	 * from PuSH Feeds Custom Post Type
	 * @return HTML
	 */
	public function pushfeed_display_meta_boxes( $post ) {
		// Define the nonce for security purposes
		wp_nonce_field( basename( __FILE__ ), 'pushfeed-nonce-field' );

		// Start the HTML string so that all other strings can be concatenated
	?>
		<input type="hidden" name="subscription_id" id="subscription_id" value="<?php echo esc_attr(get_post_meta($post->ID, 'pushfeed-subscription-id', true)); ?>">

		<label for="pushfeed-feed-url"><strong>Feed/Topic URL:</strong></label> <br>
		<small>This is the URL for the Feed you are subscribing to.</small> <br>
		<input type="text" name="pushfeed-feed-url" id="pushfeed-feed-url" class="widefat" placeholder="http://www.example.com/feed/" value="<?php echo esc_attr(get_post_meta($post->ID, 'pushfeed-feed-url', true)); ?>"> <br><br>

		<label for="pushfeed-hub-url"><strong>Hub URL (Optional)</strong></label> <br>
		<small>If any is provided it will override the url to hub found in the feed.</small> <br>
		<input type="text" name="pushfeed-hub-url" id="pushfeed-hub-url" class="widefat" placeholder="http://www.hub.com/" value="<?php echo esc_attr(get_post_meta($post->ID, 'pushfeed-hub-url', true)); ?>"> <br><br>

		<input type="submit" name="pushfeed-subscribe" id="pushfeed-subscribe" class="button-secondary" value="Subcribe">
		<input type="submit" name="pushfeed-unsubscribe" id="pushfeed-unsubscribe" class="button-secondary" value="Unsubscribe">

	<?php
	}

	/**
	 * Method to save PuSH Feed Meta
	 * @param  integer $post_id The ID of current PuSH Feed Post being saved
	 */
	public function pushfeed_save_post_meta( $post_id ) {
		
		// Bail if we're doing an auto save
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	     
	    // if our nonce isn't there, or we can't verify it, bail
    	if( !isset( $_POST['pushfeed-nonce-field'] ) || !wp_verify_nonce( $_POST['pushfeed-nonce-field'], basename( __FILE__ ) ) ) return;

		// If PuSH Feed URL is not empty then save Post meta for it
		if ( isset( $_POST['pushfeed-feed-url'] ) && 0 < count( strlen( trim( $_POST['pushfeed-feed-url'] ) ) ) ) {

			$push_feed_url = wp_strip_all_tags( $_POST['pushfeed-feed-url'] );
			update_post_meta( $post_id, 'pushfeed-feed-url', $push_feed_url );
		}

		// If PuSH Hub URL is not empty then save Post meta for it
		if ( isset( $_POST['pushfeed-hub-url'] ) && 0 < count( strlen( trim( $_POST['pushfeed-hub-url'] ) ) ) ) {

			$push_hub_url = wp_strip_all_tags( $_POST['pushfeed-hub-url'] );
			update_post_meta( $post_id, 'pushfeed-hub-url', $push_hub_url );
		}

		var_dump($_POST);

	}

	/**
	 * Method to add PuSH Feed Hub Callback Rewrite Rule
	 * @return rewrite_rule Adds a new rewrite_rule to WordPress
	 */
	public function pushfeed_hub_callback_rewrite() {
		add_rewrite_rule('^pushfeed/([^/]*)/?','index.php?pagename=pushfeed&subscription_id=$matches[1]','top');
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

	    	if ( isset( $_POST ) ) {
	    		var_dump( array( $wp->query_vars, $_POST ) );
	    		include_once 'simplexml-feed.php';
	        	exit;
	    	}

	    } elseif ( array_key_exists( 'subscription_id', $wp->query_vars ) && $wp->query_vars['subscription_id'] === 'all' ) {
	    	echo "Do full feed pull";
	    	exit;
	    }
	    return;
	}

}