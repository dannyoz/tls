.factory('objToArr',function(){
	return{
		
		convert: function(o) {

			var result = [];
			
			for (var k in o) {
				var ob = o[k];
				result.push(ob);
			}

			return result;
		}
	}
})