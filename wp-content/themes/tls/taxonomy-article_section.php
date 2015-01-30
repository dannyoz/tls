<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package tls
 */

get_header(); ?>

<section id="discover" ng-controller="articleSection">

	<div class="container">
		<div class="grid-row">
			<div class="intro" ng-bind-html="page.content"></div>
		</div>
	</div>

	<div tls-scroll="scrollState">

		<div class="container" ng-if="ready">

			<div class="grid-row" ng-if="size == 'desktop'">

				<div  class="grid-4" ng-repeat="column in col3">

					<div ng-repeat="card in column">
						<div tls-card="card" data-copy="false"></div>
					</div>

				</div>

			</div>

			<div class="grid-row" ng-if="size == 'tablet'">

				<div class="grid-6" ng-repeat="column in col2">

					<div ng-repeat="card in column">
							<div tls-card="card" data-copy="false"></div>
						</div>

				</div>

			</div>

			<div class="grid-row" ng-if="size == 'mobile'">

				<div class="grid-6" ng-repeat="column in col1">

					<div ng-repeat="card in column">
						<div tls-card="card" data-copy="false"></div>
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
