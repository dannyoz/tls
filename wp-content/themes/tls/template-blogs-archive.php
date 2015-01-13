<?php
/**
 * Template Name: Blogs Archive Template
 *
 * @package tls
 */

get_header(); ?>


<section id="category" ng-controller="category" ng-class="{loading:loading}">

	<div id="banner" ng-if="firstPost" ng-attr-style="background-image:url({{firstPost.images.full[0]}})">

		<div class="container">
				
			<div class="caption">
				<a ng-href="{{firstPost.link}}">
					<h2>{{firstPost.title}}</h2>
					<p class="excerpt">{{firstPost.excerpt}}</p>
				</a>
			</div>
		</div>

		<div class="gradient"></div>

	</div>

	<div tls-scroll="scrollState">

		<div class="container" ng-if="ready">

			<div class="grid-row" ng-if="size == 'desktop'">
				
				<div  class="grid-4" ng-repeat="column in col3">
					
					<div class="card" ng-repeat="card in column">

						<h3 class="futura">
							<a ng-attr-href="{{card.category_url}}">{{card.categories[0].title}}</a>
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
							<a ng-attr-href="{{card.category_url}}">{{card.categories[0].title}}</a>
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
							<a ng-attr-href="{{card.category_url}}">{{card.categories[0].title}}</a>
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
