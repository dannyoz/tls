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

				<div id="" class="transition-1">
				
					<h2 class="futura">Vitor Sort by</h2>

					<a class="close-filters tablet-show">
						close
					</a>

					<div class="filter-block">
						<h3 class="futura uppercase">Categories</h3>
						<ul class="filters">
							<?php
								$s = get_search_query();
								$blogs = new WP_Query( "post_type=post&post_status=publish&posts_per_page=-1&s={$s}" );
								echo '<li>Blogs (' . $blogs->post_count . ')</li>'; wp_reset_postdata();


								$articles = new WP_Query( "post_type=tls_articles&post_status=publish&posts_per_page=-1&s={$s}" );
								echo '<li>Articles (' . $articles->post_count . ')</li>'; wp_reset_postdata();

								$args = array(
									'post_type'	=> array('post', 'tls_articles'),
									'post_status' => 'publish',
									's'			=> $s
								);
								$search = new WP_Query($args);
								$categories = array();

								while ($search->have_posts() ) {
									$search->the_post();
									$catTerms = wp_get_object_terms( get_the_ID() ,'category' );
									foreach ($catTerms as $catTerm) {
										array_push($categories, $catTerm->slug);
									}
								}
								$categoryCount = array_count_values($categories);
								foreach ($categoryCount as $key => $value) {
									$json_categories['blog_categories'][] = array(
											'slug' => $key,
											'count' => $value
										);
								}
								var_dump($json_categories);
							?>
						</ul>
					</div>

				</div>

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
