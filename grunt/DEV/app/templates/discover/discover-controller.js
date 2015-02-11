.controller('discover',[
	'$scope',
	'$sce',
	'api',
	'mpu',
	'columns',
	'tealium',
	function ($scope,$sce,api, mpu, columns,tealium){

		var url = window.location.href;

		$scope.test = true
		$scope.ready       = false;
		$scope.pageNumber  = 1;
		$scope.loading     = true;
		$scope.scrollState = "off";
		$scope.infinite    = false;
		$scope.loadMsg     = "";
		$scope.mpuPosition = 4;
		$scope.tealium     = tealium;

		$scope.$on('loadNext',function(){
			$scope.loadMore();
		})

		api.getArticle(url).then(function (result){
			
			$scope.page      = result.page
			$scope.loading   = false;
			$scope.pageCount = result.pages

			//Inserts Discover Ads to cards
			var mpuObj = [
				{
					id: 'discover-1',
					order: 4
				},
				{
					id: 'discover-2',
					order: 10
				}
			];

			mpu.insert(result.top_articles, mpuObj[0]).then(function (result){
				result.top_articles = result;
			});

			mpu.insert(result.top_articles, mpuObj[1]).then(function (result){
				result.top_articles = result;
			});

			columns.divide(result.top_articles).then(function (cols){
				$scope.topCol1  = cols.col1
				$scope.topCol2  = cols.col2
				$scope.topCol3  = cols.col3

				//console.log($scope.topCol3)
			})


			columns.divide(result.posts).then(function (cols){
				$scope.col1  = cols.col1
				$scope.col2  = cols.col2
				$scope.col3  = cols.col3
				$scope.ready = true
			})

			console.log(result)

		})

		$scope.truncate = function(str){

			if (!! str) {
				var trunc    = str.substring(0,200),
					combined = trunc + " [...]"

				return combined
			}
			
		}

		$scope.loadMore = function(){

			$scope.scrollState = "on";
			$scope.infinite    = true;
			$scope.infLoading  = true;

			if($scope.pageCount > ($scope.pageNumber-1)){

				if($scope.pageNumber == 1){

					$scope.pageNumber ++

				} else {

					api.getArticleList($scope.pageNumber).then(function (result){

						var posts = result.posts;
						$scope.scrollState = "on";

						console.log(posts);

						columns.divide(posts).then(function (cols){

							$scope.col1[0] = $scope.col1[0].concat(cols.col2[0]);
							$scope.col2[0] = $scope.col2[0].concat(cols.col2[0]);
							$scope.col2[1] = $scope.col2[1].concat(cols.col2[1]);
							$scope.col3[0] = $scope.col3[0].concat(cols.col3[0]);
							$scope.col3[1] = $scope.col3[1].concat(cols.col3[1]);
							$scope.col3[2] = $scope.col3[2].concat(cols.col3[2]);

							$scope.pageNumber ++
							$scope.infLoading = false;
						})
					})
				}

			} else {
				
				$scope.scrollState = "off";
				$scope.infLoading  = false;

				$scope.$apply(function(){
					$scope.loadMsg = "End of results in " + $scope.page.title;
				});
			}
		}

}])