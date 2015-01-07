.controller('latesteditions',['$scope','$sce','$location','$timeout','api','columns','niceDate',

	function ($scope,$sce,$location,$timeout,api,columns,niceDate) {

		api.getLatestEditions().then(function (result){			
				
			// Full object			
			$scope.latestEdition = result;	
			
			// Edition sections articles				
			$scope.currentEdition = $scope.latestEdition.content;			
			// Previous edition
			$scope.previousEdition = $scope.latestEdition.next_post_info;			
			// Next edition
			$scope.nextEdition = $scope.latestEdition.previous_post_info;

			// Public content
			$scope.publicObj = $scope.currentEdition.public;
			// Regulars content
			$scope.regularsObj = $scope.currentEdition.regulars;
			// Subscribers content
			$scope.subscribersObj = $scope.currentEdition.subscribers;

		});
}])