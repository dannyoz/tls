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
		<div id="banner" ng-attr-style="background-image:url({{featured.images.hero_image}})">

			<div class="container">

				<div class="caption">
					<p class="category">Memoir</p>
					<h2><a ng-attr-href="{{featued.link}}" ng-bind="featured.title"></a></h2>
					<p class="excerpt"><a ng-attr-href="{{featued.link}}" ng-bind="featured.text"></a></p>
				</div>
			</div>

			<div class="gradient"></div>

		</div>

		<div class="container">

			<div class="grid-row" ng-if="size == 'desktop'">
				
				<div  class="grid-4" ng-repeat="column in col3">
					
					<div class="card" ng-repeat="card in column">

						{{card}}

						<h3 class="futura">
							<a href="/category/a-dons-life/" ng-if="card.categories[0].slug == 'a-dons-life'">A don's life</a>
							<a href="/category/listen/" ng-if="card.categories[0].slug == 'listen'">Listen</a>
							<a href="/category/tls-blogs/" ng-if="card.categories[0].slug != 'a-dons-life' && card.categories[0].slug != 'listen'">TLS blog</a>
						</h3>

						<div class="grid-row padded" ng-if="card.categories[0].slug != 'listen'">

							<div class="grid-4">
								<a href="#">
									<img class="max circular" ng-if="card.categories[0].slug == 'a-dons-life'" src="<?php bloginfo('template_directory'); ?>/images/mary.jpg"/>
									<img class="max circular" ng-if="card.categories[0].slug != 'a-dons-life'" src="<?php bloginfo('template_directory'); ?>/images/grey-logo.jpg"/>
								</a>
							</div>
							
							<div class="grid-7 push-1">
								<h4><a ng-attr-href="{{card.url}}">{{card.title}}</a></h4>
								<p class="author futura"><a ng-href="/author/{{card.author.slug}}">{{card.author.name}}</a></p>
								<div ng-bind-html="card.excerpt"></div>
							</div>

						</div>
	
						<div class="grid-row padded" ng-if="card.categories[0].slug == 'listen'">
							<div class="embed" ng-bind-html="formatEmbed(card.custom_fields.embed_code[0])"></div>
							<h4>TLS voices</h4>
							<div ng-bind-html="card.excerpt"></div>
						</div>

					</div>

				</div>

			</div>
			
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