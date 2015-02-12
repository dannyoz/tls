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
        $scope.filters         = []
        $scope.activeFilters   = []
        $scope.taxonomyFilters = []
        $scope.currentPage     = 1
        $scope.dateRange       = ""
        $scope.orderName       = "Newest"
        $scope.order           = "DESC"
        $scope.showSorter      = false
        $scope.loadResults     = true
        $scope.clearable       = false
        $scope.niceDate        = niceDate


        // Helper function that checks whether a filter is active or not
        $scope.inFiltersArray = function(value) {        	
            var inFilter = false;
            if ($scope.activeFilters.indexOf(value) != -1) inFilter = true;
            return inFilter;
        };


        $scope.request = function(){

            $scope.loadResults = true

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
                    $scope.paginationConfig = {
                        "pageCount"   : results.pages,
                        "currentPage" : $scope.currentPage,
                        "filters"     : $scope.filters,
                        "order"       : $scope.order,
                        "dateRange"   : $scope.dateRange
                }

                //console.log(results);
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

        })

        $scope.contentFilter = function(term,query,key,type) {

            var list = (type == 'content') ? $scope.contentType : $scope.sections,
                typeName = (type == 'content') ? 'content type' : type;

            if (query) {

                var index = $scope.filters.indexOf(query);

                if (index == -1) {
                    $scope.filters.push(query);
                    // Restrict single filter for Content Types
                    if (type == 'content') {
                    	$scope.activeFilters = [];
                    } 
                    $scope.activeFilters.push(key);	                   
                    tealium.filtering('add',typeName,term);
                } else {
                    $scope.filters.splice(index,1);
                    $scope.activeFilters.splice(index,1);
                    tealium.filtering('remove',typeName,term);
                }

                $scope.loadResults = true;              

                // Refactor filters based on filter type
                if (type == 'content') {                      		
                	var filtersArr   = (!$scope.filters) ? [] : $scope.filters;                	
                	var converted = filtersArr.toString().replace(/,/g,'&');
                	var filters = (filtersArr.length == 0) ? "" : "&"+converted;

                } else if (type == 'category') {
	                var filters = 'article_section=' + $scope.filters.toString();
                }

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

                        //console.log($scope.contentType);                    
                })
            }
        }

        $scope.dateRangeFilter = function(range,name){

            var $this = $scope.dateRanges[name];
            var index = $scope.activeFilters.indexOf(name);            

            if (index == -1) {
            	// Restrict single filter for Dates
            	$scope.activeFilters = [];
            	
                $scope.activeFilters.push(name);
                $scope.dateRange = range;
                $scope.clearable = true;
                tealium.filtering('add','date',range);
                
            } else {
                $scope.activeFilters.splice(index,1);
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
            $scope.activeFilters   = [];
            $scope.taxonomyFilters = [];
            $scope.currentPage     = 1;
            $scope.dateRange       = "";
            $scope.clearable       = false;

            $scope.request();
        }

        $scope.request();

}])