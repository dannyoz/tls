<article class="single-post" ng-controller="article" ng-cloak>

	<div ng-if="!loadingPg">

		<div class="container relative" ng-swipe-right="chooseArticle('next',prev)" ng-swipe-left="chooseArticle('prev',next)">

			<div class="article-current">

				<div ng-if="post" class="col-wide share-bar">

					<a href="javascript:if(window.print)window.print()" class="button clear small">
						<span ng-if="size == 'desktop'">Print</span> 
						<i class="icon icon-print"></i>
					</a>
					<a ng-attr-href="{{emailLink();}}" ng-click="tealium.socialLink('email')" class="button clear small">
						<span ng-if="size == 'desktop'">Email</span> 
						<i class="icon icon-email"></i>
					</a>
					<a ng-click="socialLink(post.url,'facebook')" class="button clear small">
						<i class="icon icon-facebook"></i>
					</a>
					<a ng-click="socialLink(post.url,'twitter')" class="button clear small">
						<i class="icon icon-twitter"></i>
					</a>

					<p class="commentlink futura"><a href="#comments">Comments (<span ng-bind="post.comment_count"></span>)</a></p>

				</div>

			</div>

			<div class="article-links">
				<div class="inner">
					<a href="javascript:;" ng-if="prev" ng-click="chooseArticle('next',prev)" class="article-nav prev-article">
						<div class="icon icon-left-arrow">
							<span ng-bind-html="post.previous_post_info.title"></span>
						</div>
					</a>
					<a href="javascript:;" ng-if="next" ng-click="chooseArticle('prev',next)" class="article-nav next-article">
						<div class="icon icon-right-arrow">
							<span ng-bind-html="post.next_post_info.title"></span>
						</div>
					</a>					
				</div>			
			</div>

			<div class="article-current">

				<div class="grid-row article-top">
					<div class="col-wide article-section title-small">
						<div class="grid-6" ng-bind-html="post.taxonomy_article_section[0].title"></div>
						<div class="grid-6" ng-if="!post.taxonomy_article_section" ng-bind="post.categories[0].title"></div>
						<div class="grid-6 article-date title-small right" ng-bind="format(post.modified)"></div>
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
							<div class="grid-12" ng-if="post.custom_fields.article_author_name[0].length > 0"><h4 class="author" ng-bind="post.custom_fields.article_author_name[0]"></h4></div>
							<div class="grid-12" ng-if="post.custom_fields.article_author_name[0].length == 0"><h4 class="author" ng-bind="post.author.name"></h4></div>
						</div>

						<div class="grid-12 article-summary folded-corner" ng-if="post.custom_fields.teaser_summary[0]">
							<div class="inner" ng-bind-html="post.custom_fields.teaser_summary[0]"></div>						
						</div>

						<div ng-if="post.custom_fields.soundcloud_embed_code[0].length > -1">
							<div ng-bind-html="sce.trustAsHtml(post.custom_fields.soundcloud_embed_code[0])"></div>
						</div>
						
						<div ng-bind-html="post.content" class="body-copy"></div>

						<div ng-if="post" class="share-bar">

							<a href="javascript:if(window.print)window.print()" class="button clear small">
								Print 
								<i class="icon icon-print"></i>
							</a>
							<a ng-attr-href="{{emailLink();}}"  ng-click="tealium.socialLink('email')" class="button clear small">
								Email 
								<i class="icon icon-email"></i>
							</a>
							<a ng-click="socialLink(post.url,'facebook')" class="button clear small">
								<i class="icon icon-facebook"></i>
							</a>
							<a ng-click="socialLink(post.url,'twitter')" class="button clear small">
								<i class="icon icon-twitter"></i>
							</a>

						</div>	

					</div>
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

				<div class="grid-row article-top">
					<div class="col-wide article-section title-small">
						<div class="grid-6" ng-bind-html="oldPost.taxonomy_article_section[0].title"></div>
						<div class="grid-6 article-date title-small right" ng-bind="format(oldPost.modified)"></div>
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
							<div class="grid-12" ng-if="oldPost.custom_fields.article_author_name[0].length > 0"><h4 class="author" ng-bind="post.custom_fields.article_author_name[0]"></h4></div>
							<div class="grid-12" ng-if="oldPost.custom_fields.article_author_name[0].length == 0"><h4 class="author" ng-bind="post.author.name"></h4></div>
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

				<div class="grid-row" ng-if="size == 'desktop'">

					<div class="grid-4" ng-repeat="column in col3">
				
						<div class="card" ng-repeat="relPost in column">
							
							<h3 class="futura">
								<a ng-attr-href="{{relPost.section.link}}" ng-if="relPost.taxonomy_article_section" ng-bind-html="relPost.taxonomy_article_section[0].title"></a>			
								<i ng-if="relPost.visibility == 'private'" class="icon icon-key"></i>
							</h3>
							
							<img class="max" ng-if="relPost.custom_fields.thumbnail_image_url" ng-attr-src="{{relPost.custom_fields.thumbnail_image_url[0]}}" />						
							
							<div class="padded">
								<h4><a ng-if="relPost.url" ng-attr-href="{{relPost.url}}" ng-bind-html="relPost.title"></a></h4>		
								<p ng-bind-html="relPost.excerpt"></p>
							</div>

							<footer>
								<p ng-if="relPost.author" class="futura" ng-bind="relPost.author.name"></p>	
							</footer>

						</div>

					</div>

				</div>

				<div class="grid-row" ng-if="size == 'tablet'">

					<div class="grid-6" ng-repeat="column in col2">
				
						<div class="card" ng-repeat="relPost in column">
							
							<h3 class="futura">
								<a ng-attr-href="{{relPost.section.link}}" ng-if="relPost.taxonomy_article_section" ng-bind-html="relPost.taxonomy_article_section[0].title"></a>			
								<i ng-if="relPost.visibility == 'private'" class="icon icon-key"></i>
							</h3>
							
							<img class="max" ng-if="relPost.custom_fields.thumbnail_image_url" ng-attr-src="{{relPost.custom_fields.thumbnail_image_url[0]}}" />						
							
							<div class="padded">
								<h4><a ng-if="relPost.url" ng-attr-href="{{relPost.url}}" ng-bind-html="relPost.title"></a></h4>		
								<p ng-bind-html="relPost.excerpt"></p>
							</div>

							<footer>
								<p ng-if="relPost.author" class="futura" ng-bind="relPost.author.name"></p>	
							</footer>

						</div>

					</div>

				</div>

				<div class="grid-row" ng-if="size == 'mobile'">

					<div class="grid-4" ng-repeat="column in col3">
				
						<div class="card" ng-repeat="relPost in column">
							
							<h3 class="futura">
								<a ng-attr-href="{{relPost.section.link}}" ng-if="relPost.taxonomy_article_section" ng-bind-html="relPost.taxonomy_article_section[0].title"></a>			
								<i ng-if="relPost.visibility == 'private'" class="icon icon-key"></i>
							</h3>
							
							<img class="max" ng-if="relPost.custom_fields.thumbnail_image_url" ng-attr-src="{{relPost.custom_fields.thumbnail_image_url[0]}}" />						
							
							<div class="padded">
								<h4><a ng-if="relPost.url" ng-attr-href="{{relPost.url}}" ng-bind-html="relPost.title"></a></h4>		
								<p ng-bind-html="relPost.excerpt"></p>
							</div>

							<footer>
								<p ng-if="relPost.author" class="futura" ng-bind="relPost.author.name"></p>	
							</footer>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</article>



