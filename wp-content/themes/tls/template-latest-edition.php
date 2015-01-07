<?php get_header(); ?>

<section id="latest-edition" ng-controller="latesteditions" ng-cloak>
	
	<div class="container relative editions-top" tls-window-size="size" ng-swipe-right="chooseArticle('prev',prev)" ng-swipe-left="chooseArticle('next',next)">
		
		<div class="article-links">
			<div class="inner">
				<a href="javascript:;" ng-if="nextEdition.url" ng-click="chooseEdition('next',next)" class="article-nav next-article">
					<div class="icon icon-right-arrow"><span>{{nextEdition.title}}</span></div>
				</a>
				<a href="javascript:;" ng-if="previousEdition.url" ng-click="chooseEdition('prev',prev)" class="article-nav prev-article">
					<div class="icon icon-left-arrow"><span>{{previousEdition.title}}</span></div>
				</a>	
			</div>			
		</div>

		<div class="edition-current">

			<h1>{{latestEdition.title}}</h1>
			
			<div class="grid-row">		

				<div class="grid-4 featured-col">					
					<img class="max" ng-attr-src="{{currentEdition.featured.image_url}}">
					<div class="title-small">No. {{currentEdition.featured.issue_no}}</div>
				</div>
				
				<div class="grid-4 public-col">
					<h2>{{publicObj.title}}</h2>
					<div class="grid-row">
						<div class="col-12 edition-item" ng-repeat="public in publicObj.articles">
							<div class="padded">
								<h3 class="futura">{{public.section}}</h3>
								<p class="title-small">{{public.author}}</p>
								<h4>{{public.title}}</h4>
							</div>
						</div>
					</div>
				</div>
				
				<div class="grid-4 regular-col">					
					<h2>{{regularsObj.title}}</h2>
					<div class="grid-row">
						<div class="col-12 edition-item" ng-repeat="regular in regularsObj.articles">
							<div class="padded">
								<h3 class="futura">{{regular.section}}<i class="icon icon-key-after"></i></h3>								
								<p class="title-small">{{regular.title}}</p>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>

	<div class="editions-bottom">
		
		<div class="container relative" tls-window-size="size">
			<h2>{{subscribersObj.title}}</h2>
		</div>

	</div>


</section>

<?php get_footer(); ?>