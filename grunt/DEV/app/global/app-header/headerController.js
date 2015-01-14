.controller('header',['$scope', function ($scope){

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
	
}])