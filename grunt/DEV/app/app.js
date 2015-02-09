themeUrl = 'wp-content/themes/tls';
var app = angular.module('tls', ['ngTouch','ngRoute','ngSanitize','ngDfp'])
	// .config(["$locationProvider",function ($locationProvider) {
 //  		$locationProvider.html5Mode({
	// 	  	enabled: true,
	// 	  	requireBase: false
	// 	});
 //  	}])

.config(["DoubleClickProvider",function (DoubleClickProvider) {
  	DoubleClickProvider.defineSlot('/1234/travel/asia/food', [300, 250], 'div-gpt-ad-123456789-0');
}])
