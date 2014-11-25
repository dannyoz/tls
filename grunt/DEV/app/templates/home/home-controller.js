.controller('home',['$scope','api',function ($scope, api){

	api.getCards().then(function(result){
		$scope.cards = result.cards

		//Config object for tls-columns directive
		$scope.columns = {
			"template" : "home",
			"cards" : $scope.cards
		}

	})

	$scope.image = "hero"

}])