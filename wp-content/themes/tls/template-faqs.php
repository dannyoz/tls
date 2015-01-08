<?php
/**
 * Template Name: FAQs Page Template
 *
 * @package tls
 */

get_header(); ?>


<article id="accordian-template" class="single-post" ng-controller="footerpages">
	<div class="container">
		<div class="grid-row">

			<div class="article-body">
				<h1 ng-bind="page.title"></h1>
				<p class="date" ng-bind="format(page.date);"></p>
				<div ng-bind-html="page.content"></div>
				<div ng-if="page.accordion_items.length > 0" tls-accordian="page.accordion_items"></div>
			</div>
			
		</div>
	</div>
</article>

<?php get_footer(); ?>
