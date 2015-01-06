.controller('latesteditions',['$scope','$sce','$location','$timeout','api','columns','niceDate',

	function ($scope,$sce,$location,$timeout,api,columns,niceDate) {

		api.getLatestEditions().then(function (result){			
						
			$scope.currentEdition = result;
			$scope.previousEdition = result.next_post_info;
			$scope.nextEdition = result.previous_post_info;
		})
}])