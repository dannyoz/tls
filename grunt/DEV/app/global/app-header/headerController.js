.controller('header',['$scope','tealium', function ($scope,tealium){

	$scope.ready       = true
	$scope.hint        = false
	$scope.placeholder = "Tls archive, blogs and website";
	$scope.searchOpen  = false

	$scope.showSearch  = function(){

		$scope.placeholder = "";
		$scope.searchOpen  = true;
	}

	$scope.searchFocus = function(){

		$scope.hint = true
		$scope.placeholder = ''; 
		$scope.focus = true
	}

	$scope.subscribe = function(){
		tealium.subscribe('header');
	}

	$scope.login = function(){
		tealium.user('login');
	}
	
}])