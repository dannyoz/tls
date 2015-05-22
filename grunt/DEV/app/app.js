themeUrl = 'wp-content/themes/tls';
var app = angular.module('tls', ['ngTouch','ngRoute','ngSanitize','ngDfp'])
    // .config(["$locationProvider",function ($locationProvider) {
 //         $locationProvider.html5Mode({
    //      enabled: true,
    //      requireBase: false
    //  });
 //     }])

.config(["DoubleClickProvider", function (DoubleClickProvider) {

    var currentLocation = window.location.pathname;

    // ------------------------
    // localhost SETTINGS
    // ------------------------    
    // if (currentLocation == '/') {
    //     // Homepage
    //     DoubleClickProvider
    //         .defineSlot('/25436805/TheTimesTLS/Home', [300,250],'div-gpt-ad-home-1', {target: ['mpu']})
    //         .defineSlot('/25436805/TheTimesTLS/Home', [300,251],'div-gpt-ad-home-2', {target: ['bottommpu']});
    // }
    // if (currentLocation == '/discover/') {
    //     // Discover
    //     DoubleClickProvider
    //         .defineSlot('/25436805/TheTimesTLS/Discover', [300,250],'div-gpt-ad-discover-1', {target: ['mpu']})
    //         .defineSlot('/25436805/TheTimesTLS/Discover', [300,251],'div-gpt-ad-discover-2', {target: ['bottommpu']});
    // }
    // if (currentLocation.indexOf('/articles/') !== -1) {
    //     // Discover
    //     DoubleClickProvider
    //         .defineSlot('/25436805/TheTimesTLS/Discover', [300,250],'div-gpt-ad-discover-single-1', {target: ['mpu']})
    //         .defineSlot('/25436805/TheTimesTLS/Discover', [300,251],'div-gpt-ad-discover-single-2', {target: ['bottommpu']});
    // }
    // if (currentLocation == '/blogs/') {
    //     // Blogs
    //     DoubleClickProvider
    //         .defineSlot('/25436805/TheTimesTLS/Blogs', [300,250],'div-gpt-ad-blogs-1', {target: ['mpu']})
    //         .defineSlot('/25436805/TheTimesTLS/Blogs', [300,251],'div-gpt-ad-blogs-2', {target: ['bottommpu']}); 
    // }


    // ------------------------
    // live SETTINGS
    // ------------------------    
    if (currentLocation == '/') {
        // Homepage
        DoubleClickProvider
            .defineSlot('/3048/tls.times.co.uk/Home', [300,250],'div-gpt-ad-home-1', {target: ['mpu']})
            .defineSlot('/3048/tls.times.co.uk/Home', [300,251],'div-gpt-ad-home-2', {target: ['bottommpu']});
    }
    if (currentLocation == '/discover/') {
        // Discover
        DoubleClickProvider
            .defineSlot('/3048/tls.times.co.uk/Discover', [300,250],'div-gpt-ad-discover-1', {target: ['mpu']})
            .defineSlot('/3048/tls.times.co.uk/Discover', [300,251],'div-gpt-ad-discover-2', {target: ['bottommpu']});
    }
    if (currentLocation.indexOf('/articles/') !== -1) {
        // Discover
        DoubleClickProvider
            .defineSlot('/3048/tls.times.co.uk/Discover', [300,250],'div-gpt-ad-discover-single-1', {target: ['mpu']})
            .defineSlot('/3048/tls.times.co.uk/Discover', [300,251],'div-gpt-ad-discover-single-2', {target: ['bottommpu']});
    }
    if (currentLocation == '/blogs/') {
        // Blogs
        DoubleClickProvider
            .defineSlot('/3048/tls.times.co.uk/Blogs', [300,250],'div-gpt-ad-blogs-1', {target: ['mpu']})
            .defineSlot('/3048/tls.times.co.uk/Blogs', [300,251],'div-gpt-ad-blogs-2', {target: ['bottommpu']}); 
    } 

}])
