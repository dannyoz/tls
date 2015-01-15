themeUrl = 'wp-content/themes/tls';
var app = angular.module('tls', ['ngTouch','ngRoute','ngSanitize','ngDfp'])
	// .config(["$locationProvider",function ($locationProvider) {
 //  		$locationProvider.html5Mode({
	// 	  	enabled: true,
	// 	  	requireBase: false
	// 	});
 //  	}])

.config(["DoubleClickProvider",function (DoubleClickProvider) {
  	DoubleClickProvider.defineSlot('gclid=CJjsqefUkAJSHDJ_gPdDiEAAA&gclsrc=ds', [300, 250], 'advert1');
}])
