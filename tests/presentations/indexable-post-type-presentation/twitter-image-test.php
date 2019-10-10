<?php

namespace Yoast\WP\Free\Tests\Presentations\Indexable_Post_Type_Presentation;

use Yoast\WP\Free\Tests\TestCase;

/**
 * Class Twitter_Image_Test
 *
 * @coversDefaultClass \Yoast\WP\Free\Presentations\Indexable_Post_Type_Presentation
 *
 * @group presentations
 * @group twitter
 * @group twitter-image
 */
class Twitter_Image_Test extends TestCase {
	use Presentation_Instance_Builder;

	/**
	 * Sets up the test class.
	 */
	public function setUp() {
		parent::setUp();

		$this->setInstance();
	}

	/**
	 * Tests the situation where the twitter image is given.
	 *
	 * @covers ::generate_twitter_image
	 */
	public function test_with_set_twitter_image() {
		$this->indexable->twitter_image = 'twitter_image.jpg';

		$this->assertEquals( 'twitter_image.jpg', $this->instance->generate_twitter_image() );
	}

	/**
	 * Tests the situation where the opengraph image is given.
	 *
	 * @covers ::generate_twitter_image
	 */
	public function test_with_opengraph_image() {
		$this->options_helper
			->expects( 'get' )
			->once()
			->with( 'opengraph' )
			->andReturnTrue();

		$this->indexable->og_image = 'facebook_image.jpg';

		$this->assertEquals( 'facebook_image.jpg', $this->instance->generate_twitter_image() );
	}


	/**
	 * Tests the situation where no twitter image is set and the opengraph is disabled.
	 *
	 * @covers ::generate_twitter_image
	 */
	public function _test_with_opengraph_disabled() {
		$this->options_helper
			->expects( 'get' )
			->twice()
			->with( 'opengraph' )
			->andReturnFalse();

		$this->indexable->og_image = 'facebook_image.jpg';

		$this->assertEmpty( $this->instance->generate_twitter_image() );
	}
	/**
	 * Tests the situation where the featured image is given.
	 *
	 * @covers ::generate_twitter_image
	 */
	public function test_with_a_featured_image() {
		$this->image_helper
			->expects( 'get_featured_image' )
			->once()
			->andReturn( 'attachment_image.jpg' );

		$this->assertEquals( 'attachment_image.jpg', $this->instance->generate_twitter_image() );
	}

	/**
	 * Tests the situation where the gallery image is given.
	 *
	 * @covers ::generate_twitter_image
	 */
	public function test_with_a_gallery_image() {

		$this->image_helper
			->expects( 'get_featured_image' )
			->once()
			->andReturnFalse();

		$this->image_helper
			->expects( 'get_gallery_image' )
			->once()
			->andReturn( 'gallery_image.jpg' );

		$this->assertEquals( 'gallery_image.jpg', $this->instance->generate_twitter_image() );
	}

	/**
	 * Tests the situation where the post image is given.
	 *
	 * @covers ::generate_twitter_image
	 */
	public function test_with_a_post_content_image() {
		$this->image_helper
			->expects( 'get_featured_image' )
			->once()
			->andReturnFalse();

		$this->image_helper
			->expects( 'get_gallery_image' )
			->once()
			->andReturnFalse();

		$this->image_helper
			->expects( 'get_post_content_image' )
			->once()
			->andReturn( 'post_content_image.jpg' );

		$this->assertEquals( 'post_content_image.jpg', $this->instance->generate_twitter_image() );
	}

	/**
	 * Tests the situation where the default image is given.
	 *
	 * @covers ::generate_twitter_image
	 */
	public function test_with_default_image() {
		$this->options_helper
			->expects( 'get' )
			->times( 3 )
			->andReturn( true, 0, 'default_image.jpg' );

		$this->image_helper
			->expects( 'get_featured_image' )
			->once()
			->andReturnFalse();

		$this->image_helper
			->expects( 'get_gallery_image' )
			->once()
			->andReturnFalse();

		$this->image_helper
			->expects( 'get_post_content_image' )
			->once()
			->andReturnFalse();

		$this->assertEquals( 'default_image.jpg', $this->instance->generate_twitter_image() );
	}
	/**
	 * Tests the situation where the default image is not given.
	 *
	 * @covers ::generate_twitter_image
	 */
	public function test_with_no_default_image_given() {
		$this->options_helper
			->expects( 'get' )
			->once()
			->andReturn( false );

		$this->image_helper
			->expects( 'get_featured_image' )
			->once()
			->andReturnFalse();

		$this->image_helper
			->expects( 'get_gallery_image' )
			->once()
			->andReturnFalse();

		$this->image_helper
			->expects( 'get_post_content_image' )
			->once()
			->andReturnFalse();

		$this->assertEmpty( $this->instance->generate_twitter_image() );
	}
}