<?php get_header(); ?>

	<section id="category" ng-controller="category">

		<div class="container" tls-window-size="size" ng-if="ready">

			<div class="grid-row" ng-if="size == 'desktop' || size == 'mobile'">
				
				<div  class="grid-4" ng-repeat="column in col3">
					
					<div class="card" ng-repeat="card in column">
						<h3 class="futura"><a ng-attr-href="{{card.url}}" ng-bind="card.title"></a></h3>
						<img class="max" ng-if="card.thumbnail_images" ng-attr-src="{{card.thumbnail_images.large.url}}" />
						<div class="padded" ng-bind-html="card.excerpt"></div>
						<footer>
							<p class="futura"><a href="#" ng-bind="card.author.name"></a></p>
						</footer>
					</div>

				</div>

			</div>

			<div class="grid-row" ng-if="size == 'tablet'">

				<div class="grid-6" ng-repeat="column in col2">
					
					<div class="card" ng-repeat="card in column">
						<h3 class="futura"><a ng-attr-href="{{card.url}}" ng-bind="card.title"></a></h3>
						<img class="max" ng-if="card.thumbnail_images" ng-attr-src="{{card.thumbnail_images.large.url}}" />
						<div class="padded" ng-bind-html="card.excerpt"></div>
						<footer>
							<p class="futura"><a href="#" ng-bind="card.author.name"></a></p>
						</footer>
					</div>

				</div>

			</div>

			<div class="grid-row" ng-click="loadMore();">
				<button class="clear">Load more</button>
			</div>
			
		</div>

	</section>

<?php get_footer();?>
