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
        rootScope.$broadcast("hello");
        expect(rootScope.$broadcast).toHaveBeenCalled()
    })
})
