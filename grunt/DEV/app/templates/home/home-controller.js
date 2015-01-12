.controller('home',['$scope', '$sce', 'api','columns','objToArr',function ($scope, $sce, api, columns, objToArr){

	var url = '/api/get_page/?id=' + home_page_id

	$scope.cards = ""

	api.getHomePage(url).then(function (result){

		console.log(result)

		$scope.page     = result.page
		$scope.featured = result.featured_article

		var cards = objToArr.convert(result.home_page_cards);

		columns.divide(cards).then(function (cols){

			$scope.col1  = cols.col1
			$scope.col2  = cols.col2
			$scope.col3  = cols.col3

		})

		console.log($scope.col3)

	});

	$scope.formatEmbed = function(html) {
		return $sce.trustAsHtml(html);
	}

}])