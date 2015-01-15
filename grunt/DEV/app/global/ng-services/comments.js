.factory('commentApi',['$http','$q', function ($http,$q){
	return {

		post : function (path,formData){

			$http.post(path,formData)
				.success(function (data, textStatus){

					console.log('success', data, textStatus);
				})
				.error(function (data){

					console.log('error', data);
				})
		}
	}
}])