.controller('discover',['$scope','$sce','api','columns',function ($scope,$sce,api,columns){

	var url = window.location.href;

	$scope.ready       = false;
	$scope.pageNumber  = 1;
	$scope.loading     = true;
	$scope.scrollState = "off";
	$scope.infinite    = false;
	$scope.loadMsg     = "";

	$scope.$on('loadNext',function(){
		$scope.loadMore();
	})

	api.getArticle(url).then(function (result){
		
		$scope.page      = result.page
		$scope.loading   = false;
		$scope.pageCount = result.pages

		var posts = result.posts;

		console.log(result)

		columns.divide(posts).then(function (cols){
			$scope.col1  = cols.col1
			$scope.col2  = cols.col2
			$scope.col3  = cols.col3
			$scope.ready = true
			$scope.pageNumber ++
		})
	})

	$scope.loadMore = function(){

		$scope.scrollState = "on";
		$scope.infinite    = true;
		$scope.infLoading  = true;

		if($scope.pageCount > ($scope.pageNumber-1)){

			api.getArticleList($scope.pageNumber).then(function (result){

				var posts = result.posts;
				$scope.scrollState = "on";

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
		} else {
			$scope.scrollState = "off";
			$scope.infLoading  = false;

			$scope.$apply(function(){
				$scope.loadMsg = "End of results in " + $scope.page.title;
			});
		}
	}

}])