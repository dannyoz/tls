.controller('article',['$scope','$sce','$location','$timeout','api','niceDate',function ($scope,$sce,$location,$timeout,api,niceDate){

	//Get the json response from the api.js factory
	api.getArticle(window.location.href).then(function (result){
		$scope.post = result.post
		$scope.prev = result.previous_url
		$scope.next = result.next_url

		console.log(result)
	})

	$scope.format = function(date){
		return niceDate.format(date);
	}

	$scope.chooseArticle = function(dir,path){

		//Only turn page if path is defined
		if(path){

			var duration = 400;
			$scope.loading = true

			api.getArticle(path).then(function (result){

				$scope.loading = false

				if(dir == 'prev'){

					$scope.oldPost  = $scope.post;
					$scope.post     = result.post
					$scope.prev     = result.previous_url
					$scope.next     = result.next_url
					$scope.dir      = dir
					$scope.pageTurn = true

					$timeout(function(){
						$scope.pageTurn = false
					},duration)
				}

				if(dir == 'next'){

					$scope.oldPost  = result.post;
					$scope.dir      = dir
					$scope.pageTurn = true

					$timeout(function(){
						$scope.pageTurn = false
						$scope.post     = result.post
						$scope.prev     = result.previous_url
						$scope.next     = result.next_url
					},duration)
				}
			})
		}
	}

}])