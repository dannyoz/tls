<?php

/*================================*
 * Menus
/*================================*/

 /**
  * Add 'TLS Theme Options' as a top level menu page
  */
function tls_theme_options_page() {

	add_submenu_page(
		'themes.php',					// Parent page slug
		'TLS Theme Options', 			// Text displayed in the browser title bar
		'Theme Options', 				// Text used for the menu item
		'manage_options', 				// Minimum required capability of users to access this menu
		'tls_theme_options',			// Slug used to access this menu item
		'tls_theme_options_display'	// Name of the function used to display the page content
	);

} // End of tls_theme_options_page
add_action( 'admin_menu', 'tls_theme_options_page' );



/*================================*
 * Sections, Settings and Fields
/*================================*/

/**
 * Register a new settings field in the 'General Settings' page of the WordPress
 * Dashboard
 */
function tls_initialise_theme_options() {

	// Add New 'social_media_section' Section to be rendered on the new options page
	add_settings_section(
		'social_media_section', 	// The ID used for this section in attribute tags
		'Social Media Links',		// The title of the section rendered to the screen
		'social_media_sec_display',	// Callback function used to render the options for this section
		'tls_theme_options'			// The ID (or slug) of the page on which this section is rendered
	);

	// Define Facebook URL Setting Field
	add_settings_field(
		'facebook_url',				// The ID (or the name) of the field
		'Facebook URL',				// The text used for the label of the field
		'tls_facebook_url_display',	// The callback function used to render the field
		'tls_theme_options',		// The ID (or slug) of the page on which this field is rendered
		'social_media_section'		// The section to which setting is added
	);

	// Define Facebook URL Setting Field
	add_settings_field(
		'twitter_url',				// The ID (or the name) of the field
		'Twitter URL',				// The text used for the label of the field
		'tls_twitter_url_display',	// The callback function used to render the field
		'tls_theme_options',		// The ID (or slug) of the page on which this field is rendered
		'social_media_section'		// The section to which setting is added
	);

	// Register the 'facebook_url' setting with the 'General' section
	register_setting(
		'social_media_section', 	// Name of section to which setting is registered to
		'social_media_options', 	// Name of the field setting,
		'tls_sanitise_social_media'	// Sanitisation callback function
	);

} // End of tls_initialise_theme_options
add_action( 'admin_init', 'tls_initialise_theme_options' );



/*================================*
 * Callback Functions
/*================================*/

/**
 * Render TLS Theme Options Page
 */
function tls_theme_options_display() {
?>
	
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2>TLS Theme Options</h2>

		<!-- run the settings_errors() function here. -->
        <?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php 

				// Render the settings for the settings identified as 'Social Media Links'. Parameter is the id for the settings group used in the register_setting
				settings_fields( 'social_media_section' );

				// Render all of the settings for the 'tls_theme_options' section page. Parameter should be the id of the page used in add_settings_section
				do_settings_sections( 'tls_theme_options' );

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
function social_media_sec_display() {
	echo "Your Social media links for people to follow you.";
}

/**
 * Render the input field for the 'Facebook URL' setting in the 'General Settings' section
 */
function tls_facebook_url_display() {

	$options = (array)get_option('social_media_options');
	$facebook_url = $options['facebook_url'];

	echo '<input type="text" name="social_media_options[facebook_url]" id="social_media_options_facebook_url" value="' . $facebook_url . '">';
} // End of tls_facebook_url_display

/**
 * Render the input field for the 'Twitter URL' setting in the 'General Settings' section
 */
function tls_twitter_url_display() {

	$options = (array)get_option('social_media_options');
	$twitter_url = $options['twitter_url'];

	echo '<input type="text" name="social_media_options[twitter_url]" id="social_media_options_twitter_url" value="' . $twitter_url . '">';
} // End of tls_twitter_url_display


/**
 * Sanitise all social media links
 * @param  array 	$sm_options 		The array of options to sanitise
 * @return array 	$sanitised_options	The array of sanitised options
 */
function tls_sanitise_social_media( $sm_options ) {

	$sanitised_options = array();

	foreach ($sm_options as $option_key => $option_value) {
		$sanitised_options[ $option_key ] = strip_tags( stripslashes( $option_value ) );
	} // end foreach

	return $sanitised_options;
} // End of tls_sanitise_social_media

