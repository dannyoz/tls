.factory('mpu',['$q', function ($q) {

	return {

		insert : function(posts, mpuObj){

			var defer  = $q.defer(),
				object = {
                    id   : "div-gpt-ad-" + mpuObj.id,
                    type : "mpu"
                };

			posts.splice(mpuObj.order, 0, object);

			defer.resolve(posts);

			return defer.promise;
		}
	}

}])