<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package tls
 */
?>


<footer id="site-footer">
	<div class="container">
		
		<ul>
		<?php 
		   /**
			* Displays a navigation menu
			* @param array Arguments
			*/
			wp_nav_menu( array( 
				'theme_location' => 'footer', // Menu theme location
				'container' => '',
				'items_wrap' => '%3$s' // The wrapping of the <li> tags. Since the <ul> is built into the theme use only %3$s which should print out only the links
			) );
		?>
		
		<?php
			/**
			 * Social Media Links
			 * Check if the Social Media links in Theme Options have been 
			 * filled in and then display them
			 * @var string
			 */
			$social_media_options = get_option( 'social_media_options' );
			if ( $social_media_options ) {

				$facebook_url = $social_media_options['facebook_url'];
				$twitter_url = $social_media_options['twitter_url'];
			
				if ( $facebook_url != '' || $facebook_url != null ) {
					echo '<li><a href="' . $facebook_url . '">Facebook</a></li>';
				}

				if ( $twitter_url != '' || $twitter_url != null ) {
					echo '<li><a href="' . $twitter_url . '">Twitter</a></li>';
				}
			
			}
		?>

		</ul>

		<p class="copyright">
			Copyright &copy; The Times Literary Supplement Limited <?php echo date('Y') ?>. The Times Literary Supplement Limited: 1 London Bridge Street, London SE1 9GF. Registered in England. <br />
			Company registration number: 935240. VAT no: GB 243 8054 69.
		</p>

	</div>
</footer>


<?php wp_footer(); ?>

<!--<script src="//localhost:35729/livereload.js"></script>-->
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/main.min.js"></script>

</body>
</html>
