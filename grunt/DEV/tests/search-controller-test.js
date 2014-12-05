describe('Unit: search', function() {

    beforeEach(module('tls'));
    var ctrl, scope;
    beforeEach(inject(function($controller, $rootScope) {
        rootScope = $rootScope;
        scope = $rootScope.$new();
        ctrl = $controller('search', {
          $scope: scope
        });

       spyOn($rootScope, '$broadcast').and.callThrough();

    }));
    
    it('should broadcast to scope', function(){
        rootScope.$broadcast();
        expect(rootScope.$broadcast).toHaveBeenCalled()
    });

    it('should add or remove filters when applied', function(){

        scope.filters = ['articles','blogs'];
        scope.filterResults('blogs');
        expect(scope.filters[0]).toBe('articles');

        scope.filters = ['articles','blogs'];
        scope.filterResults('articles');
        expect(scope.filters[0]).toBe('blogs');

        scope.filters = ['articles','blogs'];
        scope.filterResults('test');
        expect(scope.filters[0]).toBe('articles');
        expect(scope.filters[1]).toBe('blogs');
        expect(scope.filters[2]).toBe('test');

    });

    // it('should sort the search order', function(){

    //     var test = scope.orderResults('derp');
    //     expect(test).toBe('derp');
    // });

    it('should convert wordpress date format', function(){

        var date    = "2014-12-02 10:32:36",
            test    = scope.format(date),
            conv    = "2 DECEMBER 2014",
            match   = (test.indexOf(conv) > -1 && conv.length == test.length) ? true : false;

        expect(typeof test).toBe("string");
        expect(match).toBe(true);
        
    })
})
