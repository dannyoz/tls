.controller('search',["$scope",'$sce','$timeout','api','niceDate', function ($scope,$sce,$timeout,api,niceDate) {
	
	api.getSearchResults(window.location.href).then(function (results){
		$scope.results = results
		console.log($scope.results)
	})


	$scope.format = function(date){
		return niceDate.format(date);
	}

}])