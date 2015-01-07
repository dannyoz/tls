<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package tls
 */

get_header(); ?>


<section id="category" ng-controller="category" ng-class="{loading:loading}">

	<div id="banner" ng-attr-style="background-image:url({{firstPost.thumbnail_images.full.url}})">

		<div class="container">
				
			<div class="caption">
				<a ng-href="{{firstPost.url}}">
					<h2>{{firstPost.title}}</h2>
					<div class="excerpt" ng-bind-html="firstPost.excerpt"></div>
				</a>
			</div>
		</div>

		<div class="gradient"></div>

	</div>

	<div tls-scroll="scrollState">

		<div class="container" tls-window-size="size" ng-if="ready">

			<div class="grid-row" ng-if="size == 'desktop'">
				
				<div  class="grid-4" ng-repeat="column in col3">
					
					<div class="card" ng-repeat="card in column">

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
								<p class="author futura"><a ng-attr-href="/author/{{card.author.slug}}">{{card.author.name}}</a></p>
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

			<div class="grid-row" ng-if="size == 'tablet'">

				<div class="grid-6" ng-repeat="column in col2">
					
					<div class="card" ng-repeat="card in column">

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
								<h4><a href="#">{{card.title}}</a></h4>
								<p class="author futura"><a href="#">{{card.author.name}}</a></p>
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

			<div class="grid-row" ng-if="size == 'mobile'">
				
				<div  class="grid-12" ng-repeat="column in col1">
					
					<div class="card" ng-repeat="card in column">

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
								<p class="author futura"><a ng-attr-href="/author/{{card.author.slug}}">{{card.author.name}}</a></p>
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

			<div id="load-more" class="grid-row">

				<p class="centred futura">{{loadMsg}}</p>
				<div tls-loading="infLoading"></div>
				<button class="clear centre" ng-if="!infinite && pageCount > 1" ng-click="loadMore();">
					Load more <i class="icon icon-plus"></i>
				</button>
			</div>
			
		</div>

		<div tls-loading="loading"></div>

	</div>
	
</section>


<?php get_footer(); ?>
