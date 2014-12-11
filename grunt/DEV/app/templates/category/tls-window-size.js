.directive('tlsWindowSize', function(){
	return {
		restrict: "A",
		scope:{
			size: "=tlsWindowSize"
		},
		link : function(scope){

			// Breakpoint vars
			var tabletBP  = 840,
				mobileBP  = 450;

			scope.viewport = function(size){

				if(size>=tabletBP){
					return 'desktop'
				} else if(size<tabletBP && size>=mobileBP){
					return 'tablet'
				} else if(size<mobileBP){
					return 'mobile'
				}
			}

			scope.size = scope.viewport(window.innerWidth);

			window.onresize = function(event) {

				scope.$apply(function(){
			    	var width  = window.innerWidth;
			    	scope.size = scope.viewport(width);
			    })

			};
		}
	}
})