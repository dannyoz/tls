.controller('latesteditions',['$scope', '$sce','$location','$timeout','api','columns','niceDate',

	function ($scope, $sce, $location, $timeout, api, columns, niceDate) {

		$scope.ready   = false;
		$scope.loading = true;
		var path = 'http://tls.localhost/grunt/DEV/app/templates/latest-editions/latest-editions.json';
		//var path = window.location.href;

		// Set scope variables of Current Edition
		$scope.setCurrentEditionObj = function(obj) {

			// Full object			
			$scope.latestEdition = obj;			
			// Edition sections articles				
			//$scope.currentEdition = $scope.latestEdition.latest_edition.content;	
			$scope.currentEdition = $scope.latestEdition.content;	
			// Previous edition
			$scope.nextEdition = $scope.latestEdition.next_post_info;			
			// // Next edition
			$scope.previousEdition = $scope.latestEdition.previous_post_info;

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
		}

		// API request
		api.getArticle(path).then(function (result) {		
			$scope.setCurrentEditionObj(result);			
		});

		$scope.chooseEdition = function(dir, path){

			//Only turn page if path is defined
			if (path) {

				var duration = 400;
				$scope.loading = true;

				api.getLatestEditions(path).then(function (result){

					$scope.loading = false;
					$scope.setCurrentEditionObj(result);

				})
			}
		}
}])