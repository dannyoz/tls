.factory('commentApi',['$http','$q', function ($http,$q){
	return {

		post : function (path,formData){

			$http.post(path,formData)
				.success(function (data){

					console.log('success', data);
				})
				.error(function (data){

					console.log('error', data);
				})
		}
	}
}])