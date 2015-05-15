.controller('home',[
	'$scope', 
	'$sce', 
	'api',
	'mpu',
	'columns',
	'objToArr',
	'tealium',
	'$window',
	function ($scope, $sce, api, mpu, columns, objToArr, tealium, $window){

	var url = '/api/get_page/?id=' + home_page_id

	$scope.cards   = ""
	$scope.tealium = tealium;

	api.getHomePage(url).then(function (result){

		//console.log(result);	

		$scope.page     = result.page
		$scope.featured = result.featured_article

		var cards = objToArr.convert(result.home_page_cards);

		//Inserts home Ads to cards
		var mpuObj = [
			{
				id: 'home-1',
				order: 4
			},
			{
				id: 'home-2',
				order: 9
			}
		];

		mpu.insert(cards, mpuObj[0]).then(function (result){
			cards = result;
		});

		mpu.insert(cards, mpuObj[1]).then(function (result){
			cards = result;
		});

		columns.divide(cards).then(function (cols){

			$scope.col1  = cols.col1
			$scope.col2  = cols.col2
			$scope.col3  = cols.col3
		})

	});

	$scope.formatEmbed = function(html) {
		return $sce.trustAsHtml(html);
	}

	$scope.subscribe = function(){
		tealium.subscribe('subscriber exclusive box');
	}

	$scope.login = function(){
		tealium.user('login');
	}

	$scope.viewEdition = function(url) {
		
		tealium.viewEdition();

		// If url is passed then redirect page to that page				
		if (url) {					
			$window.location.href = url;				
		}
	}

}])