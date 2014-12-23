.directive('tlsLoading',['$timeout','$interval',function ($timeout,$interval){
	return{
		restrict: "AE",
		templateUrl : "tls-loading.html",
		scope : {
			visible : '=tlsLoading'
		},
		link : function(scope,element){		
			
			scope.dots     = [1,2,3,4,5];
			
			var current = 0,
				next    = 1,
				delay   = 600,
				flipDel = 200;

			scope.currChar = "t"
			scope.nextChar = "l"
			scope.direction  = 'h'

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

			$interval(function(){

				scope.isFlipping = true
				$timeout(function(){

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

					scope.direction = scope.sequence[current].direction
					scope.currChar  = scope.sequence[current].character
					scope.nextChar  = scope.sequence[next].character

					scope.isFlipping = false
				},flipDel);
			
			},delay)
		}
	}
}])