.controller('home',[
	'$scope', 
	'$sce', 
	'api',
	'mpu',
	'columns',
	'objToArr',
	function ($scope, $sce, api, mpu, columns, objToArr){

	var url = '/api/get_page/?id=' + home_page_id

	$scope.cards = ""

	api.getHomePage(url).then(function (result){

		console.log(result);	

		$scope.page     = result.page
		$scope.featured = result.featured_article

		var cards = objToArr.convert(result.home_page_cards);

		//Inserts first advert to cards
		// mpu.insert(cards,4).then(function (result){
		// 	cards = result
		// })

		//Inserts second advert to cards
		// mpu.insert(cards,10).then(function (result){
		// 	cards = result
		// })

		columns.divide(cards).then(function (cols){

			$scope.col1  = cols.col1
			$scope.col2  = cols.col2
			$scope.col3  = cols.col3
		})

	});

	$scope.formatEmbed = function(html) {
		return $sce.trustAsHtml(html);
	}

}])