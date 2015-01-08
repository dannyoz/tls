<?php
/**
 * Template Name: Home Page Template
 *
 * @package tls
 */

get_header(); ?>

<section id="home" ng-controller="home">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
	?>
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

		<div id="edition-preview">

			<div class="container">
				
				<div id="this-week">

					<div class="preview grid-row">

						<div class="top">
							<h3>This<br/>week's<br/>TLS</h3>
						</div>

						<div class="prevbody">

							<div class="grid-6">
								<h4 class="main">Lorem ipsum dolor sit.</h4>
								<p>
									Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam veritatis amet placeat.
								</p>
								<button>View edition</button>
							</div>

							<div class="grid-6">
								<img class="max" src="http://placehold.it/320x400">
							</div>

						</div>		

					</div>
					
				</div>

				<div id="next-week">

					<div class="preview grid-row">

						<div class="top">
							<h3>In next<br/>week's<br/>TLS</h3>
							<div class="date">
								<span><u>OUT</u></span><br/>
								<span>12th Nov 2014</span>
							</div>
						</div>

						<div class="prevbody">

							<ul>
								<li>
									<h4>Lorem ipsum.</h4>
									<h5>Lorem ipsum dolor sit amet.</h5>
								</li>
								<li>
									<h4>Adipisci, exercitationem.</h4>
									<h5>Lorem ipsum dolor sit amet.</h5>
								</li>
								<li>
									<h4>Ea, corporis.</h4>
									<h5>Lorem ipsum dolor sit amet.</h5>
								</li>
								<li>
									<h4>Animi, perspiciatis?</h4>
									<h5>Lorem ipsum dolor sit amet.</h5>
								</li>
							</ul>

						</div>

					</div>

				</div>

			</div>

		</div>
	<?php
		endwhile; // End while loop
	endif; // End if have_posts()
	?>
</section>
<?php get_footer(); ?>