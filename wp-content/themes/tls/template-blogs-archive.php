<?php
/**
 * Template Name: Blogs Archive Template
 *
 * @package tls
 */

get_header(); ?>


<section id="category" ng-controller="category" ng-class="{loading:loading}">

	<div id="banner" ng-if="firstPost" ng-attr-style="background-image:url({{firstPost.hero_image}})">

		<div class="container">
				
			<div class="caption">
				<a ng-href="{{firstPost.link}}">
					<h2 ng-bind-html="firstPost.title"></h2>
					<p class="excerpt" ng-bind-html="firstPost.excerpt"></p>
				</a>
			</div>
		</div>

		<div class="gradient"></div>

	</div>

	<div tls-scroll="scrollState">

		<div class="container" ng-if="ready">

			<div class="grid-row" ng-if="size == 'desktop'">

				<div  class="grid-4" ng-repeat="column in col3">

					<div ng-repeat="card in column">
						<div tls-card="card" data-type="blog"></div>
					</div>

				</div>

			</div>

			<div class="grid-row" ng-if="size == 'tablet'">

				<div class="grid-6" ng-repeat="column in col2">
					
					<div ng-repeat="card in column">
						<div tls-card="card" data-type="blog"></div>
					</div>

				</div>

			</div>

			<div class="grid-row" ng-if="size == 'mobile'">
				
				<div  class="grid-12" ng-repeat="column in col1">
					
					<div ng-repeat="card in column">
						<div tls-card="card" data-type="blog"></div>
					</div>

				</div>

			</div>

			<div id="load-more" class="grid-row" ng-if="ready">

				<p class="centred futura">{{loadMsg}}</p>
				<div tls-loading="infLoading"></div>
				<button class="clear centre" ng-if="!infinite && pageCount > 1" ng-click="loadMore();tealium.engagement('load more');">
					Load more <i class="icon icon-plus"></i>
				</button>
			</div>
			
		</div>

		<div tls-loading="loading"></div>

	</div>
	
</section>


<?php get_footer(); ?>
