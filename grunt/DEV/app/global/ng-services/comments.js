.factory('commentApi',['$http','$q', function ($http,$q){
	return {

		post : function (path){

			var defer = $q.defer();

			$http.post(path)
				.success(function (data, textStatus){

					if (data.status == 'error') {
						defer.reject('You might have left one of the fields blank, or be posting too quickly');
					} else {
						defer.resolve(data);
					}

				})
				.error(function (data, textStatus){

					if (textStatus == 409 || textStatus == '409') {
						defer.reject('Duplicate comment detected, it looks as though you’ve already said that!');
					} else {
						defer.reject('Sorry there was an error posting your comment! Please check all fields are correct.');
					}

				})

			return defer.promise
		}
	}
}])