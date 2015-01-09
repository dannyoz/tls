.controller('home',['$scope','api','columns',function ($scope, api, columns){

	var url = '/api/get_page/?id=' + home_page_id

	$scope.cards = ""

	api.getHomePage(url).then(function (result){

		console.log(result)

		$scope.page     = result.page
		$scope.featured = result.featured_article

		columns.divide(result.home_page_cards).then(function (cols){

			$scope.col1  = cols.col1
			$scope.col2  = cols.col2
			$scope.col3  = cols.col3

		})

		console.log($scope.col3)

	})

}])