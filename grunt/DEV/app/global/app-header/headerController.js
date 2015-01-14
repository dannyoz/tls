.controller('header',['$scope', function ($scope){

	$scope.ready       = true
	$scope.placeholder = "Tls archive, blogs and website";
	$scope.searchOpen  = false

	$scope.showSearch  = function(){

		$scope.placeholder = "";
		$scope.searchOpen  = true;
	}
	
}])