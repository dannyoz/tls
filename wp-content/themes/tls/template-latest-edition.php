<?php get_header();

//var_dump(get_fields());
 ?>

<section id="latest-edition" ng-controller="latesteditions" ng-cloak>
	
	<div class="container relative" tls-window-size="size" ng-swipe-right="chooseArticle('prev',prev)" ng-swipe-left="chooseArticle('next',next)">
		
		<div class="article-links" ng-if="size == 'desktop'">
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

			<h1 ng-if="size == 'desktop'">{{latestEdition.title}}</h1>
			
			<div class="editions-top grid-row">		

				<div class="editions-top-left featured-col">		
					<div class="img-wrapper">
						<img class="max" ng-attr-src="{{currentEdition.featured.image_url}}">	
					</div>								
					<div class="title-small">No. {{currentEdition.featured.issue_no}}</div>
				</div>
				
				<div class="editions-top-right">
					<div class="grid-row">
						<div class="public-col">
							<h2>{{publicObj.title}}</h2>
							<div class="edition-item" ng-repeat="public in publicObj.articles">
								<div class="padded">
									<h3 class="futura"><a href="#">{{public.section}}</a></h3>
									<p class="title-small">{{public.author}}</p>
									<h4><a href="#">{{public.title}}</a></h4>
								</div>								
							</div>
						</div>

						<div class="regular-col">					
							<h2>{{regularsObj.title}}</h2>
							<div class="edition-item" ng-repeat="regular in regularsObj.articles">
								<div class="padded">
									<h3 class="futura"><a href="#">{{regular.section}}</a><i class="icon icon-key-after"></i></h3>								
									<p class="title-small"><a href="#">{{regular.title}}</a></p>
								</div>
							</div>
						</div>
					</div>					
				</div>

			</div>
		</div>
	</div>

	<div class="editions-bottom">		

		<div class="container relative" tls-window-size="size">
			<div class="title-icon icon">
				<div class="icon-border icon-key"></div>
				<h2>{{subscribersObj.title}}</h2>	
			</div>			

			<div class="grid-row" ng-if="size == 'desktop'">
				
				<div  class="grid-4" ng-repeat="column in col3">					
					
					<div class="card-flat" ng-repeat="card in column">
						
						<h3 class="futura"><a href="#">{{card.section}}</a></h3>
						<div class="edition-item" ng-repeat="post in card.posts">
							<div class="padded">							
								<p class="title-small">{{post.author}}</p>
								<h4><a href="#">{{post.title}}</a></h4>
							</div>								
						</div>

					</div>
				
				</div>

			</div>
		</div>
	</div>


</section>

<?php get_footer(); ?>