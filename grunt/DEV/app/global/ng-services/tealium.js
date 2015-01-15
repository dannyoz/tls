.factory('tealium',function(){

	
	var utagView = function utagView(tags) {
		try {
			if(utag) {
				utag.view(tags);
			}
		}catch(e) {

		}
	};

	var utagLink = function utagLink(tags) {
		try {
			if(utag) {
				utag.link(tags);
			}
		}catch (e) {

		}
	};

	var debug    = true;
	var debugBar = function(tags,method){

		if(debug){

			var	old  = document.getElementById("tealium-debug");
				body = document.body,
				elem = angular.element(body),
				coms = "Comic Sans MS",
				pop  = '<div id="tealium-debug" class="centre" style="border-radius:40px;background:white;z-index:9999;padding:50px;box-shadow:0 0 100px rgba(0,0,0,0.9);width:700px;font-size:14px;font-family:'+coms+'">' +
							'<h2 style="color:blue">Tealium debugger</h2>' +
							'<p style="color:red">Method : '+method+'</p>' +
							'<ul id="telium-tags">' +
							'<li class="grid-row" style="background:#e9e9e9;padding:10px;"><span class="grid-6">Variable</span><span class="grid-6">Value</span></li>' +
							'</ul>' +
							'<a id="close-telium" style="display:block;background:#ddd;position:absolute;top:10px;right:10px;width:50px;height:50px;border-radius:100%">'+ 
							'<i class="icon icon-cross centre"></i></a>' +
					   '</div>'

			if(old){
				angular.element(old).remove();
			}

			elem.append(pop);

			var tealiumList  = angular.element(document.getElementById("telium-tags")),
				closeTealium = angular.element(document.getElementById("close-telium")),
				tealiumDebug = angular.element(document.getElementById("tealium-debug"));

			angular.forEach(tags,function (val,key){
				var listItem = '<li class="grid-row" style="padding:10px;">' +
							   '<span class="grid-6">'+key+'</span>' +
							   '<span class="grid-6">'+val+'</span>' +
							   '</li>'
				tealiumList.append(listItem)
			})

			closeTealium.click(function(){
				tealiumDebug.remove();
			})

		}

	}

	return{
		debugBar : debugBar,
		test : function(val1,val2,val3,val4){

			var tags = {
				"key1" : val1,
				"key2" : val2,
				"key3" : val3,
				"key4" : val4
			}

			return tags
		},
		socialLink : function(platform){

			var tags = {
				"event_social_action" : "share start",
				"social_category" : "share",
				"social_platform" : platform,
				"page_name" : utag_data.page_name
			}

			debugBar(tags, 'Link');
			utagLink(tags);

			return tags
		},
		loadMore : function (){

			var tags = {
				"event_engagement_action" : "engagement",
				"event_engagement_name" : "load more",
				"event_engagement_browsing_method" : "click"
			}

			debugBar(tags, 'Link');
			utagLink(tags);

			return tags
		}
	}
})