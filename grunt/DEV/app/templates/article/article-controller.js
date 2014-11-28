.controller('article',['$scope',function ($scope){


	$scope.pageTurn = false

	$scope.chooseArticle = function(dir){

		console.log(dir);

		if(dir == 'next'){
			$scope.pageTurn = true
		} else {

			$scope.pageTurn = false
		}

		
	}

}])