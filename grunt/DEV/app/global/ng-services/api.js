.factory('api',['$http','$q', function ($http,$q){
	return {
		getCards : function(){
			var defer = $q.defer();

			$http.get(themeUrl + '/apis/cards.json').success(function (data){
				defer.resolve(data)
			})

			return defer.promise
		},
		getArticle : function(path){
			var defer = $q.defer(),
				query = "?json=1";

			$http.get(path+query).success(function (data){
				defer.resolve(data)
			})

			return defer.promise
		},
		getSearchResults : function(path){
			var defer = $q.defer(),
				query = "&json=1";

			$http.get(path+query).success(function (data){
				defer.resolve(data)
			})

			return defer.promise
		}
	}
}])