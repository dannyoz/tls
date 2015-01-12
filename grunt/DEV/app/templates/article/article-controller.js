.controller('article',['$scope','$sce','$location','$timeout','api','columns','niceDate',function ($scope,$sce,$location,$timeout,api,columns,niceDate){

	$scope.sce        = $sce;
	$scope.tags       = [];
	$scope.activeTags = []; 
	$scope.firstLoad  = true;
	$scope.mpu        = "<script type=\"text/javascript\" src=\"http://ad.uk.doubleclick.net/adj/tls.thesundaytimes/mainhomepage/index;pos=mpu;content_type=sec;sz=300x250;'+RStag + cipsCookieValue +'tile=1;'+categoryValues+'ord='+randnum+'?\"></script>"

	//Get the json response from the api.js factory
	api.getArticle(window.location.href).then(function (result){
		$scope.post = result.post
		$scope.prev = result.previous_url
		$scope.next = result.next_url

		// Get related content
		if($scope.post.taxonomy_article_tags.length > 0){

			for (var i = 0; i<$scope.post.taxonomy_article_tags.length; i++){
				$scope.tags.push($scope.post.taxonomy_article_tags[i].title);
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

	$scope.emailLink = function(){

		var subject   = 'TLS article you may be interested in -' + $scope.post.title_plain,
			emailBody = $scope.post.url,
			emailPath = "mailto:&subject="+subject+"&body=" + emailBody

		return emailPath
	}

	$scope.socialLink = function(path,platform){

		var fbLink = "https://www.facebook.com/sharer/sharer.php?u=" + path,
			twLink = "https://twitter.com/home?status=" + path,
			link   = (platform == 'fb') ? fbLink : twLink,
			width  = 500,
			height = 300,
			params =   "scrollbars=no,
						toolbar=no,
						location=no,
						menubar=no,
						left=200,
						top=200,
						height="+height+",
						width="+width;

		window.open(link,"_blank",params);
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

					// console.log(path.split( '/' ));

					// $location.path(path.split( '/' )[3]);

					$timeout(function(){
						$scope.pageTurn = false
					},duration);

					
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

				for (var i = 0; i<$scope.post.taxonomy_article_tags.length; i++){
					$scope.tags.push($scope.post.taxonomy_article_tags[i].title);
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