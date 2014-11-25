var app = angular.module('app', ['ngRoute'])

	.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
	 	
	 	$routeProvider

	        .when('/', {
	        	templateUrl: '/ng-views/home.html'
	        })
	        

		$locationProvider.html5Mode(true);
	}])