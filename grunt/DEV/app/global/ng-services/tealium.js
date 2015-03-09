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
							'<h2 style="color:blue;font-family:'+coms+'">Tealium debugger</h2>' +
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
		user : function (action){

			if(action == "login"){

				var tags = {
					"event_navigation_action" : "navigation",
					"event_navigation_name" : action,
					"event_navigation_browsing_method" : "click",
					"page_name" : utag_data.page_name
				}

			}

			if(action == "logout"){

				var tags = {
					"event_registration_action" : "logout success",
					"page_name" : utag_data.page_name
				}
			}

			//debugBar(tags, 'Link');
			utagLink(tags);

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
		engagement : function (name){

			var tags = {
				"event_engagement_action" : "engagement",
				"event_engagement_name" : name,
				"event_engagement_browsing_method" : "click"
			}

			//debugBar(tags, 'Link');
			utagLink(tags);

			return tags
		},
		exitLink : function(platform){

			var tags = {
				"event_navigation_action" : "engagement",
				"event_navigation_name" : "exit link:" + platform,
				"event_navigation_browsing_method" : "click"
			}

			//debugBar(tags, 'Link');
			utagLink(tags);

			return tags
		},
		subscribe : function(location){

			var tags = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "subscribe:" + location,
				"event_navigation_browsing_method" : "click"
			}

			//debugBar(tags, 'Link');
			utagLink(tags);

			return tags
		},
		viewEdition  :function (){
			var tags = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "view edition",
				"event_navigation_browsing_method" : "click"
			}

			//debugBar(tags, 'Link');
			utagLink(tags);

			return tags
		},
		paging : function (dir,title){

			var tags = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : dir +":"+ title,
				"event_navigation_browsing_method" : "click"
			}

			//debugBar(tags, 'Link');
			utagLink(tags);

			return tags
		},
		relatedTag : function (name) {

			var tags = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "tag:"+ name,
				"event_navigation_browsing_method" : "click"
			}

			//debugBar(tags, 'Link');
			utagLink(tags);

			return tags
		},
		sortOrder : function (order){

			var tags = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "sort by:" + order,
				"event_navigation_browsing_method" : "click"
			}

			//debugBar(tags, 'Link');
			utagLink(tags);

			return tags
		},
		filtering : function(state,category,filter){

      			var tags = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "search filters:"+state+":"+category+":"+filter,
				"event_navigation_browsing_method" : "click"
			}
 
			//debugBar(tags, 'Link');
			utagLink(tags);

			return tags
		},
		archive : function(){

			var tags = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "footer:archive",
				"event_navigation_browsing_method" : "click"
			}

			//debugBar(tags, 'Link');
			utagLink(tags);

			return tags

		},
		classified : function(filename){

			var tags = {
				"event_engagement_action" : "engagement",
				"event_engagement_name" : "classifieds:" + filename,
				"event_engagement_browsing_method" : "click",
				"event_download_action" :"download",
				"event_download_name" : "classifieds:" + filename
			}

			//debugBar(tags, 'Link');
			utagLink(tags);

			return tags

		},
		emailLink : function(address){

			var tags = {
				"event_engagement_action" : "engagement",
				"event_engagement_name" : "mailto:" + address,
				"event_engagement_browsing_method" : "click"
			}

			//debugBar(tags, 'Link');
			utagLink(tags);

			return tags

		},
		cardLink : function(category,title,availabilty){

			var tags = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "widget:"+category+":"+title,
				"event_navigation_browsing_method" : "click",
				"page_restrictions" : availabilty
			}

			//debugBar(tags, 'Link');
			utagLink(tags);

			return tags

		},
        searchPagination : function(searchTerm, resultsTotal, pageTotal, pageNumber){

            var tags = {
                "page_name" : "search results",
                "page_type" : "search",
                "page_section" : "search",
                "page_restrictions" : "public",
                "internal_search_term" : searchTerm,
                "internal_search_results" : resultsTotal,
                "page_number" : pageNumber + " of " + pageTotal
            }

            //debugBar(tags, 'Link');
            utagView(tags);

            return tags

        }
	}
})