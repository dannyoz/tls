<?php
/**
 * Template Name: Articles Archive Template
 *
 * @package tls
 */

get_header();

// Articles Archive WP_Query arguments
$articles_archive_args = array(
	'post_type'		=> 'tls_articles',
	'orderby'		=> 'date',
	'order'			=> 'DESC'
);
// Articles Archive new WP_Query
$articles_archive = new WP_Query($articles_archive_args);
?>

<section id="discover" ng-controller="discover">

	<div class="container">
		<div class="grid-row">
			<div class="intro" ng-bind-html="page.content"></div>
		</div>
	</div>

	<div tls-scroll="scrollState">

			<div class="container" tls-window-size="size" ng-if="ready">

				<div class="grid-row" ng-if="size == 'desktop' || size == 'mobile'">

					<div  class="grid-4" ng-repeat="column in col3">

						<div class="card" ng-repeat="card in column" ng-class="{private:card.taxonomy_article_visibility[0].slug == 'private'}">
							<h3 class="futura">
								<a ng-attr-href="{{card.url}}" ng-bind="card.title"></a>
								<i ng-if="card.taxonomy_article_visibility[0].slug == 'private'" class="icon icon-key"></i>
							</h3>
							<img class="max" ng-if="card.custom_fields.thumbnail_image_url[0].length > 0" ng-attr-src="{{card.custom_fields.thumbnail_image_url[0]}}" />
							<div class="padded" ng-bind-html="card.excerpt"></div>
							<footer>
								<p class="futura"><a href="#" ng-bind="card.author.name"></a></p>
							</footer>
						</div>

					</div>

				</div>

				<div class="grid-row" ng-if="size == 'tablet'">

					<div class="grid-6" ng-repeat="column in col2">

						<div class="card" ng-repeat="card in column" ng-class="{private:card.taxonomy_article_visibility[0].slug == 'private'}">
							<h3 class="futura">
								<a ng-attr-href="{{card.url}}" ng-bind="card.title"></a>
								<i ng-if="card.taxonomy_article_visibility[0].slug == 'private'" class="icon icon-key"></i>
							</h3>
							<img class="max" ng-if="card.custom_fields.thumbnail_image_url[0].length > 0" ng-attr-src="{{card.custom_fields.thumbnail_image_url[0]}}" />
							<div class="padded" ng-bind-html="card.excerpt"></div>
							<footer>
								<p class="futura"><a href="#" ng-bind="card.author.name"></a></p>
							</footer>
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
