<?php
require_once dirname( dirname( dirname( __FILE__ ) ) ) . '/wp-content/themes/tls/functions.php';

class functionsTest extends WP_UnitTestCase {

    private $post;

    function setUp() {
        parent::setUp();

        $this->post = new stdClass;
    }

    function tearDown() {
        parent::tearDown();
    }

    function testMakeExcerptReturnsCleanFromScriptContent() {
        // Arrange
        $this->post->post_content = '<script type="text/javascript">alert("This Could be malicious code");</script>';
        $expectedExcerpt = '...'; // Should clean all JS Code

        // Act
        $actualExcerpt = tls_make_post_excerpt( $this->post->post_content );

        // Assert
        $this->assertEquals( $expectedExcerpt, $actualExcerpt );
    }

    function testMakeExcerptReturnCleanFromPhpContent() {
        // Arrange
        $this->post->post_content = '<?php echo "This could be malicious PHP Code"; ?>';
        $expectedExcerpt = '...'; // Should clean all the PHP Code

        // Act
        $actualExcerpt = tls_make_post_excerpt( $this->post->post_content );

        // Assert
        $this->assertEquals( $expectedExcerpt, $actualExcerpt );
    }

    function testMakeExcerptReturnsCleanFromHtmlContent() {
        // Arrange
        $this->post->post_content = '<html><h1>Heading</h1><p>This is a paragraph text.</p><a href="http://www.google.com">This is a link to Google</a></html>';
        $expectedExcerpt = 'Heading This is a paragraph text. This is a link to Google...'; // Should clean all the PHP Code

        // Act
        $actualExcerpt = tls_make_post_excerpt( $this->post->post_content );

        // Assert
        $this->assertEquals( $expectedExcerpt, $actualExcerpt );
    }

    function testMakeExcerptReturnOnly55Words() {
        // Arrange
        $this->post->post_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tellus est, euismod ut massa luctus, ornare mollis erat. Quisque eu felis auctor neque consectetur venenatis. Pellentesque vestibulum velit nec vehicula consectetur. Praesent dictum vulputate nisl, nec ornare sem lobortis ac. Ut rhoncus purus tellus, sit amet auctor nisi consectetur non. Donec posuere nunc at odio fringilla, et sagittis elit porta.';
        $expectedExcerpt = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tellus est, euismod ut massa luctus, ornare mollis erat. Quisque eu felis auctor neque consectetur venenatis. Pellentesque vestibulum velit nec vehicula consectetur. Praesent dictum vulputate nisl, nec ornare sem lobortis ac. Ut rhoncus purus tellus, sit amet auctor nisi consectetur non. Donec posuere nunc at odio...';

        // Act
        $actualExcerpt = tls_make_post_excerpt( $this->post->post_content );

        // Assert
        $this->assertEquals( $expectedExcerpt, $actualExcerpt );
    }

    function testMakeExcerptReturnsSpecificNumberOfWords() {
        // Arrange
        $this->post->post_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tellus est, euismod ut massa luctus, ornare mollis erat. Quisque eu felis auctor neque consectetur venenatis. Pellentesque vestibulum velit nec vehicula consectetur. Praesent dictum vulputate nisl, nec ornare sem lobortis ac. Ut rhoncus purus tellus, sit amet auctor nisi consectetur non. Donec posuere nunc at odio fringilla, et sagittis elit porta.';
        $expectedExcerpt = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tellus...';

        // Act
        $actualExcerpt = tls_make_post_excerpt( $this->post->post_content, 10 );

        // Assert
        $this->assertEquals( $expectedExcerpt, $actualExcerpt );
    }

    function testMakeExcerptReturns55WordsIfNegativeNumberOfWordsSpecified() {
        // Arrange
        $this->post->post_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tellus est, euismod ut massa luctus, ornare mollis erat. Quisque eu felis auctor neque consectetur venenatis. Pellentesque vestibulum velit nec vehicula consectetur. Praesent dictum vulputate nisl, nec ornare sem lobortis ac. Ut rhoncus purus tellus, sit amet auctor nisi consectetur non. Donec posuere nunc at odio fringilla, et sagittis elit porta.';
        $expectedExcerpt = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tellus est, euismod ut massa luctus, ornare mollis erat. Quisque eu felis auctor neque consectetur venenatis. Pellentesque vestibulum velit nec vehicula consectetur. Praesent dictum vulputate nisl, nec ornare sem lobortis ac. Ut rhoncus purus tellus, sit amet auctor nisi consectetur non. Donec posuere nunc at odio...';

        // Act
        $actualExcerpt = tls_make_post_excerpt( $this->post->post_content, -10 );

        // Assert
        $this->assertEquals( $expectedExcerpt, $actualExcerpt );
    }

}