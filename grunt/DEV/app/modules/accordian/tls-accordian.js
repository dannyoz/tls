.directive('tlsAccordian', function () {
	return {
		restrict : "A",
		templateUrl : "tls-accordian.html",
		scope : {
			items : "=tlsAccordian"
		},
		link : function (scope){

			// angular.forEach(scope.items,function(obj,i){
			// 	var bool = (i == 0)? true : false;
			// 	obj.isOpen = bool
			// })

			scope.toggleOpen = function (i){

				var newState = (scope.items[i].isOpen == true)? false : true;
				angular.forEach(scope.items,function(obj){
					obj.isOpen = false
				})
				scope.items[i].isOpen = newState;
				
			}
		}
	}
})