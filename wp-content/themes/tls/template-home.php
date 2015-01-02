<?php
/**
 * Template Name: Home Page Template
 *
 * @package tls
 */

get_header(); ?>

<section id="home" ng-controller="home">

	<div id="banner" style="background-image:url(/wp-content/themes/tls/images/hero.jpg)">

		<div class="container">
			
			<div class="caption">
				<p class="category">Memoir</p>
				<h2>The soldier poets</h2>
				<p class="excerpt">Does poetry carry more weight than history in the legacy of the First World War?</p>
			</div>
		</div>

		<div class="gradient"></div>

	</div>
	
	<div class="container">

		<div ng-if="columns" tls-columns="columns"></div>

	</div>

	<div class="grid-row" id="subscriber" ng-class="{locked:isLocked}">

		<div class="container">
		
			<h5 class="centred-heading grid-row">Subscriber exclusive</h5>

			<div class="subscribe-grid">
				<div class="card">
					<h3 class="futura">Archive</h3>
					<img class="max" src="http://placehold.it/380x192">
					<p class="padded">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit.
					</p>
				</div>
			</div>

			<div class="subscribe-grid">				
				<div class="card">
					<h3 class="futura">Letters to the editor</h3>
					<img class="max" src="http://placehold.it/380x192">
					<p class="padded">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit.
					</p>
				</div>
			</div>

			<div class="subscribe-grid">
				<div class="card">
					<h3 class="futura">NB</h3>
					<img class="max" src="http://placehold.it/380x192">
					<p class="padded">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit.
					</p>
				</div>
			</div>

			<div class="subscribe-grid">
				<div class="card">
					<h3 class="futura">Wall street journal</h3>
					<img class="max" src="http://placehold.it/380x192">
					<p class="padded">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit.
					</p>
				</div>
			</div>

		</div>

	</div>

</section>
<?php get_footer(); ?>
