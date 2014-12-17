.controller('footerpages',['$scope','$sce','api','niceDate', function ($scope,$sce,api,niceDate){

	api.getArticle(window.location.href).then(function (result){
		$scope.page = result.page
		console.log(result)
	});

	$scope.format = function(date){
		return niceDate.format(date);
	}

}])