.controller('home',['$scope','api','columns',function ($scope, api, columns){

	var url = '/api/get_page/?id=' + home_page_id

	$scope.cards = ""

	api.getHomePage(url).then(function (result){

		console.log(result)

	})


	$scope.image = "hero"

}])