.factory('mpu',['$q', function ($q) {

	return {

		insert : function(posts,order){

			var defer  = $q.defer(),
				object = {"type" : "mpu"};

			posts.splice(order,0,object);
			defer.resolve(posts);

			return defer.promise
		}
	}

}])