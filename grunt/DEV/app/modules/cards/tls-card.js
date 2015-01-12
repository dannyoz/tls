.directive('tlsCard',function(){
	return{
		restrict:"A",
		templateUrl : "tls-card.html",
		scope : {
			data : "=tlsCard"
		},
		link : function(scope){
			console.log(scope.data)
		}
	}
})