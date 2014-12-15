.controller('category', ['$scope','$sce', '$timeout', 'api', 'columns', function ($scope,$sce,$timeout,api,columns){

	var url = window.location.href;

	$scope.ready       = false;
	$scope.page        = 1;
	$scope.loading     = true;
	$scope.scrollState = "off";
	$scope.infinite    = false;
	$scope.loadMsg     = "";

	$scope.$on('loadNext',function(){
		$scope.loadMore();
	})

	api.getArticle(url).then(function (result){

		$scope.loading = false;
		$scope.title   = result.category.title
		$scope.pageCount = result.pages

		var posts = result.posts;

		console.log(result)

		columns.divide(posts).then(function (cols){
			$scope.col1  = cols.col1
			$scope.col2  = cols.col2
			$scope.col3  = cols.col3
			$scope.ready = true
			$scope.page ++
		})
	})

	$scope.loadMore = function(){

		$scope.scrollState = "on";
		$scope.infinite    = true;
		$scope.infLoading  = true;

		if($scope.pageCount > ($scope.page-1)){

			api.getArticle(url,$scope.page).then(function (result){

				var posts = result.posts;
				$scope.scrollState = "on";

				columns.divide(posts).then(function (cols){

					$scope.col1[0] = $scope.col1[0].concat(cols.col2[0]);
					$scope.col2[0] = $scope.col2[0].concat(cols.col2[0]);
					$scope.col2[1] = $scope.col2[1].concat(cols.col2[1]);
					$scope.col3[0] = $scope.col3[0].concat(cols.col3[0]);
					$scope.col3[1] = $scope.col3[1].concat(cols.col3[1]);
					$scope.col3[2] = $scope.col3[2].concat(cols.col3[2]);

					$scope.page ++
					$scope.infLoading = false;
				})
			})
		} else {
			$scope.scrollState = "off";
			$scope.infLoading  = false;

			$scope.$apply(function(){
				$scope.loadMsg = "End of results in " + $scope.title;
			});
		}
	}

}])