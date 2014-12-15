<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package tls
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="container">

	<div class="grid-row">
		<div class="comment-wrap" ng-cloak>

			<h3 class="futura">Comments (<span ng-bind="post.comment_count"></span>)</h3>

			<div class="comment" ng-repeat="comment in post.comments">
				<div class="thumb-wrapper">
					<img class="max circular" src="http://placehold.it/85x85">
				</div>
				<h4 class="futura" ng-bind="comment.name"></h4>
				<p class="date futura" ng-bind="format(comment.date)"></p>
				<div ng-bind-html="comment.content"></div>
			</div>

	
			<?php comment_form(); ?>
			
		</div>
	</div>
	
</div>


