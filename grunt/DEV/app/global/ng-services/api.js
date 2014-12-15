.factory('api',['$http','$q','$timeout', function ($http,$q,$timeout){

	var delay  = 1500;

	return {
		getCards : function(){
			var defer = $q.defer();

			$http.get(themeUrl + '/apis/cards.json').success(function (data){
				defer.resolve(data)
			})

			return defer.promise
		},
		getArticle : function(url,pg){

			var defer  = $q.defer(),
				path   = this.removeHashFrag(url),
				prefix = this.checkQueries(path),
				page   = (pg) ? "&paged=" + pg : "",
				query  = "json=1";

			//expose url for testing
			defer.promise.url = path+prefix+query+page

			$http.get(path+prefix+query+page).success(function (data){
				//simulate server delay
				$timeout(function(){
					defer.resolve(data)
				},delay)
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

				//simulate server delay
				$timeout(function(){
					defer.resolve(data)
				},delay)
				
			})

			return defer.promise
		},
		getSearchResults : function(path,page,filters,ord){

			var defer   = $q.defer(),
				page    = (!page) ? 1 : page,
				filters = (!filters) ? [] : filters,
				filt    = (filters.length == 0) ? "" : "&category_name=["+filters+"]",
				prefix  = this.checkQueries(path),
				order   = (!ord)? "" : "&orderby=date&order=" + ord,
				query   = "json=1&paged="+page;

			//expose url for testing
			defer.promise.url = path+prefix+query+filt+order

			$http.get(path+prefix+query+filt+order).success(function (data){
				//simulate server delay
				$timeout(function(){
					defer.resolve(data)
				},delay)
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