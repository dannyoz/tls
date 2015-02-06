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

				<div id="search-filters" class="transition-1">
					
					<h2 class="futura">Filters</h2>

					<button class="close-filters tablet-show clear small">
						Close Filters <i class="icon icon-cross"></i>
					</button>

					<div class="filter-block">
						<h3 class="futura uppercase">
							Content type
							<span class="clear">Clear <i style="font-size:14px" class="icon icon-cross"></i></span>
						</h3>
						<?php echo facetwp_display( 'facet', 'content_type' ); ?>
					</div>

					<div class="filter-block">
						<h3 class="future uppercase">Content Visibility</h3>
						<?php echo facetwp_display( 'facet', 'content_visibility' ); ?>
					</div>

					<div class="filter-block">
						<h3 class="futura uppercase">Date</h3>
						<?php echo facetwp_display( 'facet', 'dates' ); ?>
					</div>

					<div class="filter-block">
						<h3 class="futura uppercase">
							Category
						</h3>
						<?php echo facetwp_display( 'facet', 'category' ); ?>
					</div>

				</div>

				<div id="results" class="transition-1">

					<button id="show-filters" class="tablet-show clear small">Filters <i class="icon icon-plus"></i></button>

					<h2><span><?php echo facetwp_display( 'counts' ); ?></span> results for: <?php printf( __( '%s', 'tls' ), '<span class="term">"' . get_search_query() . '"</span>' ); ?></h2>

					<div class="grid-row">
						
						<div><?php echo facetwp_display( 'pager' ); ?></div>

						<div id="sorter">
							<span>Sort:</span>
							<?php echo facetwp_display('sort'); ?>
						</div>

					</div>

					<?php echo facetwp_display( 'template', 'default' ); ?>


					<div><?php echo facetwp_display( 'pager' ); ?></div>

				</div>

				<div tls-loading="loadResults"></div>

			</div>

		</div>

	</section>

<?php get_footer(); ?>
