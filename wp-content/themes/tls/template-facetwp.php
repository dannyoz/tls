<?php
/**
 * FacetWP Search Results page
 */
?>
<?php while (have_posts() ) : the_post(); ?>
    <?php
    $article_id = get_the_ID();

    if ('tls_articles' == get_post_type($article_id)) {
        $section_tax = 'article_section';

        $visibility_terms = wp_get_post_terms($article_id, 'article_visibility');
        $visibility = html_entity_decode($visibility_terms[0]->slug);
    } else if ('post' == get_post_type($article_id)) {
        $section_tax = 'category';
    }

    $section_terms = wp_get_post_terms($article_id, $section_tax);
    $section = html_entity_decode($section_terms[0]->name);
    $section_term_link = get_term_link( $section_terms[0]->term_id, $section_tax );

    $post_time = get_the_time('F j Y', $article_id);
    ?>
    <div class="card <?php echo ( isset($visibility) && $visibility == 'private' ) ? 'private' : ''; ?>">
        <?php if (isset($section)) { ?>
            <h3 class="futura date">
                <a href="<?php echo $section_term_link; ?>"><?php echo $section; ?></a>
            </h3>
        <?php } ?>
						<span class="post-date"><?php echo $post_time; ?>
							<?php if (isset($visibility) && $visibility == 'private') : ?>
                                <i class="icon icon-key"></i>
                            <?php endif; ?>
						</span>
        <div class="padded">
            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            <div><?php the_excerpt(); ?></div>
        </div>

        <?php if ( 'tls_articles' == get_post_type($article_id) && have_rows('books') ) { ?>
        <footer>
            <?php while (have_rows('books')) { ?>
                <div class="book summary-wrapper">
                    <p class="book-title futura"><?php the_sub_field('book_title'); ?></p>
                    <p class="futura"><?php the_sub_field('book_author'); ?></p>
                </div>
            <?php } ?>
        </footer>
        <?php } ?>
    </div>
<?php endwhile; ?>