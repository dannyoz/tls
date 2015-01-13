.directive('tlsCard',['$sce',function ($sce) {
	return{
		restrict:"A",
		templateUrl : "tls-card.html",
		scope : {
			data : "=tlsCard"
		},
		link : function(scope){
			
			console.log(scope.data);
			
			// Type of card (Object or Array)
			var cardType = Object.prototype.toString.call(scope.data);

			// Card is array, must be blog items
			if(cardType === '[object Array]' && cardType.length > 0) {
				scope.data.type = 'blog';
			} 
		}
	}
}])