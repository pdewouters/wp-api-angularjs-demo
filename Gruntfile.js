module.exports = function(grunt) {

	'use strict';

	require('load-grunt-tasks')(grunt);

	var banner = '/**\n * WP API AngularJS Demo\n * http://paulwp.com\n *\n * Copyright (c) 2014\n This file was generated automatically. Do not edit directly.\n */\n';

	grunt.initConfig({

		uglify: {
			theme: {
				options: {
					sourceMap: true,
					banner: banner,
					mangle: {}
				},
				files: {
					'includes/js/combined.min.js': 'includes/js/combined.js'
				}
			}
		},

		concat: {
			js: {
				src: [
					//'includes/js/modules/angular/angular.js',
					//'includes/js/modules/angular-route/angular-route.js',
					'includes/js/modules/angular-sanitize/angular-sanitize.js',
					'includes/js/modules/ng-tags-input/ng-tags-input.js',
					'includes/js/checklist-model.js',
					'includes/js/app.js'
				],
				dest: 'includes/js/combined.js'
			}
		}

	});

};