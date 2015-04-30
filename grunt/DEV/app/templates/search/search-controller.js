.controller('search',[
    "$scope",
    '$sce',
    '$timeout',
    'api',
    'niceDate',
    'tealium', 
    function ($scope,$sce,$timeout,api,niceDate,tealium) {

        //Set default vars
        var url = window.location.href;

        // Used to build final string to be passed to the API call
        $scope.filters         = []
        // Used to check active filters to add class
        var activeFilters   = {
        	'contentType': [],
        	'date':        [],
        	'category':    []
        };
        // Content types not attached to categories
        var contentTypeNoCategories = ['Blogs', 'FAQs'];

        $scope.taxonomyFilters = []
        $scope.currentPage     = 1
        $scope.dateRange       = ""
        $scope.orderName       = "Newest"
        $scope.order           = "DESC"
        $scope.showSorter      = false
        $scope.loadResults     = true
        $scope.clearable       = false
        $scope.niceDate        = niceDate

        // Flag to show/hide Categories based on content type
        $scope.showCategories  = true; 


        // Helper function
        // Checks if clicked filter exists in array
        $scope.inFiltersArray = function(value, type) {        	
            var inFilter = false;
            if (type) {
            	if (activeFilters[type].indexOf(value) != -1) inFilter = true;	
            }            
            return inFilter;
        };

        // Helper function
        // Check if content type returns categories
        $scope.contentNoCategories = function(contentType) {
        	var hasCats = false;
        	if (contentType) {
        		if (contentTypeNoCategories.indexOf(contentType) != -1) hasCats = true;	
        	}
        	return hasCats;
        }


        $scope.request = function(){

            $scope.loadResults = true;

            api.getSearchResults(
                url,
                $scope.currentPage,
                $scope.filters,
                $scope.order,
                $scope.dateRange
                )
                .then(function (results){

                    $scope.showFilters = false
                    $scope.loadResults = false
                    $scope.results     = results
                    $scope.contentType = results.content_type_filters
                    $scope.sections    = results.articles_sections
                    $scope.dateRanges  = results.date_filters
                    $scope.searchData  = {
                        "searchTerm" : results.search_query,
                        "count" : results.count_total,
                        "pagesTotal": results.pages,
                        "pageNumber": results.page_number
                    },
                    $scope.paginationConfig = {
                        "pageCount"   : results.pages,
                        "currentPage" : $scope.currentPage,
                        "filters"     : $scope.filters,
                        "order"       : $scope.order,
                        "dateRange"   : $scope.dateRange
                    }

            })
        }   

        //Refresh scope when pagination page is selected
        $scope.$on('loading',function (){
            $scope.loadResults = true
        })

        $scope.$on('updatePage',function (e,results,curr){

            $scope.loadResults = false
            $scope.paginationConfig.currentPage = curr
            $scope.currentPage = curr
            $scope.results     = results
            $scope.searchData.pageNumber = curr

        })

        // =====================
        // Content Type filter
        // =====================
        $scope.contentFilter = function(term,query,key) {

        	var list = $scope.contentType,
                typeName = 'content type';

            $scope.showCategories = !$scope.contentNoCategories(term);    

            if (query) {

            	// Index of filter in filters array
                var index = $scope.filters.indexOf(query);
                // Removing filter already in array
                // to force searching one filter at a time
                $scope.filters.splice(index,1);

                // Filter clicked not in array
                if (index == -1) {                	
                    // Clear active filters array 
                    // (force to filter one at a time)
                    activeFilters['contentType'] = [];
                    // Add filter to array
                    $scope.filters.push(query);
                    // Add filter key name to array
                    activeFilters['contentType'].push(key);	                   
                    // Tealium tag
                    tealium.filtering('add',typeName,term);
                } 
                else {                	
                    activeFilters['contentType'].splice(index,1);
                    // Show categories back
                    $scope.showCategories = true;
                    // Remove Tealium tag
                    tealium.filtering('remove',typeName,term);
                }

                $scope.loadResults = true;              

                // Refactor filters string for API call
                var filtersArr   = (!$scope.filters) ? [] : $scope.filters;                	
                var converted = filtersArr.toString().replace(/,/g,'&');
                var filters = (filtersArr.length == 0) ? "" : "&"+converted;

                api.getSearchResults(
                        url,
                        1,
                        filters,
                        $scope.order,
                        $scope.dateRange
                    )
                    .then(function (results) {

                        $scope.loadResults = false
                        $scope.results = results
                        $scope.contentType = results.content_type_filters
                        $scope.sections    = results.articles_sections
                        $scope.dateRanges  = results.date_filters
                        $scope.paginationConfig = {
                            "pageCount"   : results.pages,
                            "currentPage" : 1,
                            "filters"     : $scope.filters,
                            "order"       : $scope.order,
                            "dateRange"   : $scope.dateRange
                        }                  
                })
            }
        }

        // =====================
        // Dates filter
        // =====================
        $scope.dateRangeFilter = function(range,name){

            var $this = $scope.dateRanges[name];
            var index = activeFilters['date'].indexOf(name);            

            if (index == -1) {
            	// Restrict single filter for Dates
            	activeFilters['date'] = [];
                activeFilters['date'].push(name);
                $scope.dateRange = range;
                $scope.clearable = true;
                tealium.filtering('add','date',range);
                
            } else {
                activeFilters['date'].splice(index,1);
                $scope.dateRange = "";
                $scope.clearable = false
                tealium.filtering('remove','date',range);
            }

            $scope.loadResults = true;

            api.getSearchResults(
                    url,
                    1,
                    $scope.filters,
                    $scope.order,
                    $scope.dateRange
                )
                .then(function (results){
                
                    $scope.loadResults = false
                    $scope.results = results
                    $scope.contentType = results.content_type_filters
                    $scope.sections    = results.articles_sections
                    $scope.dateRanges  = results.date_filters
                    $scope.paginationConfig = {
                        "pageCount"   : results.pages,
                        "currentPage" : 1,
                        "filters"     : $scope.filters,
                        "order"       : $scope.order,
                        "dateRange"   : $scope.dateRange
                }
            })
        }

        // =====================
        // Category filter
        // =====================
	    $scope.categoryFilter = function(term,query,key) {

	        var list = $scope.sections,
	        	typeName = 'category';    

	        if (query) {

	        	// Index of filter in filters array
	            var index = activeFilters['category'].indexOf(query);

	            // Filter clicked not in array
	            if (index == -1) {
	            	// Add filter to array
                    activeFilters['category'].push(key);
	                tealium.filtering('add',typeName,term);
	            } else {
	                activeFilters['category'].splice(index,1);
	                tealium.filtering('remove',typeName,term);
	            }


	            $scope.loadResults = true;              

	            // Refactor filters string for API call
                $scope.filters.splice('article_section=' + activeFilters['category'],1);
                $scope.filters.push('article_section=' + activeFilters['category']);

	            api.getSearchResults(
	                    url,
	                    1,
                        $scope.filters,
	                    $scope.order,
	                    $scope.dateRange
	                )
	                .then(function (results) {
	                
	                    $scope.loadResults = false
	                    $scope.results = results
	                    $scope.contentType = results.content_type_filters
	                    $scope.sections    = results.articles_sections
	                    $scope.dateRanges  = results.date_filters
	                    $scope.paginationConfig = {
	                        "pageCount"   : results.pages,
	                        "currentPage" : 1,
	                        "filters"     : $scope.filters,
	                        "order"       : $scope.order,
	                        "dateRange"   : $scope.dateRange
	                    }                       
	            })
	        }
	    }        

        $scope.orderResults = function(order,orderName){

            $scope.loadResults = true

            $scope.order     = order;
            $scope.orderName = orderName;
            
            api.getSearchResults(
                    url,
                    1,
                    $scope.filters,
                    $scope.order,
                    $scope.dateRange)
                .then(function (results){
                    
                    $scope.loadResults = false
                    $scope.results = results
                    $scope.contentType = results.content_type_filters
                    $scope.sections    = results.articles_sections
                    $scope.dateRanges  = results.date_filters
                    $scope.paginationConfig = {
                        "pageCount"   : results.pages,
                        "currentPage" : 1,
                        "filters"     : $scope.filters,
                        "order"       : $scope.order,
                        "dateRange"   : $scope.dateRange
                }
            })

            tealium.sortOrder(orderName)
        }

        $scope.clearFilters = function(filters){

            $scope.filters         = [];
            activeFilters   = {
	        	'contentType': [],
	        	'date':        [],
	        	'category':    []
	        };
            $scope.taxonomyFilters = [];
            $scope.currentPage     = 1;
            $scope.dateRange       = "";
            $scope.clearable       = false;

            $scope.request();
        }

        $scope.request();

}])