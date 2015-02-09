<?php

class FacetWP_Integration_WooCommerce
{

    function __construct() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            add_action( 'wp_footer', array( $this, 'front_scripts' ), 30 );
        }
    }


    function front_scripts() {

    }
}

new FacetWP_Integration_WooCommerce();
