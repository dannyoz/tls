<article class="single-post" ng-controller="article" ng-cloak>

	<div class="container relative" tls-window-size="size" ng-swipe-right="chooseArticle('prev',prev)" ng-swipe-left="chooseArticle('next',next)">

		<div class="article-links">
			<a href="javascript:;" ng-if="next" ng-click="chooseArticle('next',next)" class="article-nav next-article">
				<span class="icon icon-right-arrow">{{post.next_post_info.title}}</span>
			</a>
			<a href="javascript:;" ng-if="prev" ng-click="chooseArticle('prev',prev)" class="article-nav prev-article">
				<span class="icon icon-left-arrow">{{post.previous_post_info.title}}</span>
			</a>
		</div>

		<div class="article-current">

			<div class="grid-row">
				<div class="col-wide article-section title-small">
					{{post.taxonomy_article_section[0].title}}
				</div>
			</div>

			<div class="grid-row">
				<div class="col-wide">
					<img class="max" ng-attr-src="{{post.custom_fields.hero_image_url[0]}}">
				</div>
			</div>

			<div class="grid-row">
				
				<div class="article-body">

					<h2 ng-bind-html="post.title"></h2>
					<div class="grid-12">
						<div class="grid-6"><h4 class="author" ng-bind="post.author.name"></h4></div>
						<div class="grid-6 article-date title-small">{{format(post.modified)}}</div>
					</div>

					<div class="grid-12 article-summary">
						<div class="inner" ng-if="post.custom_fields.teaser_summary[0]">
							{{post.custom_fields.teaser_summary[0]}}
						</div>						
					</div>
					
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
		if ( ('tls_articles' == get_post_type() || 'post' == get_post_type() ) && ( comments_open() || get_comments_number() ) ) :
			comments_template();
		endif;
	?> 

	<div id="related-content" ng-if="post.tags.length > 0">

		<div class="container transition-3" ng-class="{loadingopacity:loadingTags}">

			<div class="grid-row">
				<h4 class="futura centred">Use the tags to browse related articles</h4>
				<ul id="tags" class="article-body">
					<li ng-repeat="tag in post.tags">
						<a ng-class="{applied:activeTags[$index].isApplied}" class="futura" ng-click="refineRelated(tag.title,$index);" ng-bind="tag.title"></a>
					</li>
				</ul>
			</div>

			<div class="grid-row" ng-if="size == 'desktop' || size == 'mobile'">

				<div class="grid-4" ng-repeat="column in col3">
			
					<div class="card" ng-repeat="relPost in column">
						<h3 class="futura"><a ng-attr-href="{{relPost.url}}" ng-bind="relPost.title"></a></h3>
						<img class="max" ng-attr-src="{{relPost.thumbnail_images.full.url}}" />
						<div class="padded" ng-bind-html="relPost.excerpt"></div>
						<footer>
							<p class="futura"><a href="#" ng-bind="relPost.author.name"></a></p>
						</footer>
					</div>

				</div>

			</div>

			<div class="grid-row" ng-if="size == 'tablet'">

				<div class="grid-6" ng-repeat="column in col2">
			
					<div class="card" ng-repeat="relPost in column">
						<h3 class="futura"><a ng-attr-href="{{relPost.url}}" ng-bind="relPost.title"></a></h3>
						<img class="max" ng-attr-src="{{relPost.thumbnail_images.full.url}}" />
						<div class="padded" ng-bind-html="relPost.excerpt"></div>
						<footer>
							<p class="futura"><a href="#" ng-bind="relPost.author.name"></a></p>
						</footer>
					</div>

				</div>

			</div>

		</div>

	</div>

</article>



