<section ng-controller="article">

	<article class="single-post-template transition-2" ng-class="{show:ready}" ng-cloak>

		<div ng-if="!loadingPg">

			<div class="container relative">

				<div class="article-current">

					<div ng-if="post && size != 'mobile' && !post.akamai_teaser" class="col-wide share-bar">

						<a ng-click="printPage();" class="share-print button clear small">
							<i class="icon icon-print"></i>						
							<span ng-if="size == 'desktop'">Print</span> 							
						</a>
						<a ng-attr-href="{{emailLink();}}" ng-click="tealium.socialLink('email')" class="share-email button clear small">
							<i class="icon icon-email"></i>
							<span ng-if="size == 'desktop'">Email</span> 							
						</a>
						<a ng-click="socialLink(post.url,'facebook')" class="share-fb button clear small">
							<i class="icon icon-facebook"></i>
						</a>
						<a ng-click="socialLink(post.url,'twitter')" class="share-twitter button clear small">
							<i class="icon icon-twitter"></i>
						</a>

						<p class="commentlink futura">
							<a href="#comments">Comments (<span ng-bind="post.comment_count"></span>)</a>
						</p>

					</div>

				</div>

				<div class="article-links">
					<div class="inner">
						<a href="javascript:;" ng-if="prev" ng-click="chooseArticle('next',prev,post.previous_post_info.title)" class="article-nav prev-article">
							<div class="icon icon-left-arrow">
								<span ng-bind-html="post.previous_post_info.title"></span>
							</div>
						</a>
						<a href="javascript:;" ng-if="next" ng-click="chooseArticle('prev',next,post.next_post_info.title)" class="article-nav next-article">
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
							<div class="grid-6 article-date title-small right-align" ng-bind="format(post.modified)" ng-if="size != 'mobile'"></div>
						</div>
					</div>

					<div class="grid-row" ng-if="post.custom_fields.hero_image_url[0]">
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
								<div class="grid-12 article-date title-small" ng-bind="format(post.modified)" ng-if="size == 'mobile'"></div>
							</div>

							<div class="grid-12 article-summary folded-corner" ng-if="post.books">
								<div class="inner" ng-repeat="book in post.books">
									<div class="summary-top">
										<div class="inner-wrapper">
											<div class="book-title" ng-bind-html="book.title"></div>
											<div ng-bind-html="book.author"></div>	
										</div>									
									</div>	
									<div class="summary-bottom">
										<div class="inner-wrapper">									
											<div ng-bind-html="book.info1"></div>
											<div ng-bind-html="book.info2"></div>
											<div ng-bind-html="book.isbn"></div>					
										</div>
									</div>	
								</div>							
							</div>

							<div ng-if="post.custom_fields.soundcloud_embed_code[0].length > -1">
								<div ng-bind-html="sce.trustAsHtml(post.custom_fields.soundcloud_embed_code[0])"></div>
							</div>
							
							<div ng-bind-html="post.content" class="body-copy"></div>

							<div ng-if="post" class="share-bar">

								<a ng-click="printPage();" class="share-print button clear small">									
									<i class="icon icon-print"></i>
									<span ng-if="size == 'desktop'">Print</span> 	
								</a>
								<a ng-attr-href="{{emailLink();}}"  ng-click="tealium.socialLink('email')" class="share-email button clear small">									
									<i class="icon icon-email"></i>
									<span ng-if="size == 'desktop'">Email</span> 	
								</a>
								<a ng-click="socialLink(post.url,'facebook')" class="share-fb button clear small">
									<i class="icon icon-facebook"></i>
								</a>
								<a ng-click="socialLink(post.url,'twitter')" class="share-twitter button clear small">
									<i class="icon icon-twitter"></i>
								</a>

							</div>	

							<div id="subscribe-form-wrapper" class="grid-12" ng-if="post.akamai_teaser">

								<div class="inner">

									<div class="grid-row">

										<div class="grid-8">
											
											<div class="form-subtitle futura">To read the full article, please login</div>
											<form novalidate name="login-form" class="form">	

												<div class="form-item">
													<label for="akamaiEmail" class="label">Email address</label>
													<input name="akamaiEmail" type="email" ng-model="lg.akamaiEmail" required />													
												</div>										
												<div class="form-item">
													<label for="akamaiPassword" class="label">Password</label>
													<input name="akamaiPassword" type="password" ng-model="lg.akamaiPassword" required />													
												</div>
												<div class="form-item">												
													<input name="akamaiKeepme" type="checkbox" ng-model="lg.akamaiKeepme" />
													<label for="akamaiKeepme" class="label inline">Keep me logged in</label>	
												</div>
												<div class="form-item">
													<button ng-click="login(lg);" class="button clear login"><i class="icon icon-login"></i> Login</button>
												</div>																					

											</form>
										</div>
										<div class="grid-4 subscribe-col">
											<div class="form-subtitle futura">Not a subscriber?</div>
											<button ng-click="subscribe();" class="button subscribe">Subscribe</button>
										</div>
									</div>
											
								</div>								

							</div>

						</div>
					</div>

				</div>


				<div class="fold" ng-class="{turnleft:pageTurn && dir == 'prev',turnright:pageTurn && dir == 'next'}">

					<div ng-if="oldPost" class="col-wide share-bar">

						<a href="javascript:if(window.print)window.print()" class="share-print button clear small">
							<i class="icon icon-print"></i>
							<span ng-if="size == 'desktop'">Print</span> 
						</a>
						<a ng-attr-href="{{emailLink();}}" class="share-email button clear small">							
							<i class="icon icon-email"></i>
							<span ng-if="size == 'desktop'">Email</span> 
						</a>
						<a ng-click="socialLink(oldPost.url,'fb')" class="share-fb button clear small">
							<i class="icon icon-facebook"></i>
						</a>
						<a ng-click="socialLink(oldPost.url,'tw')" class="share-twitter button clear small">
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

	<div tls-loading="loadingPg"></div>
	
</section>


