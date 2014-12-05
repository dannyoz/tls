.controller('search',["$scope",'$sce','$timeout','api','niceDate', function ($scope,$sce,$timeout,api,niceDate) {

	//Set default vars
	var url = window.location.href;
	$scope.filters     = []
	$scope.currentPage = 1
	$scope.orderName   = "Newest"
	$scope.order       = "ASC"
	$scope.showSorter  = false

	api.getSearchResults(url,$scope.currentPage,$scope.filters).then(function (results){
		
		$scope.showFilters = false
		$scope.results = results
		$scope.paginationConfig = {
			"pageCount"   : results.pages,
			"currentPage" : $scope.currentPage,
			"filters"     : $scope.filters,
			"order"       : $scope.order
		}

	})

	//Refresh scope when pagination page is selected
	$scope.$on('updatePage',function (e,results,curr){

		$scope.paginationConfig.currentPage = curr
		$scope.currentPage = curr
		$scope.results     = results

	})

	$scope.filterResults = function(term){

		var index = $scope.filters.indexOf(term);

		if(index == -1){
			$scope.filters.push(term)
		} else {
			$scope.filters.splice(index,1)
		}

		api.getSearchResults(url,1,$scope.filters,$scope.order).then(function (results){
			$scope.results = results
			$scope.paginationConfig = {
				"pageCount"   : results.pages,
				"currentPage" : 1,
				"filters"     : $scope.filters,
				"order"       : $scope.order
			}
		})
	}

	$scope.orderResults = function(order,orderName){

		$scope.order     = order;
		$scope.orderName = orderName;
		
		api.getSearchResults(url,1,$scope.filters,$scope.order).then(function (results){
			$scope.results = results
			$scope.paginationConfig = {
				"pageCount"   : results.pages,
				"currentPage" : 1,
				"filters"     : $scope.filters,
				"order"       : $scope.order
			}
		})
	}


	$scope.format = function(date){
		return niceDate.format(date);
	}


}])