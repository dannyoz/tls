.controller('category', [
	'$scope',
	'$sce', 
	'$timeout', 	
	'api', 
	'mpu',
	'columns',
	'tealium', 
	function ($scope,$sce,$timeout,api,mpu,columns,tealium) {

		var href   = window.location.href,
			parent = href.indexOf('/blogs/') > -1,
			url    = (parent) ?  '/?post_type[post]' : href;

		$scope.ready       = false;
		$scope.page        = 1;
		$scope.loading     = true;
		$scope.scrollState = "off";
		$scope.infinite    = false;
		$scope.loadMsg     = "";
		$scope.tealium     = tealium;

		$scope.$on('loadNext',function(){
			$scope.loadMore();
		})

		api.getArticle(href).then(function (result){

			$scope.loading   = false;
			$scope.title     = (result.category)? result.category.title : 'blog'
			$scope.content   = result
			$scope.pageCount = result.pages
			$scope.firstPost = result.featured_post;
			var posts        = result.posts;

			//Inserts Blogs Ads to cards
			var mpuObj = [{
					id: 'blogs-1',
					order: 4,
					type: 'mpu'
				},
				{
					id: 'blogs-2',
					order: 9,
					type: 'mpu'
				}];

			mpu.insert(posts, mpuObj[0]).then(function (result){
				posts = result;
			});	

			mpu.insert(posts, mpuObj[1]).then(function (result){
				posts = result;
			});		

			columns.divide(posts).then(function (cols){
				$scope.col1  = cols.col1
				$scope.col2  = cols.col2
				$scope.col3  = cols.col3
				$scope.ready = true
				$scope.page ++
			})
		})

		$scope.formatEmbed = function(html){

			return $sce.trustAsHtml(html)
		}

		$scope.loadMore = function(){

			$scope.scrollState = "on";
			$scope.infinite    = true;
			$scope.infLoading  = true;

			if($scope.pageCount > ($scope.page-1)){

				api.getArticle(url,$scope.page).then(function (result){

					var posts = result.posts;
					$scope.scrollState = "on";

					columns.divide(posts).then(function (cols){

						$scope.col1[0] = $scope.col1[0].concat(cols.col2[0]);
						$scope.col2[0] = $scope.col2[0].concat(cols.col2[0]);
						$scope.col2[1] = $scope.col2[1].concat(cols.col2[1]);
						$scope.col3[0] = $scope.col3[0].concat(cols.col3[0]);
						$scope.col3[1] = $scope.col3[1].concat(cols.col3[1]);
						$scope.col3[2] = $scope.col3[2].concat(cols.col3[2]);

						$scope.page ++
						$scope.infLoading = false;
					})
				})
			} else {
				$scope.scrollState = "off";
				$scope.infLoading  = false;

				$scope.$apply(function(){
					$scope.loadMsg = "End of results in " + $scope.title;
				});
			}
		}

}])