describe('niceDate', function() {

    beforeEach(module('tls'));

    it('should convert wordpress date format', inject(function(niceDate){

        var date    = "2014-12-02 10:32:36",
            test    = niceDate.format(date),
            conv    = "DECEMBER 2 2014",
            match   = (test.indexOf(conv) > -1 && conv.length == test.length) ? true : false;

        expect(typeof test).toBe("string");
        expect(match).toBe(true);
        
    }));

})