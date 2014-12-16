themeUrl = 'wp-content/themes/tls';
var app = angular.module('tls', ['ngTouch','ngRoute','ngSanitize'])
	.config(["$locationProvider",function ($locationProvider) {
  		$locationProvider.html5Mode({
		  	enabled: true,
		  	requireBase: false
		});
  	}])
