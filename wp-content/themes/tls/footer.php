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
			 */
			$theme_options_settings = get_option( 'theme_options_settings' );
			if ( $theme_options_settings ) {

				$facebook_url = $theme_options_settings['facebook_url'];
				$twitter_url = $theme_options_settings['twitter_url'];
			
				if ( $facebook_url != '' || $facebook_url != null ) { ?>
					<li><a href="<?php echo $facebook_url; ?>"><i class="icon icon-facebook"></i></a></li>
				<?php }

				if ( $twitter_url != '' || $twitter_url != null ) { ?>
					<li><a href="<?php echo $twitter_url ?>"><i class="icon icon-twitter"></i></a></li>
				<?php }
			
			}
		?>

		</ul>

		<p class="copyright">
			Copyright &copy; The Times Literary Supplement Limited <?php echo date('Y') ?>. The Times Literary Supplement Limited: 1 London Bridge Street, London SE1 9GF. Registered in England. <br />
			Company registration number: 935240. VAT no: GB 243 8054 69.
		</p>

	</div>
</footer>




<script src="//localhost:35729/livereload.js"></script>
<?php wp_footer(); ?>

</body>
</html>
