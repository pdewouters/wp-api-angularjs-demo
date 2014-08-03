<?php
/**
 * Theme functions.
 */

if ( ! defined( 'THEME_VERSION' ) ) {
	define( 'THEME_VERSION', '0.1.0' );
}

add_action( 'after_setup_theme', function() {
	add_theme_support( 'angular-wp-api', array( 'wpc-angular', 'wpc-angular-resource' ) );
} );

add_action( 'wp_enqueue_scripts', function(){

	wp_enqueue_style( 'foundation', 'http://cdn.foundation5.zurb.com/foundation.css' );

	wp_enqueue_style( 'ng-tags-input', get_template_directory_uri() . '/app/bower_components/ng-tags-input/ng-tags-input.min.css' );

	wp_register_script( 'wpc-angular', get_template_directory_uri() . '/app/bower_components/angular/angular.min.js' );

	wp_register_script( 'wpc-angular-resource', get_template_directory_uri() . '/app/bower_components/angular-resource/angular-resource.min.js' );

	wp_register_script( 'wpc-angular-router', get_template_directory_uri() . '/app/bower_components/angular-ui-router/release/angular-ui-router.min.js' );

	wp_enqueue_script( 'checklist-model', get_template_directory_uri() . '/app/checklist-model.js', array( 'wpc-angular' ), THEME_VERSION );

	wp_enqueue_script( 'ng-tags-input', get_template_directory_uri() . '/app/bower_components/angular-sanitize/angular-sanitize.min.js', array( 'wpc-angular' ), THEME_VERSION );

	wp_enqueue_script( 'ng-sanitize', get_template_directory_uri() . '/app/bower_components/ng-tags-input/ng-tags-input.js', array( 'wpc-angular' ), THEME_VERSION );

	wp_register_script( 'wpc-app',
		get_template_directory_uri() . '/app/app.js',
		array( 'wpc-angular', 'wpc-angular-resource', 'wpc-angular-router', 'checklist-model', 'angular-wp-api' ), THEME_VERSION );


	wp_localize_script( 'wpc-app', 'wpc_app', array( 'templateUrl' => THEME_PARTIALS_URL ) );

	wp_enqueue_script( 'wpc-app' );

});
