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
							<li ng-class="{applied:val.isApplied}" ng-repeat="(name,val) in contentType">
								<a ng-click="filterResults(val.slug,name)">{{val.item_label}} ({{val.search_count}})</a>
							</li>
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

				<div id="results" class="transition-1" ng-class="{shift:showFilters}">
					
					<h2><span ng-bind="results.count_total"></span> results for: <?php printf( __( '%s', 'tls' ), '<span class="term">"' . get_search_query() . '"</span>' ); ?></h2>

					<a id="show-filters" class="tablet-show" ng-click="showFilters = true">Show filters</a>

					<div class="grid-row">
						
						<div ng-if="results.pages" tls-pagination="paginationConfig"></div>

						<div id="sorter">
							<span>Sort:</span>
							<div class="selector" ng-mouseenter="showSorter = true" ng-mouseleave="showSorter = false">
								<ul>
									<li class="current" ng-bind="orderName"></li>
									<li ng-if="orderName != 'Newest' && showSorter">
										<a ng-click="orderResults('DESC','Newest')">Newest</a>
									</li>
									<li ng-if="orderName != 'Oldest' && showSorter">
										<a ng-click="orderResults('ASC','Oldest')">Oldest</a>
									</li>
								</ul>
							</div>
						</div>

					</div>

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
