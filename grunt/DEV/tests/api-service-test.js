describe('api', function() {

    beforeEach(module('tls'));

    it('can get an instance of my factory', inject(function(api) {
        expect(api).toBeDefined();
    }));


    it('should return a promise', inject(function(api,$rootScope){

        var test = api.getSearchResults("http://localhost:80",2,3),
            promise = test.then();

        expect(promise).toBeDefined();

    }));

    it('should return correct query sring prefix', inject(function(api){

        var test1 = api.checkQueries('/url/'),
            test2 = api.checkQueries('/url/?nickcage=awesome');
            test3 = api.checkQueries('/url/?nickcage=awesome&charliesheen=istoo');

        expect(test1).toBe("?");
        expect(test2).toBe("&");
        expect(test3).toBe("&");

    }));

    it('should remove hash frags', inject(function(api){

        var test1 = api.removeHashFrag('/url/#'),
            test2 = api.removeHashFrag('/url/'),
            test3 = api.removeHashFrag('/example-article/test2/#/comment-2');

        expect(test1).toBe("/url/");
        expect(test2).toBe("/url/");
        expect(test3).toBe("/example-article/test2/");

    }));


})
