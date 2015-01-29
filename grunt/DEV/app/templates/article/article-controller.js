.controller('article',[
	'$scope',
	'$sce',
	'$location',
	'$timeout',
	'api',
	'commentApi',
	'columns',
	'niceDate',
	'tealium',
	function ($scope,$sce,$location,$timeout,api,commentApi,columns,niceDate,tealium){

	$scope.ready      = false;
	$scope.tealium    = tealium;
	$scope.loadingPg  = true;
	$scope.sce        = $sce;
	$scope.tags       = [];
	$scope.activeTags = [];
	$scope.turn       = false; 
	$scope.firstLoad  = true;
	$scope.mpu        = "<script type=\"text/javascript\" src=\"http://ad.uk.doubleclick.net/adj/tls.thesundaytimes/mainhomepage/index;pos=mpu;content_type=sec;sz=300x250;'+RStag + cipsCookieValue +'tile=1;'+categoryValues+'ord='+randnum+'?\"></script>"

	//Get the json response from the api.js factory
	api.getArticle(window.location.href).then(function (result){

		$scope.post  = result.post
		$scope.prev  = result.previous_url
		$scope.next  = result.next_url
		$scope.ready = true;

		// Get related content
		if($scope.post.taxonomy_article_tags && $scope.post.taxonomy_article_tags.length > 0){

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

		$scope.loadingPg = false;
		console.log(result)

	})

	$scope.format = function(date){
		return niceDate.format(date);
	}

	$scope.printPage = function(){

		if(window.print){
			window.print();
			tealium.engagement('print');
		}
		
	}

	$scope.emailLink = function(){

		var subject   = 'TLS article',
			emailBody = 'I thought you might be interested in this article from the Times Literary Supplement ' + $scope.post.url,
			emailPath = "mailto:&subject="+subject+"&body=" + emailBody
		
		return emailPath

	}

	$scope.socialLink = function(path,platform){

		var fbLink = "https://www.facebook.com/sharer/sharer.php?u=" + path,
			twLink = "https://twitter.com/home?status=" + path,
			link   = (platform == 'facebook') ? fbLink : twLink,
			params =   "scrollbars=no,toolbar=no,location=no,menubar=no,left=200,top=200,height=300,width=500";

		window.open(link,"_blank",params);
		tealium.socialLink(platform);

	}

	$scope.chooseArticle = function(dir,path,pageTitle){

		//Only turn page if path is defined an turn var is set to true
		if(path && $scope.turn){

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

					tealium.paging('previous article',pageTitle);

					
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

					tealium.paging('next article',pageTitle);

				}

				$scope.tags = [];
				$scope.activeTags = [];

				if($scope.post.taxonomy_article_tags){ 

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
				}
			})
		} else if (path && !$scope.turn){

			$scope.loadingPg = true;
			location.replace(path);
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

	$scope.addComment = function(){

		console.log('/wp-comments-post.php')

		commentApi.post('http://tls.localhost/wp-comments-post.php', 'comment=Hello+this+is+a+test+comment&comment_post_ID=1&_wp_unfiltered_html_comment=c35d80192a')
	}

}])