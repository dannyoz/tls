<?php
/*
Template Name: TLS Feed Simple XML Parser
 */

get_header();
?>

<main>
	<h1>Feed Parser</h1>
	<?php 

		// Sanitise URL using WP function esc_url() and assign it to $feedUrl variable
		$feedUrl = esc_url(get_field('feed_url'));
		
		// Get XML contents from teh feed URL
		$rawFeed = file_get_contents($feedUrl);

		// Assign the raw XML content of feed to a new SimpleXml object assigned to $xml
		$xml = new SimpleXmlElement($rawFeed);


		/**
		 * Loop through all entry tags in XML feed and assign each to
		 * $article variable
		 * This may have to change on the real feed as everything might
		 * be entry and will have to target specific entry attribute
		 * tags based on the data model of methode
		 */
		foreach ($xml->entry as $article) {

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
			
			// For Development only. Start <article> tag, then add
			// Title, Author, Date Published, Date Updated, Content
			// Taxonomy terms, ID, and any image fields
			// to display and map to custom fields
			// This is to check the concept of being able to get all
			// the feed elements
			// echo "<article>";
			// echo "<h1>Title:</h1>"; var_dump($articleTitle);
			// echo "<h3>Post ID:</h3>"; var_dump($articleFeedId);
			// echo "<h3>Author Name:</h3>"; var_dump($articleAuthor);
			// echo "<h3>Author Email:</h3>"; var_dump($articleAuthorEmail);
			// echo "<h3>Author URI:</h3>"; var_dump($articleAuthorUri);
			// echo "<h3>Date Published:</h3>"; var_dump($articlePublished);
			// echo "<h3>Date Updated:</h3>"; var_dump($articleUpdated);
			// echo "Categories: <br />";
			// echo "<ul>";

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
				// echo "<li>" . $category['term'] . "</li>";

				$categoryTerm = term_exists($category['term'], 'article-category');
				if ($categoryTerm == 0 && $categoryTerm == null) {
					wp_insert_term($category['term'], 'article-category');
				}

				$articleCategories[] = $category['term'];
			}
			// echo "</ul>";
			// echo "<h2> Post Content </h2>";
			// echo $articleContent;
			// echo "<br /><br />";

			$newArticleData = array(
			  'post_content'   => html_entity_decode($articleContent), // The full text of the post.
			  'post_title'     => wp_strip_all_tags($articleTitle), // The title of your post.
			  'post_status'    => 'publish', // Default 'draft'.
			  'post_type'      => 'tls_articles', // Default 'post'.
			  'post_author'    => 1, // The user ID number of the author. Default is the current user ID.
			  'ping_status'    => 'closed', // Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
			  'post_excerpt'   => html_entity_decode($articleTeaser), // For all your post excerpt needs.
			  'post_date'      => $articlePublished,
			  'post_modified'  => $articleUpdated,
			  'comment_status' => 'closed', // Default is the option 'default_comment_status', or 'closed'.
			);

			$newArticleId = wp_insert_post($newArticleData);

			// Add Article Category Terms found in $articleCategories array
			// This array will be reset again on the next item
			wp_set_object_terms($newArticleId, $articleCategories, 'article-category');

			// Article Feed ID Custom Field
			update_field( "field_54776890165dc", $articleFeedId, $newArticleId );
			// Article Author Name Custom Field
			update_field( "field_547768aa165dd", $articleAuthor, $newArticleId );
			// Article Author Email Custom Field
			update_field( "field_547768b9165de", $articleAuthorEmail, $newArticleId );
			// Article Author URI Custom Field
			update_field( "field_547768c4165df", $articleAuthorUri, $newArticleId );
			// Article Date Published Custom Field
			update_field( "field_547768d2165e0", $articlePublished, $newArticleId );
			// Article Date Updated Custom Field
			update_field( "field_5477693b165e1", $articleUpdated, $newArticleId );
			// Article Teaser Custom Field
			update_field( "field_54776952165e2", $articleTeaser, $newArticleId );



			//echo "<pre>";
			//print_r($article);
			//echo "</pre>";
			// echo "</article>";
		}

	?>
</main>

<?php
get_footer();