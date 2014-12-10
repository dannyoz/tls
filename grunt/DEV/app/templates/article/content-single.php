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

					<h2 ng-bind-html="post.title"></h2>
					<h4 class="author" ng-bind="post.author.name"></h4>
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

	<div id="related-content" ng-if="post.tags.length > 0">

		<div class="container">

			<div class="grid-row">
				<ul id="tags">
					<li ng-repeat="tag in post.tags">
						<a class="futura" ng-click="refineRelated(tag.title);" ng-bind="tag.title"></a>
					</li>
				</ul>
			</div>

			<div class="grid-row">
			
				<div class="card" ng-repeat="relPost in related">
					<h3 class="futura"><a ng-attr-href="{{relPost.url}}" ng-bind="relPost.title"></a></h3>
					<img class="max" ng-attr-src="{{relPost.thumbnail_images.full.url}}" />
					<div class="padded" ng-bind-html="relPost.excerpt"></div>
				</div>

			</div>

		</div>

	</div>

</article>



