.factory('api',['$http','$q','$timeout', function ($http,$q,$timeout){

    var delay  = 1;

    return {
        getHomePage : function(url){

            var defer  = $q.defer();

            $http.get(url).success(function (data){
                //simulate server delay
                $timeout(function(){
                    defer.resolve(data)
                },delay)
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
        getPagedPosts : function(section,pg){

            var defer  = $q.defer(),
                prefix = "/api/get_posts/?post_type=tls_articles&article_section=",
                page   = (pg) ? "&paged=" + pg : "";

            //expose url for testing
            defer.promise.url = prefix+section+page

            $http.get(prefix+section+page).success(function (data){
                //simulate server delay
                $timeout(function(){
                    defer.resolve(data)
                },delay)
            })

            return defer.promise

        },
        getRelatedContent : function(tags, exclude_post_id){

            var defer  = $q.defer(),
                parse  = tags.toString(),
                path1  = "/tag/"+parse+"/?json=1",
                path2  = "/?tag="+parse+"&json=1",
                url    = "/api/get_posts/?article_tags=" + parse + "&exclude_post=" + exclude_post_id;

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
        getSearchResults : function(path,page,filters,ord,date,contentFilters) {

            var defer     = $q.defer(),
                page      = (!page) ? 1 : page,
                filt      = (filters.length == 0) ? "" : "&"+filters,
                prefix    = this.checkQueries(path),
                order     = (!ord)? "" : "&orderby=date&order=" + ord,
                dateRange = (!date)? "" : "&date_filter=" + date,
                cFilters  = (!contentFilters)? "" : "&post_type["+contentFilters+"]",
                query     = "json=1&paged="+page,
                finalPath = path+prefix+query+filt+order+dateRange+cFilters;

            //expose url for testing
            defer.promise.url = finalPath
            //console.log(finalPath);

            $http.get(finalPath).success(function (data){
                //simulate server delay
                $timeout(function(){
                    defer.resolve(data)
                },delay)
            })
            return defer.promise
        
        },
        getFullResults : function(path){

            var defer = $q.defer(),
                prefix    = this.checkQueries(path),
                query     = "json=1&count=-1",
                finalPath = path+prefix+query;

            //expose url for testing
            defer.promise.url = finalPath

            $http.get(finalPath).success(function (data){
                //simulate server delay
                $timeout(function(){
                    defer.resolve(data)
                },delay)
            })
            return defer.promise

        },
        getArticleList : function(page){

            var defer = $q.defer(),
                path  = '/discover/page/' + page + '?json=1';

            $http.get(path).success(function (data){
                //simulate server delay
                $timeout(function(){
                    defer.resolve(data)
                },delay)
            })

            return defer.promise

        },
        getFaqs : function (){

            var defer = $q.defer(),
                path  = '/api/get_posts/?post_type=tls_faq&order=ASC&orderby=menu_order';

            $http.get(path).success(function (data){
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