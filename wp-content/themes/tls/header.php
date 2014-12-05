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

	<header id="main-header">

		<div id="header-top" class="grid-row">
			
			<div class="container">
				<div id="brand">
					<h1 id="logo">TLS</h1>
					<p class="sub">The times Literary supplement</p>
					<p class="strap">The leading international weekly for literary culture</p>
				</div>

				<div id="user" class="centre-y">
					
					<button>Subcribe</button>
					<button class="clear">Login</button>

				</div>
			</div>

		</div>

		<nav>
			
			<div class="container">

				<div class="grid-row">
				
					<ul class="futura">
						<li><a href="#">Latest Edition</a></li>
						<li><a href="#">Discover</a></li>
						<li><a href="#">Blogs</a></li>
					</ul>

					<div class="search">
						<?php get_search_form(); ?>
					</div>

				</div>	

			</div>

		</nav>
	</header>	
