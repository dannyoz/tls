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
	<title><?php wp_title( '|', true, 'right' ); ?></title>
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

		<div id="header-top" class="grid-row">
			
			<div class="container">
				<div id="brand">
					<h1 id="logo"><a title="TLS" href="/"><img alt="TLS" src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.jpg" /></a></h1>
					<p class="sub">The times Literary supplement</p>
					<p class="strap">The leading international weekly for literary culture</p>
				</div>

				<div id="user" class="centre-y" ng-class="{tablet:size == 'tablet'}">
					<button class="button subscribe">Subcribe</button>
					<button class="button clear login"><i class="icon icon-login"></i> Login</button>
				</div>
			</div>

		</div>

		<nav ng-class="{desktop : size == 'desktop', tablet : size == 'tablet',mobile : size == 'mobile'}">
			
			<div class="container">

				<div class="grid-row">
				
					<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>

					<div class="search" ng-click="placeholder = ''; focus = true" ng-class="[size]">
						<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<div class="search-wrapper">
								<label class="screen-reader-text" for="s" ng-if="size == 'desktop'">Search:</label>
								<input type="search" value="<?php echo get_search_query(); ?>" ng-attr-placeholder="{{placeholder}}" ng-class="[size]" name="s" id="s" />
							</div>
							<!-- <input type="submit"/> -->
							<button type="submit" class="icon icon-search"></button>
						</form>
					</div>

				</div>	

			</div>

		</nav>
	</header>	
