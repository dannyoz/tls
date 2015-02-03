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
					
					<h2 class="futura">Filters</h2>

					<button class="close-filters tablet-show clear small" ng-click="showFilters = false">
						Close Filters <i class="icon icon-cross"></i>
					</button>

					<div class="filter-block">
						<h3 class="futura uppercase">
							Content type 
							<span class="clear" ng-if="filters.length > 0 || clearable" ng-click="clearFilters()">Clear <i style="font-size:14px" class="icon icon-cross"></i></span>
						</h3>
						<ul class="filters" ng-cloak>
							<li ng-if="val.search_count > 0" ng-class="{applied:val.isApplied}" ng-repeat="(name,val) in contentType">
								<a ng-click="contentFilter(val.item_label,val.json_query,name,'content')"><span ng-bind-html="val.item_label"></span> ({{val.search_count}}) <i ng-if="val.isApplied" class="icon icon-cross"></i></a>
							</li>
						</ul>
					</div>

					<div class="filter-block">
						<h3 class="futura uppercase">Date</h3>
						<ul class="filters" ng-cloak>
							<li ng-class="{applied:val.isApplied}" ng-repeat="(name,val) in dateRanges">
								<a ng-click="dateRangeFilter(val.search_term,name)"><span ng-bind-html="val.item_label"></span> ({{val.search_count}}) <i ng-if="val.isApplied" class="icon icon-cross"></i></a>
							</li>
						</ul>
					</div>

					<div class="filter-block">
						<h3 class="futura uppercase">
							Category
						</h3>
						<ul class="filters">
							<li ng-if="val.search_count > 0" ng-class="{applied:val.isApplied}" ng-repeat="(name,val) in sections">
								<a ng-click="contentFilter(val.item_label,val.json_query,name,'category')"><span ng-bind-html="val.item_label"></span> ({{val.search_count}}) <i ng-if="val.isApplied" class="icon icon-cross"></i></a>
							</li>
						</ul>
					</div>

				</div>

				<div id="results" class="transition-1" ng-class="{shift:showFilters,loading:loadResults}">

					<button id="show-filters" class="tablet-show clear small" ng-click="showFilters = true">Filters <i class="icon icon-plus"></i></button>

					<h2><span ng-bind="results.count_total"></span> results for: <?php printf( __( '%s', 'tls' ), '<span class="term">"' . get_search_query() . '"</span>' ); ?></h2>

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

					<div class="card" ng-repeat="post in results.posts" ng-class="{private:post.taxonomy_article_visibility[0].slug == 'private'}">
						<h3 class="futura date">
							<a ng-attr-href="/article-section/{{post.taxonomy_article_section[0].slug}}" ng-if="post.taxonomy_article_section" ng-bind-html="post.taxonomy_article_section[0].title"></a>
							<a ng-attr-href="{{post.url}}" ng-if="!post.taxonomy_article_section" ng-bind-html="post.categories[0].title"></a>
						</h3>
						<span ng-if="post.modified" class="post-date">{{niceDate.format(post.date)}} 
							<i class="icon icon-key" ng-if="post.taxonomy_article_visibility[0].slug == 'private'"></i>
						</span>
						<div class="padded">
							<h4><a ng-attr-href="{{post.url}}" ng-bind-html="post.title"></a></h4>
							<div ng-bind-html="post.excerpt"></div>
						</div>

						<footer ng-if="post.books">
							<div class="book summary-wrapper" ng-repeat="book in post.books">
								<p class="book-title futura" ng-bind-html="book.book_title"></p>
								<p class="futura" ng-bind-html="book.book_author"></p>
							</div>
						</footer>
					</div>

					<div ng-if="results.pages" tls-pagination="paginationConfig"></div>

				</div>

				<div tls-loading="loadResults"></div>

			</div>

		</div>

	</section>

<?php get_footer(); ?>
