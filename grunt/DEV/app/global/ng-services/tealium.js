.factory('tealium',function(){
	return{
		test : function(val1,val2,val3,val4){

			var tags = {
				"key1" : val1,
				"key2" : val2,
				"key3" : val3,
				"key4" : val4
			}

			return tags
		}
	}
})