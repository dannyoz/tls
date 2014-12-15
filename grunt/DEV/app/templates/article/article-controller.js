.controller('article',['$scope','$sce','$location','$timeout','api','columns','niceDate',function ($scope,$sce,$location,$timeout,api,columns,niceDate){

	$scope.tags       = [];
	$scope.activeTags = []; 
	$scope.firstLoad  = true;

	//Get the json response from the api.js factory
	api.getArticle(window.location.href).then(function (result){
		$scope.post = result.post
		$scope.prev = result.previous_url
		$scope.next = result.next_url

		// Get related content
		if($scope.post.tags.length > 0){

			for (var i = 0; i<$scope.post.tags.length; i++){
				$scope.tags.push($scope.post.tags[i].title);
				$scope.activeTags.push({isApplied : false});
			};

			$scope.orginalList = $scope.tags
			$scope.loadingTags = true

			api.getRelatedContent($scope.tags).then(function (result){

				$scope.loadingTags = false
				
				var posts = result.posts;

				columns.divide(posts).then(function (cols){
					$scope.col1  = cols.col1
					$scope.col2  = cols.col2
					$scope.col3  = cols.col3
				})
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
				$scope.activeTags = []; 

				for (var i = 0; i<$scope.post.tags.length; i++){
					$scope.tags.push($scope.post.tags[i].title);
					$scope.activeTags.push({isApplied : false});
				};

				$scope.orginalList = $scope.tags

				api.getRelatedContent($scope.tags).then(function (result){
					var posts = result.posts;

					columns.divide(posts).then(function (cols){
						$scope.col1  = cols.col1
						$scope.col2  = cols.col2
						$scope.col3  = cols.col3
					})
				})
			})
		}
	}

	$scope.refineRelated = function(tag,i){

		if(!$scope.loadingTags){

			// Reset tags variable if no filters have been applied yet
			if($scope.firstLoad || $scope.tags == $scope.orginalList){
				$scope.firstLoad = false
				$scope.tags = [];
			}

			// Add or remove tag
			var index = $scope.tags.indexOf(tag);
			if(index == -1){
				$scope.tags.push(tag);
				$scope.activeTags[i].isApplied = true
			} else {
				$scope.tags.splice(index,1);
				$scope.activeTags[i].isApplied = false
			}

			if($scope.tags.length == 0){
				$scope.tags = $scope.orginalList
			}

			$scope.loadingTags = true
		
			api.getRelatedContent($scope.tags).then(function (result){

				$scope.loadingTags = false
				
				var posts = result.posts;

				columns.divide(posts).then(function (cols){
					$scope.col1  = cols.col1
					$scope.col2  = cols.col2
					$scope.col3  = cols.col3
				})
			})

			//console.log($scope.tags)

			return $scope.tags
		}
	}

}])