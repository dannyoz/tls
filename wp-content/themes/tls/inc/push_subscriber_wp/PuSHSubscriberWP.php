	<?php

class PuSHSubscriberWP {

	/**
	 * [__construct Start PuSHSubscriberWP actions, hooks, etc.]
	 */
	public function __construct() {

		// init Action hook to add PuSH Feed Custom Post Type
		add_action('init', array($this, 'pushFeedAddPostType'));

		// add_meta_boxes Action hook to add PuSH Feed Meta Boxes
		add_action( 'add_meta_boxes', array($this, 'pushFeedAddMetaBoxes' ));

		// init Action Hook to add hub callback rewrite rules
		add_action( 'init', array($this, 'tlsHubCallbackRewrite' ));

		// query_vars filter to add subscription_id to the query vars
		add_filter( 'query_vars', array($this, 'tlsHubCallbackQueryVars' ));

		// parse_request action hook to create the parse request for
		// hub callback page visits
		add_action( 'parse_request', array($this, 'tlsHubCallbackParser' ));
	}

	/**
	 * Method to create PuSH Feeds Custom Post Type
	 */
	public function pushFeedAddPostType() {

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
	public function pushFeedAddMetaBoxes() {
		add_meta_box(
			'pushfeed-details-metabox', // HTML 'id' attribute of the edit screen section
			__('PuSH Feeb Subcription Details', 'tls'), // Title of the edit screen section, visible to user
			array($this, 'pushFeedDisplayMetaBoxes'), // Function that prints out the HTML for the edit screen section
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
	public function pushFeedDisplayMetaBoxes() {
		echo 'Hello Bro';
	}

	/**
	 * Method to add PuSH Feed Hub Callback Rewrite Rule
	 * @return rewrite_rule Adds a new rewrite_rule to WordPress
	 */
	public function tlsHubCallbackRewrite() {
		add_rewrite_rule('^pushfeed/([^/]*)/?','index.php?pagename=pushfeed&subscription_id=$matches[1]','top');
	}

	/**
	 * Method to add subscription_id to query_vars array
	 * @param  array $query_vars WordPress $query_vars array
	 * @return array             Returns the new $query_vars 
	 * with subscription_id added
	 */
	public function tlsHubCallbackQueryVars( $query_vars ) {
	    $query_vars[] = 'subscription_id';
	    return $query_vars;
	}

	/**
	 * Method to handle and parse request to PuSH Feed Hub Callbacks
	 * @param object Pass WordPress Object by reference
	 */
	public function tlsHubCallbackParser( &$wp ) {
		if ($_POST) {
			var_dump(array($wp->query_vars, $_POST));
		}
	    if ( array_key_exists( 'subscription_id', $wp->query_vars ) ) {
	        include_once get_template_directory() . '/simplexml-feed.php';
	        exit();
	    }
	    return;
	}

}