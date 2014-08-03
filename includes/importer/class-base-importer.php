<?php

/**
 * Generic Content Importer Class
 */
class PDW_WPC_Importer {

	private $fields;

	/**
	 *
	 * Pass $fields mapping when instantiating the importer class.
	 *
	 * array(
	 *     array(
	 *         'node' => '', // required, $dirty data key.
	 *         'sanitize_callback'  => '', // required sanitize function
	 *         // Note only one of 'post_field', 'meta_field' and 'update_callback' is required.
	 *         'post_field' => '', // optional, if this is a post field.
	 *         'meta_field' => '', // optional, if this is a meta field.
	 *         'update_callback' => // optional custom update callback.
	 *     ),
	 *
	 * @param array $fields see above for format.
	 */
	public function __construct( $fields ) {
		$this->fields = $fields;
	}

	/**
	 * Handle Update/insert post.
	 * Pass an array of data, this is sanitized and formatted, then inserted.
	 *
	 * @param  array $dirty_data data in format node=>value.
	 * @return post_id
	 */
	public function import_post( $unique_id, array $dirty_data ) {

		if ( empty( $dirty_data ) ) {
			return;
		}

		// Some default post data.
		$clean_data = array(
			'post' => array(
				'post_type' => 'pdw_wpc_gh_star',
				'post_status' => 'publish',
			),
			'meta' => array(),
			'custom' => array()
		);

		$clean_data = $this->sanitize_data( $dirty_data, $clean_data );

		// Use passed id to try and update post instead of inserting.
		if ( $existing_post = $this->get_existing_post( $unique_id ) ) {
			$clean_data['post']['ID'] = $existing_post;
			$post_id = wp_update_post( wp_slash( $clean_data['post'] ), true );
		} else {
			$post_id = wp_insert_post( wp_slash( $clean_data['post'] ), true );
			update_post_meta( $post_id, '_pdw_wpc_original_id', $unique_id );
		}

		if ( is_wp_error( $post_id ) ) {
			return;
		}

		// Flag the post as import in progress.
		add_post_meta( $post_id, '_pdw_wpc_import_incomplete', true, true );

		foreach ( $clean_data['meta'] as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		// Custom update callbacks
		foreach ( $clean_data['custom'] as $item ) {
			call_user_func_array( $item['update_callback'], array( $item['value'], $post_id ) );
		}

		// remove import in progress flag.
		delete_post_meta( $post_id, 'pdw_wpc_import_incomplete', true, true );

		return $post_id;

	}

	/**
	 * Get existing post for scribblelive id.
	 *
	 * @param  int/string original unique ID.
	 * @return int/null post_id if exists.
	 */
	private static function get_existing_post( $unique_id ) {

		$query = new WP_Query( array(
			'post_type' => get_post_types(),
			'post_status' => 'any',
			'meta_query' => array(
				array(
					'key' => '_pdw_wpc_original_id',
					'value' => $unique_id,
				),
			),
			'fields' => 'ids',
			'posts_per_page' => 1,
			'cache_results' => false,
		) );

		if ( $query->have_posts() ) {
			return reset( $query->posts );
		}

	}

	/**
	 * @param $dirty_data
	 * @param $clean_data
	 *
	 * @return mixed
	 */
	private function sanitize_data( $dirty_data, $clean_data = array() ) {

		$clean_data = wp_parse_args(
			$clean_data,
			array(
				'post' => array(),
				'meta' => array(),
				'custom' => array()
			)
		);

		foreach ( $this->fields as $field ) {

			if ( ! isset( $dirty_data[ $field['node'] ] ) ) {
				continue;
			}

			// Handle subNodes.
			if ( isset( $field['subNode'] ) ) {
				$dirty_value = $dirty_data[ $field['node'] ][ $field['subNode'] ];
			} else {
				$dirty_value = $dirty_data[ $field['node'] ];
			}

			if ( ! empty( $field['post_field'] ) ) {

				$clean_data['post'][ $field['post_field'] ] = call_user_func_array(
					$field['sanitize_callback'],
					array( $dirty_value )
				);

			} elseif ( ! empty( $field['meta_field'] ) ) {

				$clean_data['meta'][ $field['meta_field'] ] = call_user_func_array(
					$field['sanitize_callback'],
					array( $dirty_value )
				);

			} elseif ( ! empty( $field['update_callback'] ) ) {

				$clean_data['custom'][] = array(
					'update_callback' => $field['update_callback'],
					'value'           => call_user_func_array(
						$field['sanitize_callback'],
						array( $dirty_value )
					),
				);

			}
		}

		return $clean_data;

	}

	/**
	 * Store the source URL to be proxied through Photon
	 *
	 * @param string $url
	 * @param int $post_id
	 * @return null
	 */
	private static function update_thumbnail( $url, $post_id ) {


		update_post_meta( $post_id, '_thumbnail_source_url', $url, true );

	}

	/**
	 * Sideload Image.
	 * Return attachment ID.
	 *
	 * @param  string $src exernal imagei source
	 * @param  int $post_id post ID. Sideloaded image is attached to this post.
	 * @param  string $desc Description of the sideloaded file.
	 * @return int Attachment ID
	 */
	private static function sideload_image( $src, $post_id = null, $desc = null ) {
		return wpcom_vip_download_image( $src, $post_id, $desc );
	}

}
