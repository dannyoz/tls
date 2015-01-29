.controller('footerpages',[
	'$scope',
	'$sce',
	'api',
	'niceDate', 
	'tealium',
	function ($scope,$sce,api,niceDate,tealium){

	$scope.ready   = false;
	$scope.tealium = tealium;

	api.getArticle(window.location.href).then(function (result){
		$scope.page  = result.page
		$scope.ready = true;
		console.log(result)

		if(result.page_template_slug == "template-faqs.php"){

			api.getFaqs().then(function (result){
				console.log(result)

				$scope.page.accordion_items = [];

				angular.forEach(result.posts, function(data){
					var obj = {};

					obj.heading = data.title
					obj.content = data.content
					
					$scope.page.accordion_items.push(obj);
				})
			})
		}
	});

	$scope.format = function(date){
		return niceDate.format(date);
	}

}])