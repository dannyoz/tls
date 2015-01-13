<article class="single-post" ng-controller="article" ng-cloak>

	<div class="container relative" ng-swipe-right="chooseArticle('prev',prev)" ng-swipe-left="chooseArticle('next',next)">

		<div class="article-current">

			<div ng-if="post" class="col-wide share-bar">

				<a href="javascript:if(window.print)window.print()" class="button clear small">
					Print 
					<i class="icon icon-print"></i>
				</a>
				<a ng-attr-href="{{emailLink();}}" class="button clear small">
					Email 
					<i class="icon icon-email"></i>
				</a>
				<a ng-click="socialLink(post.url,'fb')" class="button clear small">
					<i class="icon icon-facebook"></i>
				</a>
				<a ng-click="socialLink(post.url,'tw')" class="button clear small">
					<i class="icon icon-twitter"></i>
				</a>

			</div>

		</div>

		<div class="article-links">
			<div class="inner">
				<a href="javascript:;" ng-if="prev" ng-click="chooseArticle('prev',prev)" class="article-nav prev-article">
					<div class="icon icon-left-arrow"><span>{{post.previous_post_info.title}}</span></div>
				</a>
				<a href="javascript:;" ng-if="next" ng-click="chooseArticle('next',next)" class="article-nav next-article">
					<div class="icon icon-right-arrow"><span>{{post.next_post_info.title}}</span></div>
				</a>					
			</div>			
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
					<div class="article-meta grid-12">
						<div class="grid-6" ng-if="post.custom_fields.article_author_name[0].length > 0"><h4 class="author" ng-bind="post.custom_fields.article_author_name[0]"></h4></div>
						<div class="grid-6" ng-if="post.custom_fields.article_author_name[0].length == 0"><h4 class="author" ng-bind="post.author.name"></h4></div>
						<div class="grid-6 article-date title-small">{{format(post.modified)}}</div>
					</div>

					<div class="grid-12 article-summary folded-corner" ng-if="post.custom_fields.teaser_summary[0]">
						<div class="inner" ng-bind-html="post.custom_fields.teaser_summary[0]"></div>						
					</div>

					<div ng-if="post.custom_fields.embed_code[0].length > -1">
						<div ng-bind-html="sce.trustAsHtml(post.custom_fields.embed_code[0])"></div>
					</div>
					
					<div ng-bind-html="post.content"></div>

				</div>
			</div>

			<div ng-if="post" class="col-wide share-bar">

				<a href="javascript:if(window.print)window.print()" class="button clear small">
					Print 
					<i class="icon icon-print"></i>
				</a>
				<a ng-attr-href="{{emailLink();}}" class="button clear small">
					Email 
					<i class="icon icon-email"></i>
				</a>
				<a ng-click="socialLink(post.url,'fb')" class="button clear small">
					<i class="icon icon-facebook"></i>
				</a>
				<a ng-click="socialLink(post.url,'tw')" class="button clear small">
					<i class="icon icon-twitter"></i>
				</a>

			</div>		

		</div>


		<div class="fold" ng-class="{turnleft:pageTurn && dir == 'prev',turnright:pageTurn && dir == 'next'}">

			<div ng-if="oldPost" class="col-wide share-bar">

				<a href="javascript:if(window.print)window.print()" class="button clear small">
					Print 
					<i class="icon icon-print"></i>
				</a>
				<a ng-attr-href="{{emailLink();}}" class="button clear small">
					Email 
					<i class="icon icon-email"></i>
				</a>
				<a ng-click="socialLink(oldPost.url,'fb')" class="button clear small">
					<i class="icon icon-facebook"></i>
				</a>
				<a ng-click="socialLink(oldPost.url,'tw')" class="button clear small">
					<i class="icon icon-twitter"></i>
				</a>

			</div>

			<div class="grid-row">
				<div class="col-wide article-section title-small">
					{{oldPost.taxonomy_article_section[0].title}}
				</div>
			</div>

			<div class="grid-row">
				<div class="col-wide">
					<img class="max" ng-attr-src="{{oldPost.custom_fields.hero_image_url[0]}}">
				</div>
			</div>

			<div class="grid-row">
				
				<div class="article-body">

					<h2 ng-bind-html="oldPost.title"></h2>
					<div class="article-meta grid-12">
						<div class="grid-6" ng-if="oldPost.custom_fields.article_author_name[0].length > 0"><h4 class="author" ng-bind="post.custom_fields.article_author_name[0]"></h4></div>
						<div class="grid-6" ng-if="oldPost.custom_fields.article_author_name[0].length == 0"><h4 class="author" ng-bind="post.author.name"></h4></div>
						<div class="grid-6 article-date title-small">{{format(oldPost.modified)}}</div>
					</div>

					<div class="grid-12 article-summary folded-corner" ng-if="oldPost.custom_fields.teaser_summary[0]">
						<div class="inner" ng-bind-html="oldPost.custom_fields.teaser_summary[0]"></div>						
					</div>

					<div ng-if="oldPost.custom_fields.embed_code[0].length > -1">
						<div ng-bind-html="sce.trustAsHtml(oldPost.custom_fields.embed_code[0])"></div>
					</div>
					
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

	<div id="related-content" ng-if="post.taxonomy_article_tags.length > 0">

		<div class="container transition-3" ng-class="{loadingopacity:loadingTags}">

			<div class="grid-row">
				<h4 class="futura centred">Use the tags to browse related articles</h4>
				<ul id="tags" class="article-body">
					<li ng-repeat="tag in post.taxonomy_article_tags">
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



