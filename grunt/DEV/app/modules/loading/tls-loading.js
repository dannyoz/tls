.directive('tlsLoading',['$timeout',function ($timeout){
	return{
		restrict: "AE",
		templateUrl : "tls-loading.html",
		scope : {
			visible : '=tlsLoading'
		},
		link : function(scope,element){		
			scope.dots = [1,2,3,4,5];
		}
	}
}])