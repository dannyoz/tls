describe('Unit: article', function() {

    beforeEach(module('tls'));

    var ctrl, scope;

    beforeEach(inject(function($controller, $rootScope) {
        scope = $rootScope.$new();
        ctrl = $controller('article', {
          $scope: scope
        });
    }));


    it('should convert wordpress date format', function(){

        var date    = "2014-12-02 10:32:36",
            test    = scope.format(date),
            conv    = "2 DECEMBER 2014",
            match   = (test.indexOf(conv) > -1 && conv.length == test.length) ? true : false;

        expect(typeof test).toBe("string");
        expect(match).toBe(true);
        
    })

    it('should apply or remove filters on click', function(){

        var test = scope.refineRelated('nick');
        expect(test).toEqual(['nick']);

        var test = scope.refineRelated('cage');
        expect(test).toEqual(['nick','cage']);

        var test = scope.refineRelated('nick');
        expect(test).toEqual(['cage'])

        var test = scope.refineRelated('cage');
        expect(test).toEqual([]);

    });

})
