.factory('commentApi',['$http','$q', function ($http,$q){
	return {

		post : function (path){

			var defer = $q.defer();

			$http.post(path)
				.success(function (data, textStatus){

					defer.resolve(data);

				})
				.error(function (data){

					defer.resolve(data);

				})

			return defer.promise
		}
	}
}])