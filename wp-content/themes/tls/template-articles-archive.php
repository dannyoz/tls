<?php
/**
 * Template Name: Discover Articles Archive Template
 *
 * @package tls
 */

get_header();

?>



<section id="discover" ng-controller="discover">

	<div class="container">
		<div class="grid-row">
			<div class="intro" ng-bind-html="page.content"></div>
		</div>
	</div>

	<div tls-scroll="scrollState">

		<div class="container" ng-if="ready">

			<div id="top-articles">

				<div class="grid-row" ng-if="size == 'desktop'">

					<div class="grid-4" ng-repeat="column in topCol3">

						<div ng-repeat="card in column">
							<div tls-card="card" ng-class="{spotlight:card.spotlight}" data-copy="false"></div>
						</div>		

					</div>			

				</div>

				<div class="grid-row" ng-if="size == 'tablet'">

					<div class="grid-6" ng-repeat="column in topCol2">

						<div ng-repeat="card in column">
							<div tls-card="card" ng-class="{spotlight:card.spotlight}" data-copy="false"></div>
						</div>	

					</div>
						
				</div>

				<div class="grid-row" ng-if="size == 'mobile'">

					<div class="grid-6" ng-repeat="column in topCol1">

						<div ng-repeat="card in column">
							<div tls-card="card" ng-class="{spotlight:card.spotlight}" data-copy="false"></div>
						</div>

					</div>		

				</div>

			</div>

			<div id="infinite-scroll" ng-if="infinite">

				<h5 class="centred-heading grid-row">More articles</h5>

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

			</div>

			<div id="load-more" class="grid-row" ng-if="ready">

				<p class="centred futura" ng-bind="loadMsg"></p>
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
