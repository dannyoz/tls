.controller('header',['$scope','tealium', function ($scope,tealium){

	$scope.ready       = true
	$scope.hint        = false
	$scope.placeholder = "Tls archive, blogs and website";
	$scope.searchOpen  = false
	$scope.hasCookie   = true

	$scope.checkCookies = function(){
		
		var cookies = document.cookie,
			val     = "cookiesAccepted=true",
			index   = cookies.indexOf(val);

		if(index > -1){
			$scope.hasCookie = true
		} else {
			$scope.hasCookie = false
		}

	}();

	$scope.showSearch  = function(){

		$scope.placeholder = "";
		$scope.searchOpen  = true;
	}

	$scope.searchFocus = function(){

		$scope.hint = true
		$scope.placeholder = ''; 
		$scope.focus = true
	}

	$scope.subscribe = function(){
		tealium.subscribe('header');
	}

	$scope.login = function(){
		tealium.user('login');
	}

	$scope.acceptCookies = function(){

		var val    = "cookiesAccepted=true",
			now    = new Date,
			oneYr  = now.setYear(now.getFullYear() + 1),
			expiry = "expires=" + oneYr,
			path   = "path=/",
			cookie = val + "; " + expiry + "; " + path;

		document.cookie  = cookie;
		$scope.hasCookie = true
	}
	
}])