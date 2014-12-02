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
				<div class="grid-4">
					<h2>Sort by...</h2>

					<div class="filter-block">
						<h3>Content type</h3>
					</div>

					<div class="filter-block">
						<h3>Date</h3>
					</div>

					<div class="filter-block">
						<h3>Category</h3>
					</div>
				</div>
				<div class="grid-8">
					
					<h2>{{results.count_total}} results for: <?php printf( __( '%s', 'tls' ), '<span>' . get_search_query() . '</span>' ); ?></h2>

					<div ng-if="results.pages" tls-pagination="paginationConfig"></div>

					<div class="card" ng-repeat="post in results.posts">
						<h3 class="futura">
							<a ng-attr-href="{{post.url}}">{{post.title}}</a>
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
