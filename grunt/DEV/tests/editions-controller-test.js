describe('Unit: latest edition', function() {

    beforeEach(module('tls'));

    var ctrl, scope;

    beforeEach(inject(function($controller, $rootScope) {
        scope = $rootScope.$new();
        ctrl = $controller('latesteditions', {
          $scope: scope
        });
    }));

    // it('should work', function(){

    //     var ready = scope.ready

    //     expect(ready).toBe(false)

    //     scope.setCurrentEditionObj();

    //     expect(ready).toBe(true)

    // })

})
