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

})
