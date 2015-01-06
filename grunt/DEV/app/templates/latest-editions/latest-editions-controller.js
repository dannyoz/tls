.controller('latesteditions',['$scope','$sce','$location','$timeout','api','columns','niceDate',

	function ($scope,$sce,$location,$timeout,api,columns,niceDate) {

		api.getLatestEditions().then(function (result){			
						
			console.log(result);
			
			// Edition sections articles				
			$scope.currentEdition = result.content;
			// Previous edition
			$scope.previousEdition = result.next_post_info;
			// Next edition
			$scope.nextEdition = result.previous_post_info;

			$scope.prev = $scope.previousEdition.url;
			$scope.next = $scope.nextEdition.url;

		})
}])