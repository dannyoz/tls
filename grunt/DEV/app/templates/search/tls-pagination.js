.directive('tlsPagination',[ 'api', function (api){
	return{
		restrict:"A",
		templateUrl :  "tls-pagination.html",
		scope : {
			config : '=tlsPagination',
		},
		link : function(scope){

			//Define an array for the ng-repeat
			scope.pages = [];
			angular.forEach(scope.config.pageCount, function(){
				scope.pages.push($index)
			})

			scope.switchPage = function(i){

				var u = window.location.href,
					f = scope.config.filters,
					o = scope.config.order,
					d = scope.config.dateRange;
 
				scope.$emit('loading')

				api.getSearchResults(u,i,f,o,d).then(function (results){
					scope.$emit('updatePage',results,i)
				})

			}
		}
	}
}])