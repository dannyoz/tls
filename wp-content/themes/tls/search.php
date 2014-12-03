<?php
/**
 * The template for displaying search results pages.
 *
 * @package tls
 */

get_header(); ?>


	<section id="search-results" ng-controller="search" ng-cloak>

		<div class="container">

			<div class="grid-row">

				<div id="search-filters" class="transition-1" ng-class="{open:showFilters}">
					
					<h2 class="futura">Sort by...</h2>

					<a class="close-filters tablet-show" ng-click="showFilters = false">
						close
					</a>

					<div class="filter-block">
						<h3 class="futura uppercase">Content type</h3>
						<ul class="filters">
							<li><a ng-click="filterResults('derp')">Derp</a></li>
							<li><a ng-click="filterResults('articles')">Article</a></li>
							<li><a href="">Tempora, perspiciatis.</a></li>
							<li><a href="">Porro, quidem.</a></li>
							<li><a href="">Eos, consequatur.</a></li>
						</ul>
					</div>

					<div class="filter-block">
						<h3 class="futura uppercase">Date</h3>
						<ul class="filters">
							<li><a href="">Lorem ipsum.</a></li>
							<li><a href="">Soluta, ea!</a></li>
							<li><a href="">Tempora, perspiciatis.</a></li>
							<li><a href="">Porro, quidem.</a></li>
							<li><a href="">Eos, consequatur.</a></li>
						</ul>
					</div>

					<div class="filter-block">
						<h3 class="futura uppercase">Category</h3>
						<ul class="filters">
							<li><a href="">Lorem ipsum.</a></li>
							<li><a href="">Soluta, ea!</a></li>
							<li><a href="">Tempora, perspiciatis.</a></li>
							<li class="applied"><a href="">Porro, quidem.</a></li>
							<li><a href="">Eos, consequatur.</a></li>
						</ul>
					</div>

				</div>

				<div id="search-results" class="transition-1" ng-class="{shift:showFilters}">
					
					<h2><span ng-bind="results.count_total"></span> results for: <?php printf( __( '%s', 'tls' ), '<span>' . get_search_query() . '</span>' ); ?></h2>

					<a id="show-filters" class="tablet-show" ng-click="showFilters = true">Show filters</a>

					<div ng-if="results.pages" tls-pagination="paginationConfig"></div>

					<div class="card" ng-repeat="post in results.posts">
						<h3 class="futura">
							<a ng-attr-href="{{post.url}}" ng-bind="post.title"></a>
						</h3>
						<span ng-if="post.modified" class="post-date">{{format(post.date)}}</span>
						<div class="padded" ng-bind-html="post.excerpt"></div>
					</div>

					<div ng-if="results.pages" tls-pagination="paginationConfig"></div>

				</div>
			</div>

		</div>

	</section>

<?php get_footer(); ?>
