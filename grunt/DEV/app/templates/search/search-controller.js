.controller('search',["$scope",'$sce','$timeout','api','niceDate', function ($scope,$sce,$timeout,api,niceDate) {

	//Set default vars
	var url = window.location.href;
	$scope.filters         = []
	$scope.taxonomyFilters = []
	$scope.currentPage     = 1
	$scope.dateRange       = ""
	$scope.orderName       = "Newest"
	$scope.order           = "ASC"
	$scope.showSorter      = false
	$scope.loadResults     = true
	$scope.niceDate        = niceDate

	api.getSearchResults(
			url,
			$scope.currentPage,
			$scope.filters,
			$scope.order,
			$scope.dateRange
		)
		.then(function (results){
		
			$scope.showFilters = false
			$scope.loadResults = false
			$scope.results     = results
			$scope.contentType = results.content_type_filters
			$scope.sections    = results.articles_sections
			$scope.dateRanges  = results.date_filters
			$scope.paginationConfig = {
				"pageCount"   : results.pages,
				"currentPage" : $scope.currentPage,
				"filters"     : $scope.filters,
				"order"       : $scope.order,
				"dateRange"   : $scope.dateRange
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

		api.getSearchResults(
				url,
				1,
				$scope.filters,
				$scope.order,
				$scope.dateRange
			)
			.then(function (results){
			
				$scope.loadResults = false
				$scope.results = results
				$scope.paginationConfig = {
					"pageCount"   : results.pages,
					"currentPage" : 1,
					"filters"     : $scope.filters,
					"order"       : $scope.order,
					"dateRange"   : $scope.dateRange
			}
		})
	}

	$scope.contentFilter = function(term,query,key,type){

		console.log(term,query,key)

		var list = (type == 'content') ? $scope.contentType : $scope.sections

		if(query){

			var index = $scope.filters.indexOf(query);

			if(index == -1){
				$scope.filters.push(query)
				list[key].isApplied = true
			} else {
				$scope.filters.splice(index,1)
				list[key].isApplied = false
			}

			$scope.loadResults = true
			api.getSearchResults(
					url,
					1,
					$scope.filters,
					$scope.order,
					$scope.dateRange
				)
				.then(function (results){
				
					$scope.loadResults = false
					$scope.results = results
					$scope.paginationConfig = {
						"pageCount"   : results.pages,
						"currentPage" : 1,
						"filters"     : $scope.filters,
						"order"       : $scope.order,
						"dateRange"   : $scope.dateRange
				}
			})
		}
	}

	$scope.dateRangeFilter = function(range,name){

		var $this = $scope.dateRanges[name]

		if($this.isApplied){
			$scope.dateRange = "";
		} else {
			$scope.dateRange = range;
		}

		angular.forEach($scope.dateRanges, function (obj,val){
			if(val != name){
				obj.isApplied = false
			}
		});

		$scope.dateRanges[name].isApplied = !$scope.dateRanges[name].isApplied
		$scope.loadResults = true

		api.getSearchResults(
				url,
				1,
				$scope.filters,
				$scope.order,
				$scope.dateRange
			)
			.then(function (results){
			
				$scope.loadResults = false
				$scope.results = results
				$scope.paginationConfig = {
					"pageCount"   : results.pages,
					"currentPage" : 1,
					"filters"     : $scope.filters,
					"order"       : $scope.order,
					"dateRange"   : $scope.dateRange
			}
		})
	}

	$scope.orderResults = function(order,orderName){

		$scope.loadResults = true

		$scope.order     = order;
		$scope.orderName = orderName;
		
		api.getSearchResults(
				url,
				1,
				$scope.filters,
				$scope.order,
				$scope.dateRange)
			.then(function (results){
				
				$scope.loadResults = false
				$scope.results = results
				$scope.paginationConfig = {
					"pageCount"   : results.pages,
					"currentPage" : 1,
					"filters"     : $scope.filters,
					"order"       : $scope.order,
					"dateRange"   : $scope.dateRange
			}
		})
	}

}])