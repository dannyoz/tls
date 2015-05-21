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

			<div id="comments-list">
				<div class="comment" ng-repeat="comment in post.comments">
					<h4 class="futura" ng-bind="comment.name"></h4>
					<p class="date futura" ng-bind="format(comment.date)"></p>
					<div class="comment-content" ng-bind-html="comment.content"></div>
				</div>				
			</div>			

			<?php if(comments_open()) : ?>
				<?php if(get_option('comment_registration') && !$user_ID) : ?>
					<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
					<input type="hidden" ng-model="commentAuthor" value="<?php echo $user_ID; ?>" ng-init="'<?php echo $user_ID; ?>'" />
				<?php else : ?>

					<h3 class="futura">Leave a comment</h3>
					
					<form ng-submit="addComment(commentAuthor,commentEmail,commentContent)" method="post" id="commentform" name="commentform">
						<?php if($user_ID) : ?>
							<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Log out &raquo;</a></p>

						<?php else : ?>

							<div class="form-element">
								<label for="author">Name</label>
								<input type="text" name="author" id="author" size="22" tabindex="1" ng-model="commentAuthor" />
							</div>
							
							<div class="form-element">
								<label for="email">Email Address <small>(will not be published)</small></label>
								<input type="text" name="email" id="email" size="22" tabindex="2" ng-model="commentEmail" />
							</div>							
							
						<?php endif; ?>

						<div class="form-element">
							<label for="comment">Comment</label>
							<textarea name="comment" id="comment" rows="10" tabindex="4" ng-model="commentContent" ng-init="text = ''"></textarea>
							<div class="required">All fields required</div>
						</div>

						<div class="form-element">
							<input class="button clear small" name="submit" type="submit" id="submit" tabindex="5" value="Leave comment" />
							<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
						</div>						

						<div class="form-element" ng-if="successCommentMessage || errorCommentMessage">
							<p class="comment-msg success" ng-if="successCommentMessage" >Thanks for your comment. We appreciate your response.</p>
							<p class="comment-msg error" ng-if="errorMessage" >{{ errorMessage }}</p>
						</div>

						<?php do_action('comment_form', $post->ID); ?>

					</form>
				<?php endif; ?>
			<?php else : ?>
				<p>The comments are closed.</p>
			<?php endif; ?>
			
		</div>
	</div>
	
</div>


