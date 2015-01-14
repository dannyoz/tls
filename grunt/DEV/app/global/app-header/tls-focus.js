.directive('tlsFocus', function(){
	return{
		restrict: "A",
		scope : {
			bool : '=tlsFocus'
		},
		link : function(scope,element){
			scope.$watch("bool", function(value) {
		        if(value){
		        	element[0].focus();
		        	scope.placeholder = "";
		        }
		    });
		}
	}
})