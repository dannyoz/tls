<article class="single-post" ng-controller="article" ng-cloak>

	<div class="container relative" ng-swipe-left="chooseArticle('prev',prev)" ng-swipe-right="chooseArticle('next',next)">

		<div class="article-links">
			<a href="javascript:;" ng-if="next" ng-click="chooseArticle('next',next)" class="next-article">Next article</a>
			<a href="javascript:;" ng-if="prev" ng-click="chooseArticle('prev',prev)" class="prev-article">Previous article</a>
		</div>

		<div class="article-current">

			<div class="grid-row">
				<div class="grid-6 push-3">
					{{format(post.modified)}}
				</div>
			</div>

			<div class="grid-row">
				<div class="grid-6 push-3">
					<img class="max" ng-attr-src="{{post.thumbnail_images.full.url}}">
				</div>
			</div>

			<div class="grid-row">
				
				<div class="article-body">

					<h2>{{post.title}}</h2>
					<h4 class="author">{{post.author.name}}</h4>
					<div ng-bind-html="post.content"></div>

				</div>

			</div>

		</div>


		<div class="fold" ng-class="{turnleft:pageTurn && dir == 'prev',turnright:pageTurn && dir == 'next'}">

			<div class="grid-row">
				<div class="grid-6 push-3">
					{{format(oldPost.modified)}}
				</div>
			</div>

			<div class="grid-row">
				<div class="grid-6 push-3">
					<img class="max" ng-attr-src="{{oldPost.thumbnail_images.full.url}}">
				</div>
			</div>
				
			<div class="grid-row">
			
				<div class="article-body" ng-cloak>

					<h2>{{oldPost.title}}</h2>
					<h4 class="author">{{oldPost.author.name}}</h4>
					<div ng-bind-html="oldPost.content"></div>

				</div>

			</div>

		</div>

	</div>

	<?php
		// If comments are open or we have at least one comment, load up the comment template
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
	?>

</article>



