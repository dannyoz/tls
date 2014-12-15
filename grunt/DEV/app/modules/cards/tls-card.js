.directive('tlsCard',function(){
	return{
		restrict:"A",
		templateUrl : "tls-card.html",
		scope : {
			data : "=tlsCard"
		}
	}
})