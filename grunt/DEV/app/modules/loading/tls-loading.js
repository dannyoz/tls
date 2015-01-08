.directive('tlsLoading',['$timeout','$interval',function ($timeout,$interval){
	return{
		restrict: "AE",
		templateUrl : "tls-loading.html",
		scope : {
			visible : '=tlsLoading'
		},
		link : function(scope,element){		
			

		    current = 0,
			next    = 1,
			delay   = 500,
			flipDel = 0;

			scope.currChar = "t"
			scope.nextChar = "l"

			scope.sequence = [{
				character : "t",
				direction : "h"
			},{
				character : "l",
				direction : "v"
			},{
				character : "s",
				direction : "h"
			},{
				character : "t",
				direction : "v"
			},{
				character : "l",
				direction : "h"
			},{
				character : "s",
				direction : "v"
			}]

			scope.$watch("visible",function (newVal,oldVal){

				if(newVal){

					rotate = $interval(function(){


						if (current < 5){
							current ++
							if(current == 5){
								next = 0
							} else {
								next ++
							}
						} else {
							current = 0
							next = 1
						}
						scope.currChar  = scope.sequence[current].character
						scope.nextChar  = scope.sequence[next].character
					
					},delay)

				} else {

					$interval.cancel(rotate)
				}
			})
		}
	}
}])