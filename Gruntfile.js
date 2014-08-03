module.exports = function(grunt) {

	'use strict';

	require('load-grunt-tasks')(grunt);

	var banner = '/**\n * WP API AngularJS Demo\n * http://paulwp.com\n *\n * Copyright (c) 2014\n This file was generated automatically. Do not edit directly.\n */\n';

	var includesPath = 'includes/js/';

	var themeScripts = [
		includesPath + 'angular/angular.min.js',
		includesPath + 'angular-resource/angular-resource.min.js',
		includesPath + 'angular-sanitize/angular-sanitize.min.js',
		includesPath + 'ng-tags-input/ng-tags-input.min.js',
		includesPath + 'checklist-model.js',
		includesPath + 'app.js'
	];

	grunt.initConfig({

		uglify: {
			theme: {
				options: {
					sourceMap: true,
					banner: banner,
					mangle: {},
				},
				files: {
					'includes/js/app.min.js': themeScripts
				}
			}
		}

	});

};