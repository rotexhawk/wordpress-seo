<?php
/**
 * WPSEO plugin file.
 *
 * @package WPSEO\Admin
 */

/**
 * Class WPSEO_WP_Data
 *
 * Integration class for the yoast wp-data javascript module.
 */
class WPSEO_WP_Data implements WPSEO_WordPress_Integration {

	/**
	 * Registers all hooks to WordPress
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue required scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		global $pagenow;

		if ( $pagenow === 'post.php' || $pagenow === 'post-new.php' ) {
			$script_handle = WPSEO_Admin_Asset_Manager::PREFIX . 'wp-data';

			wp_enqueue_script( $script_handle );
			wp_localize_script( $script_handle, 'wpseoWpData', $this->get_localized_data() );

		}
	}

	/**
	 * Get localized data.
	 *
	 * @return array Data to localize.
	 */
	public function get_localized_data() {
		return array(
			'taxonomies' => $this->populate_taxonomies(),
			'terms' => (object) array(),
		);
	}

	/**
	 * Get taxonomies for the current post type to populate the localized data.
	 *
	 * @return array Taxonomies for the current post type.
	 */
	private function populate_taxonomies() {
		$request = new WP_REST_Request( 'GET', '/wp/v2/taxonomies' );
		$request->set_query_params( array(
			'type' => get_current_screen()->post_type,
			'per_page' => '-1' ,
		) );

		$response = rest_do_request( $request );

		return $response->get_data();
	}
}