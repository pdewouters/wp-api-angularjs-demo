<?php
/**
 * Theme functions.
 */

if ( ! defined( 'THEME_VERSION' ) ) {
	define( 'THEME_VERSION', '0.1.0' );
}

add_action( 'after_setup_theme', function() {
	add_theme_support( 'angular-wp-api' );
} );

add_action( 'wp_enqueue_scripts', function(){

	wp_enqueue_style( 'foundation', 'http://cdn.foundation5.zurb.com/foundation.css' );

	wp_register_script( 'wpc-app', get_template_directory_uri() . '/includes/js/app.min.js', [ 'angular-wp-api' ], THEME_VERSION );

	wp_enqueue_script( 'wpc-app' );

});
