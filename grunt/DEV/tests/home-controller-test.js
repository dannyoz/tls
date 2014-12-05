describe('Unit: home', function() {
    // Load the module with MainController
    beforeEach(module('tls'));

    var ctrl, scope;
    // inject the $controller and $rootScope services
    // in the beforeEach block
    beforeEach(inject(function($controller, $rootScope) {
        // Create a new scope that's a child of the $rootScope
        scope = $rootScope.$new();
        // Create the controller
        ctrl = $controller('home', {
          $scope: scope
        });
    }));


    it('should have a hero image', function() {
        expect(scope.image).toBe("hero");
    });

    it('should get an api response',function(){
        expect(scope.cards).toBeDefined()
    })
})


