.controller('latesteditions',['$scope', '$sce','$location','$timeout','api','columns','niceDate',

	function ($scope, $sce, $location, $timeout, api, columns, niceDate) {

		$scope.ready   = false;
		$scope.loading = true;

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
			var posts = $scope.subscribersObj.articles;

			$scope.loading   = false;

			
			// Devide columns for mansory layout
			columns.divide(posts).then(function (cols) {

				$scope.col1  = cols.col1;
				$scope.col2  = cols.col2;
				$scope.col3  = cols.col3;
				$scope.ready = true;			
			});

		});
}])