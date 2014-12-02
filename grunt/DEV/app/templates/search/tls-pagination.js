.directive('tlsPagination',[ 'api', function (api){
	return{
		restrict:"A",
		templateUrl :  themeUrl + "/ng-views/tls-pagination.html",
		scope : {
			config : '=tlsPagination',
		},
		link : function(scope){

			//Define an array for the ng-repeat
			scope.pages = [];
			for (var i = 0; i<scope.config.pageCount; i++){
				scope.pages.push(i)
			}

			scope.switchPage = function(i){

				api.getSearchResults(window.location.href,i).then(function (results){
					scope.$emit('updatePage',results,i)
				})

			}
		}
	}
}])