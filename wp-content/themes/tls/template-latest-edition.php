<?php get_header(); ?>

<section id="latest-edition" ng-controller="latesteditions">
	
	<div class="container relative" tls-window-size="size" ng-swipe-right="chooseArticle('prev',prev)" ng-swipe-left="chooseArticle('next',next)">
		
		<div class="article-links">
			<div class="inner">
				<a href="javascript:;" ng-if="next" ng-click="chooseEdition('next',next)" class="article-nav next-article">
					<div class="icon icon-right-arrow"><span>{{nextEdition.title}}</span></div>
				</a>
				<a href="javascript:;" ng-if="prev" ng-click="chooseEdition('prev',prev)" class="article-nav prev-article">
					<div class="icon icon-left-arrow"><span>{{previousEdition.title}}</span></div>
				</a>	
			</div>			
		</div>

		<div class="edition-current">
			
			<div class="grid-row editions-top">
				<div class="grid-4 featured-col">					
					<img class="max" ng-attr-src="{{currentEdition.featured.image_url}}">
				</div>
				<div class="grid-4 public-col">
					
				</div>
				<div class="grid-4 regular-col">
					
				</div>
			</div>

		</div>

	</div>

</section>

<?php get_footer(); ?>