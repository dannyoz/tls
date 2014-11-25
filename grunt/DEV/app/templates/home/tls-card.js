.directive('tlsCard',function(){
	return{
		restrict:"A",
		templateUrl : "ng-views/tls-card.html",
		scope : {
			data : "=tlsCard"
		}
	}
})