<?php

class FacetWP_Display
{

    /* (array) Facet types being used on the current page */
    public $active_types = array();

    /* (array) An array of preloaded data (template, facets, etc) */
    public $preload_data = array();

    /* (boolean) Whether to enable FacetWP for the current page */
    public $load_assets = false;


    function __construct() {
        add_filter( 'widget_text', 'do_shortcode' );
        add_action( 'wp_footer', array( $this, 'front_scripts' ), 25 );
        add_shortcode( 'facetwp', array( $this, 'shortcode' ) );
    }


    /**
     * Register shortcodes
     */
    function shortcode( $atts ) {
        $output = '';
        if ( isset( $atts['facet'] ) ) {
            foreach ( FWP()->helper->get_facets() as $facet ) {
                if ( $atts['facet'] == $facet['name'] ) {
                    $operator = isset( $facet['operator'] ) ? $facet['operator'] : '';
                    $output = '<div class="facetwp-facet facetwp-facet-' . $facet['name'] . ' facetwp-type-' . $facet['type'] . '" data-name="' . $facet['name'] . '" data-type="' . $facet['type'] . '"></div>';

                    // Build list of active facet types
                    if ( ! in_array( $facet['type'], $this->active_types ) ) {
                        $this->active_types[] = $facet['type'];
                    }

                    $this->load_assets = true;
                }
            }
        }
        elseif ( isset( $atts['template'] ) ) {
            foreach ( FWP()->helper->get_templates() as $template ) {
                if ( $atts['template'] == $template['name'] ) {
                    global $wp_query;

                    // Preload the template (search engine visible)
                    $temp_query = $wp_query;
                    $this->preload_data = $this->get_preload_data( $template['name'] );
                    $wp_query = $temp_query;

                    $output = '<div class="facetwp-template" data-name="' . $atts['template'] . '">';
                    $output .= $this->preload_data['template'];
                    $output .= '</div>';

                    $this->load_assets = true;
                }
            }
        }
        elseif ( isset( $atts['sort'] ) ) {
            $output = '<div class="facetwp-sort"></div>';
        }
        elseif ( isset( $atts['selections'] ) ) {
            $output = '<div class="facetwp-selections"></div>';
        }
        elseif ( isset( $atts['counts'] ) ) {
            $output = '<div class="facetwp-counts"></div>';
        }
        elseif ( isset( $atts['pager'] ) ) {
            $output = '<div class="facetwp-pager"></div>';
        }

        return $output;
    }


    /**
     * Output any necessary JS parameters
     */
    function ajaxurl() {

        $http_params = json_encode( array(
            'get' => $_GET,
            'uri' => FWP()->helper->get_uri(),
        ) );

        $url = admin_url( 'admin-ajax.php' );
        $permalink_type = FWP()->helper->get_setting( 'permalink_type', 'hash' );

        echo "<script>\n";
        echo "var ajaxurl = '$url';\n";
        echo "var FWP_HTTP = $http_params;\n";
        echo "FWP.permalink_type = '$permalink_type';\n";
        echo "</script>\n";
    }


    /**
     * Output facet scripts
     */
    function front_scripts() {

        // Not enqueued - front.js needs to load before front_scripts()
        if ( true === apply_filters( 'facetwp_load_assets', $this->load_assets ) ) {
            if ( true === apply_filters( 'facetwp_load_css', true ) ) {
                echo '<link rel="stylesheet" href="' . FACETWP_URL . '/assets/css/front.css" />' . "\n";
            }

            echo '<script src="' . FACETWP_URL . '/assets/js/event-manager.js?ver=' . FACETWP_VERSION . '"></script>' . "\n";
            echo '<script src="' . FACETWP_URL . '/assets/js/front.js?ver=' . FACETWP_VERSION . '"></script>' . "\n";

            // Output the ajaxurl and HTTP params
            $this->ajaxurl();

            foreach ( $this->active_types as $type ) {
                FWP()->helper->facet_types[ $type ]->front_scripts();
            }
        }
    }


    /**
     * Preload the AJAX response so search engines can see it
     * @since 2.0
     */
    function get_preload_data( $template_name ) {

        $params = array(
            'facets' => array(),
            'template' => $template_name,
            'http_params' => array(
                'uri' => FWP()->helper->get_uri(),
            ),
            'extras' => array(),
            'paged' => 1,
        );

        foreach ( FWP()->ajax->url_vars as $key => $val ) {
            if ( 'paged' == $key ) {
                $params['paged'] = $val;
            }
            elseif ( 'sort' == $key ) {
                $params['extras']['sort'] = $val;
            }
            else {
                $params['facets'][] = array(
                    'facet_name' => $key,
                    'selected_values' => explode( ',', $val ),
                );
            }
        }

        return FWP()->facet->render( $params );
    }
}
