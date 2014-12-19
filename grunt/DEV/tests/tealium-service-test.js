describe('Unit : tealium', function() {

    beforeEach(module('tls'));


    it('should work', inject(function (tealium){

        var test = tealium.test("this","is","a","test");

        expect(test.key1).toBe("this");
        expect(test.key2).toBe("is");
        expect(test.key3).toBe("a");
        expect(test.key4).toBe("test");

    }));

})