<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
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
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>"/> 
	
	<script src="//use.typekit.net/zvh7bpe.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>
	<?php wp_head(); ?>
	
</head>

<body <?php body_class(); ?>>

	<header id="main-header" ng-controller="header">

		<div id="header-top" class="grid-row" tls-window-size="size">
			
			<div class="container">
				<div id="brand">
					<h1 id="logo"><a title="TLS" href="/"><img alt="TLS" src="<?php bloginfo('template_directory'); ?>/images/logo.jpg" /></a></h1>
					<p class="sub">The times Literary supplement</p>
					<p class="strap">The leading international weekly for literary culture</p>
				</div>

				<div id="user" class="centre-y">
					{{size}}
					<button>Subcribe</button>
					<button class="clear">Login <i class="icon icon-login"></i></button>
				</div>
			</div>

		</div>

		<nav ng-class="{desktop : size == 'desktop', tablet : size == 'tablet',mobile : size == 'mobile'}">
			
			<div class="container">

				<div class="grid-row">
				
					<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>

					<div class="search" ng-click="placeholder = ''">
						<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<div class="search-wrapper">
								<label class="screen-reader-text" for="s">Search:</label>
								<input type="search" value="<?php echo get_search_query(); ?>" ng-attr-placeholder="{{placeholder}}" name="s" id="s" />
							</div>
							<!-- <input type="submit"/> -->
							<button type="submit" class="icon icon-search"></button>
						</form>
					</div>

				</div>	

			</div>

		</nav>
	</header>	
