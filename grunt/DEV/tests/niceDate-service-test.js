describe('niceDate', function() {

    beforeEach(module('tls'));

    it('should convert wordpress date format', inject(function(niceDate){
        var input   = "2014-12-02 10:32:36",
            test    = niceDate.format(input),
            date    = niceDate.testobj;

        expect(test).toEqual("DECEMBER 2 2014");
        expect(date).toBeDefined();

        expect(date.year).toBe(2014);
        expect(date.month).toBe(12);
        expect(date.date).toBe(02);
        expect(date.hours).toBe(10);
        expect(date.mins).toBe(32);
        expect(date.seconds).toBe(36);

    }));

})