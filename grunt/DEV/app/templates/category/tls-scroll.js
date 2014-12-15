.directive('tlsScroll',function(){
	return{
		restrict : "A",
		scope :{
			status : "=tlsScroll"
		},
		link : function(scope,element){

			scope.$watch('status',function (newVal,oldVal){
				if(newVal == "on"){
					scope.scrollable = true
				} else {
					scope.scrollable = false
				}
			});

			window.onscroll =  function(){

				var wHeight    = (window.innerHeight || document.documentElement.clientHeight),
		            wYposition = (window.scrollY || window.pageYOffset || document.documentElement.scrollTop), // IE has no support for scrollY
		            docHeight  = document.body.clientHeight;
				
				if(scope.scrollable && wHeight + wYposition >= docHeight){
					scope.scrollable = false
					scope.status = "off"
					scope.$emit('loadNext');
				}

			}
		} 
	}
})