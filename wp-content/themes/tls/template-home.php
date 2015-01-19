<?php
/**
 * Template Name: Home Page Template
 *
 * @package tls
 */

get_header(); ?>

<section id="home" ng-controller="home">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
	?>
		<div id="banner" ng-attr-style="background-image:url({{featured.images.hero_image}})">

			<div class="container">

				<div class="caption">
					<h2><a ng-attr-href="{{featued.link}}" ng-bind="featured.title"></a></h2>
					<p class="excerpt"><a ng-attr-href="{{featued.link}}" ng-bind="featured.text"></a></p>
				</div>
			</div>

			<div class="gradient"></div>

		</div>

		<div class="container">

			<p class="excerpt mobile" ng-if="size == 'mobile'">
				<a ng-attr-href="{{featued.link}}" ng-bind="featured.text"></a>
			</p>
			
			<div class="grid-row" ng-if="size == 'desktop'">
				
				<div  class="grid-4" ng-repeat="column in col3">
					
					<div ng-repeat="card in column">
						<div tls-card="card"></div>
					</div>

				</div>

			</div>

			<div class="grid-row" ng-if="size == 'tablet'">
				
				<div  class="grid-6" ng-repeat="column in col2">
					
					<div ng-repeat="card in column">
						<div tls-card="card"></div>
					</div>

				</div>

			</div>

			<div class="grid-row" ng-if="size == 'mobile'">
				
				<div  class="grid-12" ng-repeat="column in col1">
					
					<div ng-repeat="card in column">

						<div tls-card="card"></div>

					</div>

				</div>

			</div>
			
		</div>

		<div class="grid-row" id="subscriber" ng-class="{locked:isLocked}">

			<div class="container">

				<div class="title-icon icon">
					<div class="icon-border icon-key"></div>
					<h2><?php _e( 'Subscriber exclusive', 'tls' ); ?></h2>
				</div>

				<?php if( have_rows('subscriber_exclusive_items') ): ?>

					<div class="subscriber-grid-wrapper">					
					
						<?php while ( have_rows('subscriber_exclusive_items') ) : the_row(); ?>
							<?php
								$subItemTitle = get_sub_field( 'title' );
								$subItemImage = get_sub_field( 'image' );
								$subItemDesc = get_sub_field( 'description' );
								$subItemUrl = get_sub_field( 'url_link' );
							?>
							<div class="subscribe-grid">
								<div class="card">
									<?php if ( $subItemUrl ) : ?>
										<a href="<?php echo wp_strip_all_tags( $subItemUrl ); ?>" target="_self">
											<h3 class="futura"><?php echo $subItemTitle; ?></h3>
										</a>
									<?php else : ?>
										<h3 class="futura"><?php echo $subItemTitle ?></h3>
									<?php endif; ?>

									<?php if ( $subItemUrl ) : ?>
										<a href="<?php echo wp_strip_all_tags( $subItemUrl ); ?>">
											<img class="max" src="<?php echo $subItemImage['url'] ?>" alt="<?php echo $subItemImage['alt']; ?>"/>
										</a>
									<?php else : ?>
										<img class="max" src="<?php echo $subItemImage['url'] ?>" alt="<?php echo $subItemImage['alt']; ?>"/>
									<?php endif; ?>
									<p class="padded">
										<?php echo $subItemDesc; ?>
									</p>
								</div>
							</div>
						<?php endwhile; ?>
					</div>

				<?php endif; ?>

				<div class="cta-buttons">
					<button class="button subscribe" ng-click="subscribe();">Subcribe</button>
					<button class="button clear login"><i class="icon icon-login"></i> Login</button>
				</div>

			</div>

		</div>

		<div id="edition-preview">

			<div class="container">
				
				<div id="this-week">

					<div class="preview grid-row">

						<div class="top">
							<h3><?php _e( 'This <br> week\'s <br> TLS', 'tls' ); ?></h3>
						</div>

						<div class="prevbody">

							<div class="grid-6">
								<h4 class="main"><?php the_field( 'this_weeks_heading' ); ?></h4>
								<p>
									<?php the_field( 'this_weeks_text' ); ?>
								</p>
								<a ng-click="tealium.viewEdition();" href="<?php the_field( 'this_weeks_link_to_page' ) ?>"><button><?php _e( 'View edition', 'tls'); ?></button></a>
							</div>

							<div class="grid-6">
								<?php

									$image = get_field('this_weeks_edition_image');

									if( !empty($image) ):
								?>

										<img class="max" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />

								<?php endif; ?>
							</div>

						</div>		

					</div>
					
				</div>

				<div id="next-week">

					<div class="preview grid-row">

						<div class="top">
							<h3><?php _e( 'In next <br/> week\'s <br/> TLS', 'tls') ?></h3>
							<div class="date">
								<span><u><?php _e( 'OUT', 'tls'); ?></u></span><br/>
								<span>
									<?php
									$nextWeeksDate = DateTime::createFromFormat('Ymd', get_field('next_weeks_date'));
									echo $nextWeeksDate->format( 'j M Y' );
									?>
								</span>
							</div>
						</div>

						<div class="prevbody">

							<?php if( have_rows('next_weeks_articles') ): ?>
								<ul>
							<?php while ( have_rows('next_weeks_articles') ) : the_row(); ?>
									<li>
										<h4><?php the_sub_field( 'author' ); ?></h4>
										<h5><?php the_sub_field( 'title' ); ?></h5>
									</li>
							<?php endwhile; ?>
								</ul>
							<?php endif; ?>

						</div>

					</div>

				</div>			

			</div>

		</div>
	<?php
		endwhile; // End while loop
	endif; // End if have_posts()
	?>
</section>
<?php get_footer(); ?>