.controller('article',[
	'$scope',
	'$sce',
	'$location',
	'$timeout',
	'api',
	'mpu',
	'commentApi',
	'columns',
	'niceDate',
	'tealium',
	'books',
	function ($scope,$sce,$location,$timeout,api, mpu, commentApi,columns,niceDate,tealium,books){

		$scope.ready      = false;
		$scope.tealium    = tealium;
		$scope.loadingPg  = true;
		$scope.sce        = $sce;
		$scope.tags       = [];
		$scope.activeTags = [];
		$scope.turn       = false; 
		$scope.firstLoad  = true;
		$scope.successCommentMessage = false;
		$scope.errorCommentMessage = false;
		$scope.errorMessage = '';

		// Helper function to insert MPU's into related taxonomy_article_tags
		var insertMPU = function(posts) {

			var mpuObj = [{
					id: 'discover-single-1',
					order: 0
				},
				{
					id: 'discover-single-2',
					order: 4
				}];

			mpu.insert(posts, mpuObj[0]).then(function (result){
				posts = result;
			});

			mpu.insert(posts, mpuObj[1]).then(function (result){
				posts = result;
			});

			return posts;
		};

		//Get the json response from the api.js factory
		api.getArticle(window.location.href).then(function (result){

			$scope.post  = result.post
			$scope.prev  = result.previous_url
			$scope.next  = result.next_url
			$scope.ready = true;
			$scope.commentAuthor = result.post.commenter_information.comment_author;
			$scope.commentEmail = result.post.commenter_information.comment_author_email;
			$scope.commentContent = '';

			//restructure book format
			if($scope.post.custom_fields.books){
				books.parse($scope.post.custom_fields).then(function (returned){
					$scope.post.books = returned;
				});
			}

			// Get related content
			if($scope.post.taxonomy_article_tags && $scope.post.taxonomy_article_tags.length > 0){

				for (var i = 0; i<$scope.post.taxonomy_article_tags.length; i++){
					$scope.tags.push($scope.post.taxonomy_article_tags[i].title);
					$scope.activeTags.push({isApplied : false});
				};

				$scope.orginalList = $scope.tags;
				$scope.loadingTags = true;

				api.getRelatedContent($scope.tags).then(function (result){

					$scope.loadingTags = false
					
					var posts = result.posts;

					//Inserts Discover Ads to cards
					posts = insertMPU(posts);					

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

					//Inserts Discover Ads to cards
					posts = insertMPU(posts);

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

		$scope.addComment = function(author, email, content){

			if ($scope.successCommentMessage == true || $scope.errorCommentMessage == true) {
				$scope.successCommentMessage = false;
				$sce.errorCommentMessage = false;
				$scope.errorMessage = '';
			}

			commentApi.post('/api/submit_comment/?post_id='+$scope.post.id+'&name='+author+'&email='+email+'&content='+content)
				.then(function(data){
					$scope.successCommentMessage = true;
					var commentContent = document.getElementById('comment');
					commentContent.value = '';
				}, function (error) {
					$scope.errorCommentMessage = true;
					$scope.errorMessage = error;
				});

			if ($scope.success == true) {
				$scope.commentContent = '';
			}

		}

}])