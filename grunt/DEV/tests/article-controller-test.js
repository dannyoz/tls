describe('Unit: article', function() {

    beforeEach(module('tls'));

    var ctrl, scope;

    beforeEach(inject(function($controller, $rootScope) {
        scope = $rootScope.$new();
        ctrl = $controller('article', {
          $scope: scope
        });
    }));


    it('should apply or remove filters on click', function(){

        scope.orginalList = ['this','is','the','orginal','array'];
        scope.activeTags  = [{isApplied : false},{isApplied : false},{isApplied : false},{isApplied : false},{isApplied : false}]
        scope.loadingTags = false;

        var test = scope.refineRelated('nick',1);
        scope.loadingTags = false;
        expect(test).toEqual(['nick']);
        expect(scope.activeTags[1]).toEqual({isApplied : true});

        var test = scope.refineRelated('cage',4);
        scope.loadingTags = false;
        expect(test).toEqual(['nick','cage']);
        expect(scope.activeTags[4]).toEqual({isApplied : true});

        var test = scope.refineRelated('nick',1);
        scope.loadingTags = false;
        expect(test).toEqual(['cage'])
        expect(scope.activeTags[1]).toEqual({isApplied : false});

        var test = scope.refineRelated('cage',4);
        scope.loadingTags = false;
        expect(test).toEqual(['this','is','the','orginal','array']);
        expect(scope.activeTags[4]).toEqual({isApplied : false});

    });

})
