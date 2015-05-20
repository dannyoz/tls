<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> of the theme
 *
 * @package tls
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> ng-app="tls">
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>TLS<?php if (wp_title( '', false)) { wp_title( '|', true, 'left' ); } else{ echo " | Homepage"; } ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
	<script>try{Typekit.load();}catch(e){}</script>
	<?php if ( is_home() || is_front_page() ) :
		$front_page_id = get_option('page_on_front');
		?>
		<script>
			var home_page_id = <?php echo $front_page_id; ?>;
		</script>
	<?php endif; ?>

</head>

<body <?php body_class(); ?> tls-window-size="size">

	<header id="main-header" ng-controller="header">

		<div id="cookies" class="transition-2" ng-class="{show:!hasCookie}">
			<div class="container">
				<p class="futura">By continuing to use the site, you agree to the use of cookies. You can change this and find out more by following <a href="http://www.newsprivacy.co.uk/single/" target="_blank">this link</a></p>
				<a class="accept futura" ng-click="acceptCookies();">Accept cookies</a>
			</div>
		</div>

		<div id="header-top" class="grid-row">
			
			<div class="container">
				<div id="brand">
					<h1 id="logo"><a title="TLS" href="/"><img alt="TLS" src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.jpg" /></a></h1>
					<p class="sub">The times Literary supplement</p>
					<p class="strap">The leading international weekly for literary culture</p>
				</div>

				<div id="user" class="centre-y" ng-class="{tablet:size == 'tablet'}">
                    <?php
                    $theme_options = get_option('theme_options_settings');
                    $subscribe_url = $theme_options['subscribe_link'];
                    $login_url = $theme_options['login_link'];
                    $logout_url = $theme_options['logout_link'];
                    ?>
                    <?php if (isset($_COOKIE['acs-tls'])) : ?>
                        <a href="<?php echo esc_url($logout_url); ?>"><button ng-click="logout();" class="button clear login"><i class="icon icon-login"></i> Logout</button></a>
                    <?php else : ?>
                        <a href="<?php echo esc_url($subscribe_url); ?>"><button ng-click="subscribe();" class="button subscribe">Subscribe</button></a>
                        <a href="<?php echo esc_url($login_url); ?>"><button ng-click="login();" class="button clear login"><i class="icon icon-login"></i> Login</button></a>
                    <?php endif; ?>
				</div>
			</div>

		</div>

		<nav class="transition-1" ng-class="{desktop : size == 'desktop', tablet : size == 'tablet',mobile : size == 'mobile', hinting : hint}">
			
			<div class="container">

				<div class="grid-row">
				
					<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>

					<div class="search transition-1" ng-click="searchFocus();" ng-class="[size];{open : searchOpen}">
						
						<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<div class="search-wrapper">
								<label class="screen-reader-text" for="s" ng-if="size == 'desktop' || searchOpen">Search:</label>
								<input ng-if="ready" tls-focus="searchOpen" type="search" value="<?php echo get_search_query(); ?>" ng-attr-placeholder="{{placeholder}}" ng-class="[size]" name="s" id="s" />
							</div>
							<!-- <input type="submit"/> -->
							<button type="submit" class="icon icon-search"></button>
							<div ng-click="showSearch();" ng-if="size == 'tablet' || size == 'mobile'" ng-hide="searchOpen" class="open-search"></div>
						</form>

						<div class="hint">
							<p class="futura">Search by author, title, reviewer or keyword</p>							
						</div>				

						<a class="futura close-search" ng-click="hideSearch($event);">X</a>

					</div>					

				</div>	

			</div>

		</nav>
	</header>	
