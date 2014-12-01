describe('api', function() {

    beforeEach(module('tls'));

    it('can get an instance of my factory', inject(function(api) {
        expect(api).toBeDefined();
    }));

    it('defers a promise when request returns successfully',inject(function(api){
        expect(api.getArticle).toBeDefined();
    }));

    it('should simulate promise', inject(function(api, $q, $rootScope) {
          var deferred = $q.defer();
          var promise = deferred.promise;
          var resolvedValue;

          promise.then(function(value) { resolvedValue = value; });
          expect(resolvedValue).toBeUndefined();

          // Simulate resolving of promise
          deferred.resolve(123);
          // Note that the 'then' function does not get called synchronously.
          // This is because we want the promise API to always be async, whether or not
          // it got called synchronously or asynchronously.
          expect(resolvedValue).toBeUndefined();

          // Propagate promise resolution to 'then' functions using $apply().
          $rootScope.$apply();
          expect(resolvedValue).toEqual(123);
    }));

})
