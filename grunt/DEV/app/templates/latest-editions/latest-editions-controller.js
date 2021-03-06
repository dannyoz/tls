.controller('latesteditions',[
	'$scope', 
	'$sce',
	'$location',
	'$timeout',
	'api',
	'columns',
	'niceDate', 
	'objToArr',
	'tealium',
	'$window',
	function ($scope, $sce, $location, $timeout, api, columns, niceDate, objToArr, tealium, $window) {

		$scope.ready   = false;
		$scope.loading = true;
		$scope.tealium = tealium;
		var path = window.location.href;

		// Set scope variables of Current Edition
		$scope.setCurrentEditionObj = function(obj) {

			//console.log(obj);
			
			if (obj.hasOwnProperty('latest_edition')) {
				// Full object			
				$scope.latestEdition = obj.latest_edition;			
				// Edition sections articles				
				$scope.currentEdition = $scope.latestEdition.content;	
				// Previous edition
				$scope.nextEdition = $scope.latestEdition.next_post_info;	
				// Next edition
				$scope.previousEdition = $scope.latestEdition.previous_post_info;

			} 		

			// Public content
			$scope.publicObj = $scope.currentEdition.public;
			// Regulars content
			$scope.regularsObj = $scope.currentEdition.regulars;
			// Subscribers content
			$scope.subscribersObj = $scope.currentEdition.subscribers;
			var subcriberPosts = objToArr.convert($scope.subscribersObj.articles);

			$scope.loading   = false;

			
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

			//console.log(result);		
		});


		$scope.chooseEdition = function(dir, path, title) {

			//Only turn page if path is defined
			if (path) {

				var duration = 400;
				$scope.loading = true;

				api.getArticle(path).then(function (result) {

					$scope.loading = false;
					$scope.dir = dir;
					$scope.pageTurn = true;

					if (dir == "prev") {
						
						$scope.oldPost  = $scope.currentEdition;
						$scope.setCurrentEditionObj(result);						

						$timeout(function(){
							$scope.pageTurn = false;
						},duration);

						tealium.paging('previous edition',title);

					} else {

						$scope.oldPost  = result;						
						$timeout(function(){
							$scope.pageTurn = false;
							$scope.setCurrentEditionObj(result);
						},duration);

						tealium.paging('next edition',title);
					}					

				})
			}
		}

		$scope.nextPrevEdition = function(direction, title, url) {

			tealium.paging(direction + ' edition', title);		
			// If url is passed then redirect page to that page				
			if (url) {					
				$window.location.href = url;				
			}
			
		};
}])