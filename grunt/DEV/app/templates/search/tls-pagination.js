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

				api.getSearchResults(window.location.href,i,scope.config.filters).then(function (results){
					scope.$emit('updatePage',results,i)
				})

			}
		}
	}
}])