.controller('home',['$scope','api',function ($scope, api){

	$scope.cards = ""

	api.getCards().then(function(result){

		$scope.cards    = result.cards
		$scope.isLocked = true //class for locking content

		//Config object for tls-columns directive
		$scope.columns = {
			"template" : "home",
			"cards" : $scope.cards
		}

	})

	$scope.image = "hero"

}])