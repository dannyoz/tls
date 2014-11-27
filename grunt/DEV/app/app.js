var app = angular.module('tls', ['ngRoute'])

	.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
	 	
	 	$routeProvider

	        .when('/', {
	        	templateUrl: '/ng-views/search.html'
	        })

		$locationProvider.html5Mode(true);
	}])