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

			var defer  = $q.defer(),
				path   = this.removeHashFrag(url),
				prefix = this.checkQueries(path),
				query  = "json=1";

			$http.get(path+prefix+query).success(function (data){
				defer.resolve(data)
			})

			return defer.promise
		},
		getRelatedContent : function(tags){

			var defer  = $q.defer(),
				parse  = tags.toString(),
				path1  = "/tag/"+parse+"/?json=1",
				path2  = "/?tag="+parse+"&json=1",
				url    = (tags.length == 1)? path1 : path2;

			//expose url for testing
			defer.promise.url = url

			$http.get(url).success(function (data){
				defer.resolve(data)
			})

			return defer.promise
		},
		getSearchResults : function(path,page,filters,ord){

			var defer  = $q.defer(),
				filt   = (filters.length == 0) ? "" : "&category_name=["+filters+"]",
				prefix = this.checkQueries(path),
				order  = (!ord)? "" : "&orderby=date&order=" + ord,
				query  = "json=1&paged="+page;

			$http.get(path+prefix+query+filt+order).success(function (data){
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