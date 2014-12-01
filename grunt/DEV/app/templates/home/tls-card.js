.directive('tlsCard',function(){
	return{
		restrict:"A",
		templateUrl : themeUrl + "/ng-views/tls-card.html",
		scope : {
			data : "=tlsCard"
		}
	}
})