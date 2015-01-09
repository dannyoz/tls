.controller('latesteditions',['$scope', '$sce','$location','$timeout','api','columns','niceDate',

	function ($scope, $sce, $location, $timeout, api, columns, niceDate) {

		$scope.ready   = false;
		$scope.loading = true;
		//var path = 'http://tls.localhost/grunt/DEV/app/templates/latest-editions/latest-editions.json';
		var path = window.location.href;

		// Convert an object to array
		$scope.objectToArray = function(obj) {
			
			var result = [];
			
			for (var k in obj) {
				var o = obj[k];
				result.push(o);
			}
			return result;
		}

		// Set scope variables of Current Edition
		$scope.setCurrentEditionObj = function(obj) {

			// Full object			
			$scope.latestEdition = obj.latest_edition;			
			// Edition sections articles				
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
			var subcriberPosts = $scope.objectToArray($scope.subscribersObj.articles);

			$scope.loading   = false;

			console.log(subcriberPosts);			
			
			// Devide columns for mansory layout
			columns.divide(subcriberPosts).then(function (cols) {

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

				api.getArticle(path).then(function (result){

					$scope.loading = false;
					$scope.dir = dir;
					$scope.pageTurn = true;

					if (dir == "prev") {
						
						$scope.oldPost  = $scope.currentEdition;
						$scope.setCurrentEditionObj(result);						

						$timeout(function(){
							$scope.pageTurn = false;
						},duration);

					} else {

						$scope.oldPost  = result;						
						$timeout(function(){
							$scope.pageTurn = false;
							$scope.setCurrentEditionObj(result);
						},duration);
					}					

				})
			}
		}
}])