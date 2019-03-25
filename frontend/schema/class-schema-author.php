<?php
/**
 * WPSEO plugin file.
 *
 * @package WPSEO\Frontend\Schema
 */

/**
 * Returns schema Person data.
 *
 * @since 10.2
 *
 * @property WPSEO_Schema_Context $context A value object with context variables.
 */
class WPSEO_Schema_Author extends WPSEO_Schema_Person implements WPSEO_Graph_Piece {
	/**
	 * A value object with context variables.
	 *
	 * @var WPSEO_Schema_Context
	 */
	private $context;

	/**
	 * WPSEO_Schema_Breadcrumb constructor.
	 *
	 * @param WPSEO_Schema_Context $context A value object with context variables.
	 */
	public function __construct( WPSEO_Schema_Context $context ) {
		parent::__construct( $context );
		$this->context = $context;
		$this->logo_hash = WPSEO_Schema_Context::AUTHOR_LOGO_HASH;
	}

	/**
	 * Determine whether we should return Person schema.
	 *
	 * @return bool
	 */
	public function is_needed() {
		if ( $this->is_post_author() ) {
			return true;
		}

		return false;
	}

	/**
	 * Determine whether the current URL is worthy of Article schema.
	 *
	 * @return bool
	 */
	protected function is_post_author() {
		/**
		 * Filter: 'wpseo_schema_article_post_type' - Allow changing for which post types we output Article schema.
		 *
		 * @api array $post_types The post types for which we output Article.
		 */
		$post_types = apply_filters( 'wpseo_schema_article_post_type', array( 'post' ) );
		if ( is_singular( $post_types ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Determines a User ID for the Person data.
	 *
	 * @return bool|int User ID or false upon return.
	 */
	protected function determine_user_id() {
		$user_id = (int) get_post( $this->context->id )->post_author;

		/**
		 * Filter: 'wpseo_schema_person_user_id' - Allows filtering of user ID used for person output.
		 *
		 * @api int|bool $user_id The user ID currently determined.
		 */
		return apply_filters( 'wpseo_schema_person_user_id', $user_id );
	}

	/**
	 * Returns the string to use in Schema's `@id`.
	 *
	 * @param int $user_id The user ID if we're on a user page.
	 *
	 * @return string The `@id` string value.
	 */
	protected function determine_schema_id( $user_id ) {
		return get_author_posts_url( $user_id ) . WPSEO_Schema_Context::AUTHOR_HASH;
	}

}
