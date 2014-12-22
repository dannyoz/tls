.controller('search',["$scope",'$sce','$timeout','api','niceDate', function ($scope,$sce,$timeout,api,niceDate) {

	//Set default vars
	var url = window.location.href;
	$scope.filters     = []
	$scope.currentPage = 1
	$scope.orderName   = "Newest"
	$scope.order       = "ASC"
	$scope.showSorter  = false
	$scope.loadResults = true
	$scope.niceDate    = niceDate

	api.getSearchResults(url,$scope.currentPage,$scope.filters).then(function (results){
		
		$scope.showFilters = false
		$scope.loadResults = false
		$scope.results     = results
		$scope.contentType = results.content_type_filters
		$scope.paginationConfig = {
			"pageCount"   : results.pages,
			"currentPage" : $scope.currentPage,
			"filters"     : $scope.filters,
			"order"       : $scope.order
		}

		console.log(results);

	})

	//Refresh scope when pagination page is selected
	$scope.$on('loading',function (){
		$scope.loadResults = true
	})

	$scope.$on('updatePage',function (e,results,curr){

		$scope.loadResults = false
		$scope.paginationConfig.currentPage = curr
		$scope.currentPage = curr
		$scope.results     = results

	})

	$scope.filterResults = function(term,key){

		$scope.loadResults = true

		var index = $scope.filters.indexOf(term);

		if(index == -1){
			$scope.filters.push(term)
			$scope.contentType[key].isApplied = true
		} else {
			$scope.filters.splice(index,1)
			$scope.contentType[key].isApplied = false
		}

		api.getSearchResults(url,1,$scope.filters,$scope.order).then(function (results){
			
			$scope.loadResults = false
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

		$scope.loadResults = true

		$scope.order     = order;
		$scope.orderName = orderName;
		
		api.getSearchResults(url,1,$scope.filters,$scope.order).then(function (results){
			
			$scope.loadResults = false
			$scope.results = results
			$scope.paginationConfig = {
				"pageCount"   : results.pages,
				"currentPage" : 1,
				"filters"     : $scope.filters,
				"order"       : $scope.order
			}
		})
	}

}])