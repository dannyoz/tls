describe('Unit : columns', function() {

    beforeEach(module('tls'));


    it('should split post array into columns', inject(function (columns){

    	var array = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14],
    		test  = columns.divide(array);

    	expect(test.result.col1[0]).toEqual([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14]);
    	expect(test.result.col2[0]).toEqual([0,2,4,6,8,10,12,14]);
    	expect(test.result.col2[1]).toEqual([1,3,5,7,9,11,13]);
    	expect(test.result.col3[0]).toEqual([0,3,6,9,12])
    	expect(test.result.col3[1]).toEqual([1,4,7,10,13])
    	expect(test.result.col3[2]).toEqual([2,5,8,11,14])

    }));

})