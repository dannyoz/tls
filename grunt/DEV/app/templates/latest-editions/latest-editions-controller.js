.controller('latesteditions',['$scope','$sce','$location','$timeout','api','columns','niceDate',

	function ($scope,$sce,$location,$timeout,api,columns,niceDate) {

		api.getLatestEditions().then(function (result){			
			console.log(result);
		})
}])