<?php
/**
 * Theme functions.
 */

if ( ! defined( 'THEME_VERSION' ) ) {
	define( 'THEME_VERSION', '0.1.0' );
}

add_action( 'after_setup_theme', function() {
	add_theme_support( 'angular-wp-api', [ 'wpc-angular', 'wpc-angular-resource' ] );
} );

add_action( 'wp_enqueue_scripts', function(){

	wp_enqueue_style( 'foundation', 'http://cdn.foundation5.zurb.com/foundation.css' );
	
	wp_enqueue_style( 'ng-tabs', get_template_directory_uri() . '/includes/js/modules/ng-tags-input/ng-tags-input.min.css' );

	wp_register_script( 'wpc-angular', get_template_directory_uri() . '/includes/js/modules/angular/angular.min.js', array(), THEME_VERSION );

	wp_register_script( 'wpc-angular-resource', get_template_directory_uri() . '/includes/js/modules/angular-resource/angular-resource.min.js', array( 'wpc-angular' ), THEME_VERSION );

	wp_enqueue_script( 'wpc-app', get_template_directory_uri() . '/includes/js/combined.js', array( 'wpc-angular', 'wpc-angular-resource', 'angular-wp-api' ), THEME_VERSION );


});

add_action( 'wp_json_server_before_serve', function( $server ) {

		global $wpc_stars;

		require_once get_template_directory() . '/includes/stars/class-stars-wp-api.php';
		$wpc_stars = new WP_JSON_Stars( $server );
		$wpc_stars->register_filters();

});

// Register custom post type and taxonomy
require_once get_template_directory() . '/includes/stars/post-type.php';
require_once get_template_directory() . '/includes/stars/taxonomy.php';


// Github Starred repos importer
//require_once get_template_directory() . '/includes/vendor/autoload.php';
//require_once get_template_directory() . '/includes/importer/class-base-importer.php';
//require_once get_template_directory() . '/includes/importer/class-wpc-importer.php';