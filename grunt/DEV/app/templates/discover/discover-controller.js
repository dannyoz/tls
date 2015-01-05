.controller('discover',['$scope','$sce','api','columns',function ($scope,$sce,api,columns){

	var url = window.location.href;

	$scope.pageCount = 1;

	api.getArticle(url).then(function (result){
		$scope.page = result.page
	})

	api.getArticleList($scope.page).then(function (result){
		var posts = result.posts;

		console.log(result)

		columns.divide(posts).then(function (cols){
			$scope.col1  = cols.col1
			$scope.col2  = cols.col2
			$scope.col3  = cols.col3
			$scope.ready = true
			$scope.pageCount ++
		})
	})

}])