describe('Unit: pagination', function() {
    var $compile,$rootScope;
    var themeUrl = "wp-content/themes/tls";

    beforeEach(module('tls'));
    beforeEach(module("DEV/app/templates/search/tls-pagination.html"));

    beforeEach(inject(function(_$compile_, _$rootScope_){
        // The injector unwraps the underscores (_) from around the parameter names when matching
        $compile = _$compile_;
        $rootScope = _$rootScope_;

        $rootScope.config = {
        	"pageCount"   : 3,
			"currentPage" : 1,
			"filters"     : []
        }

    }));

    it("should compile the template", function(){

    	var element = $compile("<div tls-pagination='config'></div>")($rootScope);
        $rootScope.$digest();

        expect(element.html()).toContain('<ul class="pagination">');
    })

    it("should expect a valid config format", function(){

    	var element = $compile("<div tls-pagination='config'></div>")($rootScope);
        $rootScope.$digest();

        expect(typeof $rootScope.config.pageCount).toBe("number");
        expect(typeof $rootScope.config.currentPage).toBe("number");
        expect(Array.isArray($rootScope.config.filters)).toBeTruthy(); 

    })

})