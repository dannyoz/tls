<?php get_header(); ?>

<section id="latest-edition" ng-controller="latesteditions">
	
	<div class="container relative" tls-window-size="size" ng-swipe-right="chooseArticle('prev',prev)" ng-swipe-left="chooseArticle('next',next)">
		
		<div class="article-links">
			<div class="inner">
				<a href="javascript:;" ng-click="chooseArticle('next',next)" class="article-nav next-article">
					<div class="icon icon-right-arrow"><span>{{nextEdition.title}}</span></div>
				</a>
				<a href="javascript:;" ng-click="chooseArticle('prev',prev)" class="article-nav prev-article">
					<div class="icon icon-left-arrow"><span>{{previousEdition.title}}</span></div>
				</a>	
			</div>			
		</div>

	</div>

</section>

<?php get_footer(); ?>