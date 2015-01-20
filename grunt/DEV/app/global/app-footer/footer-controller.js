.controller('footer',['$scope','tealium', function ($scope, tealium){

	$scope.tealium = tealium;

	$scope.classifieds = function(pdf){
		tealium.classified(pdf);
	}

}])