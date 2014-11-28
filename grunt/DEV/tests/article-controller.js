describe('Unit: article', function() {

    beforeEach(module('tls'));

    var ctrl, scope;

    beforeEach(inject(function($controller, $rootScope) {
        scope = $rootScope.$new();
        ctrl = $controller('article', {
          $scope: scope
        });
    }));


    it('choose article should be a function', function() {
        expect(typeof scope.chooseArticle).toBe("function");
    });

})
