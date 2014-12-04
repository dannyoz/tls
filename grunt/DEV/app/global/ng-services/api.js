.factory('api',['$http','$q', function ($http,$q){
	return {
		getCards : function(){
			var defer = $q.defer();

			$http.get(themeUrl + '/apis/cards.json').success(function (data){
				defer.resolve(data)
			})

			return defer.promise
		},
		getArticle : function(url){

			var path   = this.removeHashFrag(url),
				defer  = $q.defer(),
				prefix = this.checkQueries(path),
				query  = "json=1";

			$http.get(path+prefix+query).success(function (data){
				defer.resolve(data)
			})

			return defer.promise
		},
		getSearchResults : function(path,page,filters){

			var defer  = $q.defer(),
				filt   = (filters.length == 0) ? "" : "&category_name=["+filters+"]",
				prefix = this.checkQueries(path),
				query  = "json=1&paged="+page;

			$http.get(path+prefix+query+filt).success(function (data){
				defer.resolve(data)
			})

			return defer.promise
		
		},
		checkQueries : function(url){
			var prefix = (url.indexOf('?') > -1) ? "&" : "?"
			return prefix
		},
		removeHashFrag : function(url){

			var hashIndex = url.indexOf('#'),
				newPath   = url.slice(0,hashIndex);

			if(hashIndex > -1){
				return newPath
			} else{
				return url
			}
			
		}
	}
}])