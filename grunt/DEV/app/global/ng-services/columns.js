.factory('columns',['$q', function ($q){
	return{
		divide : function(array){

			var defer = $q.defer(),
				cols  = {};

			cols.col1 = [];
			cols.col2 = [[],[]];
			cols.col3 = [[],[],[]];

			// One column
			cols.col1.push(array);

			if (array != undefined) {
				// Two columns
				for (var i = 0; i<array.length; i++){

					function isOdd(num) { return num % 2;}

					if(isOdd(i)){
						cols.col2[1].push(array[i])
					} else {
						cols.col2[0].push(array[i])
					}
				}

				// Three columns
				for (var i = 0; i<array.length; i+=3){
					cols.col3[0].push(array[i])
				}
				for (var i = 1; i<array.length; i+=3){
					cols.col3[1].push(array[i])
				}
				for (var i = 2; i<array.length; i+=3){
					cols.col3[2].push(array[i])
				}
			}
			

			defer.resolve(cols)
			defer.promise.result = cols

			return defer.promise
		}
	}
}])