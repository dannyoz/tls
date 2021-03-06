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

	<div id="banner" ng-attr-style="background-image:url({{firstPost.images.full[0]}})">

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

				<p class="centred futura" ng-bind="loadMsg"></p>
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
