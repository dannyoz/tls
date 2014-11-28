var app = angular.module('tls', ['ngTouch','ngRoute'])

	.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
	 	
	 	$routeProvider

	        .when('/', {
	        	templateUrl: '/ng-views/article.html'
	        })

		//$locationProvider.html5Mode(true);
	}])