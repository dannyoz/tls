.controller('header',['$scope','tealium', function ($scope,tealium){

	$scope.ready       = true;
	$scope.hint        = false;
	$scope.placeholder = "Tls archive, blogs and website";
	$scope.searchOpen  = false;
	$scope.hasCookie   = true;

	$scope.checkCookies = function(){
		
		var cookies = document.cookie,
			val     = "cookiesAccepted=true",
			index   = cookies.indexOf(val);

		if(index > -1){
			$scope.hasCookie = true;
		} else {
			$scope.hasCookie = false;
		}

	}();

	$scope.showSearch  = function(){

		$scope.placeholder = "";
		$scope.searchOpen  = true;
	}

	$scope.hideSearch  = function(e){
		
		$scope.searchOpen  = false;
		$scope.hint = false;
		e.stopPropagation();
	}

	$scope.searchFocus = function(){

		$scope.hint = true;
		$scope.placeholder = ''; 
		$scope.focus = true;
	}

	$scope.subscribe = function(){		
		tealium.subscribe('header');
	}

	$scope.login = function(){
		tealium.user('login');
	}

	$scope.acceptCookies = function(){

		var exdate = new Date();
   		exdate.setDate(exdate.getDate() + 365);

		var val    = "cookiesAccepted=true",
			expiry = "expires=" + exdate.toUTCString(),
			path   = "path=/",
			cookie = val + "; " + expiry + "; " + path;

		document.cookie  = cookie;
		$scope.hasCookie = true;
	}
	
}])