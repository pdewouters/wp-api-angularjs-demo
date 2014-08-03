<?php
/**
 * Imports the Github starred repositories metadata.
 *
 * @package   Constellations
 * @author    Paul de Wouters <paul@paulwp.com>
 * @license   GPL-2.0+
 * @link      http://paulwp.com/constellations
 * @copyright 2014 Paul de Wouters
 *
 * @wordpress-plugin
 */

require_once  'class-base-importer.php';

/**
 * Class WPC_Starred_Importer
 */
class WPC_Starred_Importer {

	protected $username;

	protected $interval;

	protected $data;

	protected $fields = array();

	protected static $instance;

	private function __construct() {}

	public static function get_instance() {

		if ( ! ( self::$instance instanceof PDW_WPC_Starred_Importer ) ) {

			self::$instance = new PDW_WPC_Starred_Importer();
			self::$instance->fields();
			//self::$instance->schedule();
			self::$instance->import();

		}

		return self::$instance;
	}

	protected function fields() {

		$this->fields = array(
			array(
				'node'              => 'id',
				'meta_field'        => 'github_id',
				'sanitize_callback' => 'sanitize_key',
			),
			array(
				'node'              => 'name',
				'meta_field'        => 'repo_name',
				'sanitize_callback' => 'sanitize_key',
			),
			array(
				'node'              => 'owner',
				'subNode'           => 'login',
				'meta_field'        => 'repo_owner',
				'sanitize_callback' => 'sanitize_text_field',
			),
			array(
				'node'              => 'full_name',
				'post_field'        => 'post_title',
				'sanitize_callback' => 'sanitize_text_field',
			),
			array(
				'node'              => 'description',
				'post_field'        => 'post_content',
				'sanitize_callback' => 'wp_kses_post',
			),
		);

	}

	protected function schedule() {

		if ( ! wp_next_scheduled( 'pdw_wpc_import_stars' ) ) {
			wp_schedule_event( time(), 'hourly', 'pdw_wpc_import_stars' );
		}

		add_action( 'pdw_wpc_import_stars', array( $this, 'import' ) );
	}

	protected function fetch_data() {

		$client = new Github\Client();

		$userApi = $client->api( 'user' );

		$paginator  = new Github\ResultPager( $client );
		$parameters = array( 'pdewouters' ); // Replace with desired github username
		$this->data = $paginator->fetchAll( $userApi, 'starred', $parameters );
		//$this->data = $paginator->fetch( $userApi, 'starred', $parameters );
	}

	protected function import() {

		$importer = new PDW_WPC_Importer( $this->fields );

		$this->fetch_data();

		$posts_to_import = $this->data;

		if ( ! empty( $posts_to_import ) ) {

			foreach ( $posts_to_import as &$post_data ) {

				if ( ! is_array( $post_data ) ) {
					continue;
				}

				$importer->import_post( $post_data['id'], $post_data );

			}
		}

	}

}

// This will run the import on every page load!
// Simply comment out after running once.
add_action( 'init', function () {
	$importer = WPC_Starred_Importer::get_instance();
} );
