<?php

class PuSHSubscriberWP {

	/**
	 * [__construct Start PuSHSubscriberWP actions, hooks, etc.]
	 */
	public function __construct() {
		add_action( 'init', array($this, 'tls_add_hub_cb_rules' ));
		add_filter( 'query_vars', array($this, 'tls_add_hub_cb_query_vars' ));
		add_action( 'parse_request', array($this, 'tls_add_hub_cb_parse_request' ));
	}


	public function tls_add_hub_cb_rules() {
		add_rewrite_rule('^hubcb/([^/]*)/?','index.php?pagename=hubcb&subscription_id=$matches[1]','top');
	}


	public function tls_add_hub_cb_query_vars( $query_vars ) {
	    $query_vars[] = 'subscription_id';
	    return $query_vars;
	}


	public function tls_add_hub_cb_parse_request( &$wp ) {
		if ($_POST) {
			var_dump($wp->query_vars );
		}
	    if ( array_key_exists( 'subscription_id', $wp->query_vars ) ) {
	        include get_template_directory() . '/simplexml-feed.php';
	        exit();
	    }
	    return;
	}

}