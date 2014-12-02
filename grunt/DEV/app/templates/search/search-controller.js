.controller('search',["$scope",'$sce','$timeout','api','niceDate', function ($scope,$sce,$timeout,api,niceDate) {

	api.getSearchResults(window.location.href,1).then(function (results){
		$scope.results = results
		$scope.paginationConfig = {
			"pageCount"   : results.pages,
			"currentPage" : 1
		}
	})

	//Refresh scope when pagination page is selected
	$scope.$on('updatePage',function (e,results,curr){

		$scope.paginationConfig.currentPage = curr
		$scope.results = results

	})


	$scope.format = function(date){
		return niceDate.format(date);
	}

}])