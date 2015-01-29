<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package tls
 */

get_header(); ?>

<article id="accordian-template" class="single-post-template transition-2" ng-class="{show:ready}" ng-controller="footerpages">
	<div class="container">
		<div class="grid-row">
			<div class="article-body">
				<h1 ng-bind="page.title" class="padded"></h1>
				<p class="date" ng-bind="format(page.date);"></p>
				<div ng-bind-html="page.content"></div>
			</div>		
		</div>
	</div>
</article>

<?php get_footer(); ?>
