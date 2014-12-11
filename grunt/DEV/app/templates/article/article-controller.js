.controller('article',['$scope','$sce','$location','$timeout','api','niceDate',function ($scope,$sce,$location,$timeout,api,niceDate){

	$scope.tags      = [];
	$scope.firstLoad = true

	//Get the json response from the api.js factory
	api.getArticle(window.location.href).then(function (result){
		$scope.post = result.post
		$scope.prev = result.previous_url
		$scope.next = result.next_url

		// Get related content
		if($scope.post.tags.length > 0){

			angular.forEach($scope.post.tags, function (tag){
				$scope.tags.push(tag.title)
			});

			api.getRelatedContent($scope.tags).then(function (result){
				$scope.related = result.posts

				console.log(result)
			})

		}

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

				$scope.tags = [];

				angular.forEach($scope.post.tags, function (tag){
					$scope.tags.push(tag.title)
				});

				api.getRelatedContent($scope.tags).then(function (result){
					$scope.related = result.posts
				})
			})
		}
	}

	$scope.refineRelated = function(tag){

		// Reset tags variable if no filters have been applied yet
		if($scope.firstLoad){
			$scope.firstLoad = false
			$scope.tags = [];
		}

		// Add or remove tag
		var index = $scope.tags.indexOf(tag);
		if(index == -1){
			$scope.tags.push(tag)
		} else {
			$scope.tags.splice(index,1)
		}

		api.getRelatedContent($scope.tags).then(function (result){
			$scope.related = result.posts
		})

		return $scope.tags
	}

}])