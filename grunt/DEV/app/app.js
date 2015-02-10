themeUrl = 'wp-content/themes/tls';
var app = angular.module('tls', ['ngTouch','ngRoute','ngSanitize','ngDfp'])
	// .config(["$locationProvider",function ($locationProvider) {
 //  		$locationProvider.html5Mode({
	// 	  	enabled: true,
	// 	  	requireBase: false
	// 	});
 //  	}])

.config(["DoubleClickProvider", function (DoubleClickProvider) {

    // Homepage Ads
    DoubleClickProvider
        .defineSlot('/25436805/TheTimesTLS/Home', [[300,250]],'div-gpt-ad-home-1', {target: ['mpu']})
  	    .defineSlot('/25436805/TheTimesTLS/Home', [[300,251]],'div-gpt-ad-home-2', {target: ['bottommpu']})    
        .defineSlot('/25436805/TheTimesTLS/Discover', [[300,250]],'div-gpt-ad-discover-1', {target: ['mpu']})
        .defineSlot('/25436805/TheTimesTLS/Discover', [[300,251]],'div-gpt-ad-discover-2', {target: ['bottommpu']})
        .defineSlot('/25436805/TheTimesTLS/Blogs', [[300,250]],'div-gpt-ad-blogs-1', {target: ['mpu']})
        .defineSlot('/25436805/TheTimesTLS/Blogs', [[300,251]],'div-gpt-ad-blog-2', {target: ['bottommpu']});        
}])
