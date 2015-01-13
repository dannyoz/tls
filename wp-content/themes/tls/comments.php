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
	
			<?php if(comments_open()) : ?>
				<?php if(get_option('comment_registration') && !$user_ID) : ?>
					<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p><?php else : ?>
					<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
						<?php if($user_ID) : ?>
							<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Log out &raquo;</a></p>
						<?php else : ?>
							<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
							<label for="author"><small>Name <?php if($req) echo "(required)"; ?></small></label></p>
							<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
							<label for="email"><small>Mail (will not be published) <?php if($req) echo "(required)"; ?></small></label></p>
						<?php endif; ?>
						<p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>
						<p><input class="button clear small" name="submit" type="submit" id="submit" tabindex="5" value="Leave comment" />
						<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" /></p>
						<?php do_action('comment_form', $post->ID); ?>
					</form>
				<?php endif; ?>
			<?php else : ?>
				<p>The comments are closed.</p>
			<?php endif; ?>
			
		</div>
	</div>
	
</div>


