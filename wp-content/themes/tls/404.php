<?php

get_header(); ?>

	<article id="accordian-template" class="single-post error-404 not-found">
		<div class="container">
			<div class="grid-row">

				<div class="article-body">
					<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'tls' ); ?></h1>
					<div class="page-content">
						<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'tls' ); ?></p>

						<?php get_search_form(); ?>
					</div>
				</div>
			</div>
		</div>
	</article>

<?php get_footer(); ?>