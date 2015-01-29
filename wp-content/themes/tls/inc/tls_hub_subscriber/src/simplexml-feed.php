<?php
//place this before any script you want to calculate time
$time_start = microtime(true);
$articleCount = 0;

$feedUrl = false;
use GuzzleHttp\Client as GuzzleClient;
if ( isset( $_GET['manual_pull'] ) && isset( $_GET['pull_url'] ) ) {
	// Sanitise URL using WP function esc_url() and assign it to $feedUrl variable
	$feedUrl = esc_url(  $_GET['pull_url'] );
	$client = new GuzzleClient();
	$response = $client->get($feedUrl);
	$xml = $response->xml();

}
//echo $feedUrl;
// Get XML contents from teh feed URL
//$rawFeed = file_get_contents( get_stylesheet_directory_uri() . '/inc/push_subscriber_wp/src/feed.xml');

// Assign the raw XML content of feed to a new SimpleXml object assigned to $xml
//$xml = new SimpleXmlElement($rawFeed);

//die( var_export( $xml ) );

/**
 * Loop through all entry tags in XML feed and assign each to
 * $article variable
 * This may have to change on the real feed as everything might
 * be entry and will have to target specific entry attribute
 * tags based on the data model of methode
 */
foreach ($xml->entry as $article) {

	$media = $article->children('media', true);

	$articleFeedId = (string)$article->id;
	$articleTitle = (string)$article->title;
	$articleAuthor = (string)$article->author->name;
	$articleAuthorEmail = (string)$article->author->email;
	$articleAuthorUri = (string)$article->author->uri;
	$articlePublished = (string)$article->published;
	$articleUpdated = (string)$article->updated;
	$articleTeaser = (string)$article->summary;
	$articleContent = (string)$article->content;

	$articleCategories = array();

	$offset = (int) get_option('gmt_offset') * 60 * 60;
	$post_date_gmt = $articlePublished;
	$post_modified_gmt = $articleUpdated;

	/**
	 * Iterate through all the <category> tags in the XML Feed
	 * Then check if that term exists in the article-category
	 * taxonomy already and if not create it.
	 *
	 * TODO Probably need to add these terms to an array
	 * to be able to use them later on to attach
	 * to each of the posts that I need to created
	 * based in each of the <entry> tags of the XML
	 */
	foreach ($article->category as $category) {

		$categoryTerm = term_exists($category['term'], 'article_section');
		if ($categoryTerm == 0 && $categoryTerm == null) {
			wp_insert_term($category['term'], 'article_section');
		}

		$articleCategories[] = $category['term'];
	}

	/**
	 * [$articleMatches_args Arguments for a comparison WP Query]
	 * @var array
	 */
	$articleMatches_args = array(
		'numberposts' => 1,
        'post_type' => 'tls_articles',
        'meta_key' => 'article_feed_id',
        'meta_value' => $articleFeedId
	);
	/**
	 * [$articleMatches WP Query to check if there are
	 * any articles in the feed that match with articles
	 * in the WordPress backend]
	 * @var WP_Query
	 */
	$articleMatches = new WP_Query($articleMatches_args);

	/**
	 * If statement to check if the result of the query brought
	 * any macthing articles. If no match is found then 
	 * create a new post otherwise if it has found a match
	 * update that post instead
	 */
	if (!$articleMatches->found_posts > 0) {
		$newArticleData = array(
			'post_content'   => html_entity_decode($articleContent), // The full text of the post.
			'post_title'     => wp_strip_all_tags($articleTitle), // The title of your post.
			'post_status'    => 'draft', // Default 'draft'.
			'post_type'      => 'tls_articles', // Default 'post'.
			'post_author'    => 1, // The user ID number of the author. Default is the current user ID.
			'ping_status'    => 'closed', // Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
			'post_excerpt'   => html_entity_decode($articleTeaser), // For all your post excerpt needs.
			'post_date'      => date( "Y-m-d H:i:s", strtotime( $articlePublished ) ), // Published Date
			'post_date_gmt'  => date( "Y-m-d H:i:s", strtotime( $articlePublished ) ), // Published Date GMT
			'post_modified'      => date( "Y-m-d H:i:s", strtotime( $articleUpdated ) ), // Updated Date
			'post_modified_gmt'  => date( "Y-m-d H:i:s", strtotime( $articleUpdated ) ), // Updated Date GMT
			'comment_status' => 'closed', // Default is the option 'default_comment_status', or 'closed'.
		);

		$newArticleId = wp_insert_post($newArticleData);

		// Add Article Category Terms found in $articleCategories array
		// This array will be reset again on the next item
		wp_set_object_terms($newArticleId, $articleCategories, 'article_section');

		// Article Feed ID Custom Field
		update_field( "article_feed_id", $articleFeedId, $newArticleId );
		// Article Author Name Custom Field
		update_field( "article_author_name", $articleAuthor, $newArticleId );
		// Article Author Email Custom Field
		update_field( "article_author_email", $articleAuthorEmail, $newArticleId );
		// Article Author URI Custom Field
		update_field( "article_author_uri", $articleAuthorUri, $newArticleId );
		// Article Date Published Custom Field
		update_field( "article_date_published", date( "Y-m-d H:i:s", strtotime( $articlePublished ) ), $newArticleId );
		// Article Date Updated Custom Field
		update_field( "article_date_updated", date( "Y-m-d H:i:s", strtotime( $articleUpdated ) ), $newArticleId );
		// Article Teaser Custom Field
		update_field( "teaser_summary", $articleTeaser, $newArticleId );

		$thumbnailImage = (string) $media->content->attributes()->url;
		update_field( 'thumbnail_image_url', $thumbnailImage , $newArticleId );

		$articleCount++;
	} else { // Matches have been found

		while($articleMatches->have_posts()) { // WP Loop to update the Article
			$articleMatches->the_post();

			$articleID = get_the_ID(); // Article ID

			/**
			 * [$updateArticle Arguments for the 
			 * Article update]
			 * @var array
			 */
			$updateArticle = array(
				'ID'			 => $articleID,
				'post_title'     => wp_strip_all_tags($articleTitle), // The title of your post.
				'post_content'   => html_entity_decode($articleContent), // The full text of the post.
				'post_excerpt'   => html_entity_decode($articleTeaser), // For all your post excerpt needs.
				'post_date'      => date( "Y-m-d H:i:s", strtotime( $articlePublished ) ), // Published Date
				'post_date_gmt'  => date( "Y-m-d H:i:s", strtotime( $articlePublished ) ), // Published Date GMT
				'post_modified'      => date( "Y-m-d H:i:s", strtotime( $articleUpdated ) ), // Updated Date
				'post_modified_gmt'  => date( "Y-m-d H:i:s", strtotime( $articleUpdated ) ), // Updated Date GMT
			);

			wp_update_post($updateArticle);

			// Add Article Category Terms found in $articleCategories array
			// This array will be reset again on the next item
			wp_set_object_terms($articleID, $articleCategories, 'article-category');

			// Article Feed ID Custom Field
			update_field( "article_feed_id", $articleFeedId, $articleID );
			// Article Author Name Custom Field
			update_field( "article_author_name", $articleAuthor, $articleID );
			// Article Author Email Custom Field
			update_field( "article_author_email", $articleAuthorEmail, $articleID );
			// Article Author URI Custom Field
			update_field( "article_author_uri", $articleAuthorUri, $articleID );
			// Article Date Published Custom Field
			update_field( "article_date_published", date( "Y-m-d H:i:s", strtotime( $articlePublished ) ), $articleID );
			// Article Date Updated Custom Field
			update_field( "article_date_updated", date( "Y-m-d H:i:s", strtotime( $articleUpdated ) ), $articleID );
			// Article Teaser Custom Field
			update_field( "teaser_summary", $articleTeaser, $articleID );

		} // End While WP Loop

		$articleCount++;
	} // End If/Else Loop for matching articles

} // End foreach loop on the XML articles


$time_end = microtime(true);

//dividing with 60 will give the execution time in minutes other wise seconds
$execution_time = ($time_end - $time_start);

echo 'The Script to parse this XML Feed took <strong>' . $execution_time . ' seconds</strong> for <strong>' . $articleCount . ' articles</strong>';