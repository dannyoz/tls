.directive('tlsAccordianColumn', function () {
	return {
		restrict : "A",
		templateUrl : "tls-accordian-column.html",
		scope : {
			items : "=tlsAccordianColumn"
		},
		link : function (scope){

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