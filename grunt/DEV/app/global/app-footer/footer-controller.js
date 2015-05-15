.controller('footer',['$scope','tealium', '$window', function ($scope, tealium, $window){

	$scope.tealium = tealium;

	$scope.classifieds = function(pdf){
		tealium.classified(pdf);
	}

	$scope.archive = function(url) {

		tealium.archive();

		// If url is passed then redirect page to that page				
		if (url) {					
			$window.location.href = $window.location.href + url;				
		}
	}

}])