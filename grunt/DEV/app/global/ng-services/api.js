.factory('api',['$http','$q', function ($http,$q){
	return {
		getCards : function(){
			var defer = $q.defer();

			$http.get('apis/cards.json').success(function (data){
				defer.resolve(data)
			})

			return defer.promise
		}
	}
}])