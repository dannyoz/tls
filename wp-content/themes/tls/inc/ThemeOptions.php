<?php namespace Tls;

/**
 * Class ThemeOptions
 * @package Tls
 * @author  Vitor Faiante
 */
class ThemeOptions
{

    /**
     * Theme Options Constructor
     */
    public function __construct()
    {

        // Enqueue Scripts and Styles for Thickbox Uploader and Theme Options Scripts
        add_action('admin_enqueue_scripts', array(
            $this,
            'tls_theme_options_scripts'
        ));
        // Set Up Theme Options Thickbox Uploader to change button text
        add_action('admin_init', array(
            $this,
            'tls_options_setup'
        ));

        // Add TLS Theme Options Page
        add_action('admin_menu', array(
            $this,
            'tls_theme_options_page'
        ));
        // Initialize TLS Theme Options
        add_action('admin_init', array(
            $this,
            'tls_initialise_theme_options'
        ));
    }

    /*================================*
     * Admin Scripts + Setup for Media Uploader
    /*================================*/

    /**
     * Load Scripts and Styles Needed for the Theme Options Page Uploader
     */
    function tls_theme_options_scripts()
    {
        wp_register_script('tls-upload', get_stylesheet_directory_uri() . '/js/tls-upload.js', array(
            'jquery',
            'media-upload',
            'thickbox'
        ));

        if ('appearance_page_tls_theme_options' == get_current_screen()->id) {
            wp_enqueue_script('jquery');

            wp_enqueue_script('thickbox');
            wp_enqueue_style('thickbox');

            wp_enqueue_script('media-upload');
            wp_enqueue_script('tls-upload');

        }

        wp_enqueue_media();

    }

    /**
     * Set Up Theme Options Thickbox Uploader
     */
    function tls_options_setup()
    {
        global $pagenow;

        if ('media-upload.php' == $pagenow || 'async-upload.php' == $pagenow) {
            // Now we'll replace the 'Insert into Post Button' inside Thickbox
            add_filter('gettext', array(
                $this,
                'tls_replace_thickbox_text',
                1,
                3
            ));
        }
    }

    /**
     * Use Filter gettext to change the Insert into Post button text
     *
     * @param string $translated_text Translated text. Only used to return the new text
     * @param string $text            Text to be used. This is the text we will change
     * @param string $domain          Text Domain. Not used but it is one of the arguments that needs to be there so
     *                                set it to null
     *
     * @return string $translated_text    New Changed text
     */
    function tls_replace_thickbox_text($translated_text, $text, $domain = null)
    {
        if ('Insert into Post' == $text) {
            $referer = strpos(wp_get_referer(), 'tls_theme_options');
            if ($referer != '') {
                return __('Select Classifieds PDF', 'tls');
            }
        }

        return $translated_text;
    }


    /*================================*
     * Menus
    /*================================*/

    /**
     * Add 'TLS Theme Options' as a top level menu page
     */
    function tls_theme_options_page()
    {

        add_submenu_page('themes.php',                                        // Parent page slug
            'TLS Theme Options',                                // Text displayed in the browser title bar
            'Theme Options',                                    // Text used for the menu item
            'manage_options',// Minimum required capability of users to access this menu
            'tls_theme_options',                                // Slug used to access this menu item
            array(
                $this,
                'tls_theme_options_display'
            )            // Name of the function used to display the page content
        );

    } // End of tls_theme_options_page


    /*================================*
 * Sections, Settings and Fields
/*================================*/

    /**
     * Register a new settings field in the 'General Settings' page of the WordPress
     * Dashboard
     */
    function tls_initialise_theme_options()
    {

        // Add New 'theme_options_section' Section to be rendered on the new options page
        add_settings_section('theme_options_section',// The ID used for this section in attribute tags
            'Social Media Links',                                // The title of the section rendered to the screen
            array(
                $this,
                'theme_options_section_display'
            ),    // Callback function used to render the options for this section
            'tls_theme_options'                                    // The ID (or slug) of the page on which this section is rendered
        );

        // Define Facebook URL Setting Field
        add_settings_field('facebook_url',                                        // The ID (or the name) of the field
            'Facebook URL',                                        // The text used for the label of the field
            array(
                $this,
                'tls_facebook_url_display'
            ),            // The callback function used to render the field
            'tls_theme_options',// The ID (or slug) of the page on which this field is rendered
            'theme_options_section'                                // The section to which setting is added
        );

        // Define Facebook URL Setting Field
        add_settings_field('twitter_url',                                        // The ID (or the name) of the field
            'Twitter URL',                                        // The text used for the label of the field
            array(
                $this,
                'tls_twitter_url_display'
            ),            // The callback function used to render the field
            'tls_theme_options',// The ID (or slug) of the page on which this field is rendered
            'theme_options_section'                                // The section to which setting is added
        );

        // Define Facebook URL Setting Field
        add_settings_field('classifieds_pdf',                                    // The ID (or the name) of the field
            'Classifieds PDF File',                                // The text used for the label of the field
            array(
                $this,
                'tls_classifieds_pdf_display'
            ),        // The callback function used to render the field
            'tls_theme_options',// The ID (or slug) of the page on which this field is rendered
            'theme_options_section'                                // The section to which setting is added
        );

        // Define Facebook URL Setting Field
        add_settings_field('tls_tealium',                                        // The ID (or the name) of the field
            'Tealium Environment Variable',                        // The text used for the label of the field
            array(
                $this,
                'tls_tealium_display'
            ),                // The callback function used to render the field
            'tls_theme_options',// The ID (or slug) of the page on which this field is rendered
            'theme_options_section'                                // The section to which setting is added
        );

        // Define Facebook URL Setting Field
        add_settings_field('tls_subscribe_link',      // The ID (or the name) of the field
            'Subscribe Link URL',                        // The text used for the label of the field
            array(
                $this,
                'tls_subscribe_link_display'
            ),                // The callback function used to render the field
            'tls_theme_options',// The ID (or slug) of the page on which this field is rendered
            'theme_options_section'                                // The section to which setting is added
        );

        // Define Facebook URL Setting Field
        add_settings_field('tls_login_link',      // The ID (or the name) of the field
            'Login Link URL',       // The text used for the label of the field
            array(
                $this,
                'tls_login_link_display'
            ),                // The callback function used to render the field
            'tls_theme_options',// The ID (or slug) of the page on which this field is rendered
            'theme_options_section'                                // The section to which setting is added
        );

        // Define Facebook URL Setting Field
        add_settings_field('tls_logout_link',      // The ID (or the name) of the field
            'Logout Link URL',       // The text used for the label of the field
            array(
                $this,
                'tls_logout_link_display'
            ),                // The callback function used to render the field
            'tls_theme_options',// The ID (or slug) of the page on which this field is rendered
            'theme_options_section'                                // The section to which setting is added
        );

        // Define Facebook URL Setting Field
        add_settings_field('tls_subscribe_text',                                        // The ID (or the name) of the field
            'Subscribe Text',                        // The text used for the label of the field
            array(
                $this,
                'tls_subscribe_text_display'
            ),                // The callback function used to render the field
            'tls_theme_options',// The ID (or slug) of the page on which this field is rendered
            'theme_options_section'                                // The section to which setting is added
        );

        // Register the 'facebook_url' setting with the 'General' section
        register_setting('theme_options_section',// Name of section to which setting is registered to
            'theme_options_settings',                            // Name of the field setting,
            array(
                $this,
                'tls_sanitise_theme_options'
            )        // Sanitise callback function
        );

    } // End of tls_initialise_theme_options


    /*================================*
     * Callback Functions
    /*================================*/

    /**
     * Render TLS Theme Options Page
     */
    function tls_theme_options_display()
    {
        ?>

        <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h2>TLS Theme Options</h2>

            <!-- run the settings_errors() function here. -->
            <?php settings_errors(); ?>

            <form method="post" action="options.php" enctype="multipart/form-data">
                <?php

                // Render the settings for the settings identified as 'Social Media Links'. Parameter is the id for the settings group used in the register_setting
                settings_fields('theme_options_section');

                // Render all of the settings for the 'tls_theme_options' section page. Parameter should be the id of the page used in add_settings_section
                do_settings_sections('tls_theme_options');

                // Add Submit button to save and serialise the options
                submit_button();
                ?>
            </form>
        </div>

    <?php
    } // End of tls_theme_options_display

    /**
     * Render Social Media Options Section
     */
    function theme_options_section_display()
    {
        echo "Your Social media links for people to follow you.";
    }

    /**
     * Render the input field for the 'Facebook URL' setting in the 'General Settings' section
     */
    function tls_facebook_url_display()
    {

        $options = (array)get_option('theme_options_settings');
        $facebook_url = (isset($options['facebook_url'])) ? $options['facebook_url'] : '';

        echo '<input type="text" name="theme_options_settings[facebook_url]" id="theme_options_settings_facebook_url" value="' . $facebook_url . '">';
    } // End of tls_facebook_url_display

    /**
     * Render the input field for the 'Twitter URL' setting in the 'General Settings' section
     */
    function tls_twitter_url_display()
    {

        $options = (array)get_option('theme_options_settings');
        $twitter_url = (isset($options['twitter_url'])) ? $options['twitter_url'] : '';

        echo '<input type="text" name="theme_options_settings[twitter_url]" id="theme_options_settings_twitter_url" value="' . $twitter_url . '">';
    } // End of tls_twitter_url_display

    /**
     * Render the input field for the 'Classifieds PDF File' setting in the 'General Settings' section
     */
    function tls_classifieds_pdf_display()
    {

        $options = (array)get_option('theme_options_settings');
        $classifieds_pdf = (isset($options['classifieds_pdf'])) ? $options['classifieds_pdf'] : '';
        ?>
        <input type="text" id="upload_classifieds" name="theme_options_settings[classifieds_pdf]"
               value="<?php echo esc_url($classifieds_pdf); ?>"/>
        <input id="upload_classifieds_button" type="button" class="button"
               value="<?php _e('Upload Classifieds', 'tls'); ?>"/>
        <span class="description"><?php _e('Upload a PDF File for the Classifieds.', 'tls'); ?></span>
    <?php } // End of tls_classifieds_pdf_display

    /**
     * Render the input field for the 'Tealium Environment Variable' setting in the 'General Settings' section
     */
    function tls_tealium_display()
    {

        $options = (array)get_option('theme_options_settings');
        $tls_tealium = (isset($options['tls_tealium'])) ? $options['tls_tealium'] : '';

        $environment_options = array(
            'dev' => 'Development',
            'qa' => 'Testing/QA',
            'prod' => 'Production'
        );
        ?>

        <select name="theme_options_settings[tls_tealium]" id="theme_options_settings_tls_tealium">
            <option value=""> -- Select A Tealium Environment --</option>
            <?php foreach ($environment_options as $environment_key => $environment_value) : ?>
                <option
                    value="<?php echo $environment_key; ?>" <?php echo ($tls_tealium == $environment_key) ? 'selected' : ''; ?>><?php echo $environment_value; ?></option>
            <?php endforeach; ?>
        </select>
    <?php
    } // End of tls_tealium_display

    /**
     * Render the input field for the 'Subscribe Link URL' setting in the 'General Settings' section
     */
    function tls_subscribe_link_display()
    {

        $options = (array)get_option('theme_options_settings');
        $subscribe_link = (isset($options['subscribe_link'])) ? $options['subscribe_link'] : '';

        echo '<input type="text" name="theme_options_settings[subscribe_link]" id="theme_options_settings_subscribe_link" value="' . $subscribe_link . '">';
    } // End of tls_subscribe_link_display

    /**
     * Render the input field for the 'Login Link URL' setting in the 'General Settings' section
     */
    function tls_login_link_display()
    {

        $options = (array)get_option('theme_options_settings');
        $login_link = (isset($options['login_link'])) ? $options['login_link'] : '';

        echo '<input type="text" name="theme_options_settings[login_link]" id="theme_options_settings_login_link" value="' . $login_link . '">';
    } // End of tls_login_link_display

    /**
     * Render the input field for the 'Logout Link URL' setting in the 'General Settings' section
     */
    function tls_logout_link_display()
    {

        $options = (array)get_option('theme_options_settings');
        $logout_link = (isset($options['logout_link'])) ? $options['logout_link'] : '';

        echo '<input type="text" name="theme_options_settings[logout_link]" id="theme_options_settings_logout_link" value="' . $logout_link . '">';
    } // End of tls_logout_link_display

    /**
     * Render the input field for the 'Facebook URL' setting in the 'General Settings' section
     */
    function tls_subscribe_text_display()
    {

        $options = (array)get_option('theme_options_settings');
        $subscribe_text = (isset($options['subscribe_text'])) ? $options['subscribe_text'] : '';

        echo '<textarea cols="30" rows="7" type="text" name="theme_options_settings[subscribe_text]" id="theme_options_settings_subscribe_text">'.$subscribe_text.'</textarea>';
    } // End of tls_subscribe_text_display

    /**
     * Sanitise all Options
     *
     * @param  array $theme_options The array of options to sanitise
     *
     * @return array    $sanitised_options    The array of sanitised options
     */
    function tls_sanitise_theme_options($theme_options)
    {

        $sanitised_options = array();

        foreach ($theme_options as $option_key => $option_value) {
            $sanitised_options[$option_key] = strip_tags(stripslashes($option_value));
        } // end foreach

        return $sanitised_options;
    } // End of tls_sanitise_theme_options


}