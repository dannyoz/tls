describe('api', function() {

    beforeEach(module('tls'));

    it('can get an instance of my factory', inject(function (api) {
        expect(api).toBeDefined();
    }));


    it('should return a promise', inject(function (api,$rootScope){

        var test = api.getSearchResults("http://localhost:80",2,3),
            promise = test.then();

        expect(promise).toBeDefined();

    }));

    it('should format getArticle urls correctly', inject(function (api){

        var test1 = api.getArticle('/url/'),
            test2 = api.getArticle('/url/',5),
            test3 = api.getArticle('/url/?preview=true',2);

        expect(test1.url).toBe('/url/?json=1');
        expect(test2.url).toBe('/url/?json=1&paged=5');
        expect(test3.url).toBe('/url/?preview=true&json=1&paged=2');

    }));

    it('should format getSearchResults urls correctly', inject(function (api){

        var test1 = api.getSearchResults('/url/',1);
            test2 = api.getSearchResults('/url/',2,['query','query2','query3']);
            test3 = api.getSearchResults('/url/',3,[],'DESC');
            test4 = api.getSearchResults('/url/',4,[],'DESC','30 days ago');
            test5 = api.getSearchResults('/url/',5,[],'DESC','30 days ago','test,anothertest');

        expect(test1.url).toBe('/url/?json=1&paged=1');
        expect(test2.url).toBe('/url/?json=1&paged=2&query&query2&query3');
        expect(test3.url).toBe('/url/?json=1&paged=3&orderby=date&order=DESC');
        expect(test4.url).toBe('/url/?json=1&paged=4&orderby=date&order=DESC&date_filter=30 days ago');
        expect(test5.url).toBe('/url/?json=1&paged=5&orderby=date&order=DESC&date_filter=30 days ago&post_type[test,anothertest]');

    }));

    it('should filter tags correctly', inject(function (api){

        var test1 = api.getRelatedContent(['tag']),
            test2 = api.getRelatedContent(['tag','tag2','tag3']);

            expect(test1.url).toBe('/api/get_posts/?article_tags=tag');
            expect(test2.url).toBe('/api/get_posts/?article_tags=tag,tag2,tag3');


    }));

    it('should return correct query sring prefix', inject(function (api){

        var test1 = api.checkQueries('/url/'),
            test2 = api.checkQueries('/url/?nickcage=awesome');
            test3 = api.checkQueries('/url/?nickcage=awesome&charliesheen=istoo');

        expect(test1).toBe("?");
        expect(test2).toBe("&");
        expect(test3).toBe("&");

    }));

    it('should remove comment hash frags', inject(function(api){

        var test1 = api.removeHashFrag('/url/#'),
            test2 = api.removeHashFrag('/url/'),
            test3 = api.removeHashFrag('/example-article/test2/#/comment-2');

        expect(test1).toBe("/url/");
        expect(test2).toBe("/url/");
        expect(test3).toBe("/example-article/test2/");

    }));


})
