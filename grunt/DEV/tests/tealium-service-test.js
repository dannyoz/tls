describe('Unit : tealium', function() {

    beforeEach(module('tls'));

	utag_data = {
		page_name : "homepage",
		page_type : "homepage", 
		page_section : "homepage", 
		page_restrictions : "public" 
	}

	it('should report log in/out buttons correctly', inject(function (tealium){

		var test = tealium.user('login'),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "login",
				"event_navigation_browsing_method" : "click",
				"page_name" : "homepage"
			}

		expect(test).toEqual(expected)

		var test = tealium.user('logout'),

        	expected = {
				"event_registration_action" : "logout success",
				"page_name" : "homepage"
			}

		expect(test).toEqual(expected)

	}));
	
    it('should report facebook shares correctly', inject(function (tealium){

        var test = tealium.socialLink("facebook"),

        	expected = {
				"event_social_action" : "share start",
				"social_category" : "share",
				"social_platform" : "facebook",
				"page_name" : "homepage"
			}

        expect(test).toEqual(expected)

    }));

    it('should report twitter shares correctly', inject(function (tealium){

        var test = tealium.socialLink("twitter"),

        	expected = {
				"event_social_action" : "share start",
				"social_category" : "share",
				"social_platform" : "twitter",
				"page_name" : "homepage"
			}

        expect(test).toEqual(expected)

    }));

    it('should report email shares correctly', inject(function (tealium){

        var test = tealium.socialLink("email"),

        	expected = {
				"event_social_action" : "share start",
				"social_category" : "share",
				"social_platform" : "email",
				"page_name" : "homepage"
			}

        expect(test).toEqual(expected)

    }));

    it('should report exit links correctly', inject(function (tealium){

    	var test = tealium.exitLink("facebook"),

        	expected = {
				"event_navigation_action" : "engagement",
				"event_navigation_name" : "exit link:facebook",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)
    }));

    it('should report engagement links correctly', inject(function (tealium){

    	var test = tealium.engagement("load more"),

        	expected = {
				"event_engagement_action" : "engagement",
				"event_engagement_name" : "load more",
				"event_engagement_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

		var test = tealium.engagement("print"),

        	expected = {
				"event_engagement_action" : "engagement",
				"event_engagement_name" : "print",
				"event_engagement_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

		var test = tealium.engagement("leave comment"),

        	expected = {
				"event_engagement_action" : "engagement",
				"event_engagement_name" : "leave comment",
				"event_engagement_browsing_method" : "click"
			}

		expect(test).toEqual(expected)
    }));

    it('should report subscribe button correctly', inject(function (tealium){

    	var test = tealium.subscribe('header'),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "subscribe:header",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

		var test = tealium.subscribe('subscriber exclusive box'),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "subscribe:subscriber exclusive box",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

		var test = tealium.subscribe('public article'),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "subscribe:public article",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

    }));

    it('should report view additions correctly', inject(function (tealium){

    	var test = tealium.viewEdition(),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "view edition",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

    }));

    it('should report prev/next links correctly', inject(function (tealium){

    	var test = tealium.paging('previous edition','october 27 2014'),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "previous edition:october 27 2014",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

		var test = tealium.paging('next edition','december 11 2014'),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "next edition:december 11 2014",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

		var test = tealium.paging('previous article','grief and the goshawk'),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "previous article:grief and the goshawk",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

		var test = tealium.paging('next article','butterflies and button boots'),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "next article:butterflies and button boots",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

    }));

	it('should report related article tags correctly', inject(function (tealium){

		var test = tealium.relatedTag('victorian'),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "tag:victorian",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

	}));

	it('should report the search page sort order correctly', inject(function (tealium){

		var test = tealium.sortOrder('newest'),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "sort by:newest",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

	}));

	it('should report search filters correctly', inject(function (tealium){

		var test = tealium.filtering('add','content type','letters to the editor'),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "search filters:add:content type:letters to the editor",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

		var test = tealium.filtering('add','date','past 30 days'),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "search filters:add:date:past 30 days",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

		var test = tealium.filtering('remove','date','past 30 days'),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "search filters:remove:date:past 30 days",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

		var test = tealium.filtering('remove','category','politics & social studies'),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "search filters:remove:category:politics & social studies",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

	}));

	it('should report the archive footer link correctly', inject(function (tealium){

		var test = tealium.archive(),

        	expected = {
				"event_navigation_action" : "navigation",
				"event_navigation_name" : "footer:archive",
				"event_navigation_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

	}));

	it('should report pdf classifieds correctly', inject(function (tealium){

		var test = tealium.classified("classified_1117191a.pdf"),

        	expected = {
				"event_engagement_action" : "engagement",
				"event_engagement_name" : "classifieds:classified_1117191a.pdf",
				"event_engagement_browsing_method" : "click",
				"event_download_action" :"download",
				"event_download_name" : "classifieds:classified_1117191a.pdf"
			}

		expect(test).toEqual(expected)

	}));

	it('should report contact page email addresses correctly', inject(function (tealium){

		var test = tealium.emailLink("letters@the-tls.co.uk"),

        	expected = {
				"event_engagement_action" : "engagement",
				"event_engagement_name" : "mailto:letters@the-tls.co.uk",
				"event_engagement_browsing_method" : "click"
			}

		expect(test).toEqual(expected)

	}));

	it('should report card links correctly', inject(function (tealium){

		var test = tealium.cardLink("blog","blog title","public"),

        	expected = {
        		"event_navigation_action" : "navigation",
				"event_navigation_name" : "widget:blog:blog title",
				"event_navigation_browsing_method" : "click",
				"page_restrictions" : "public"
			}

		expect(test).toEqual(expected)

		var test = tealium.cardLink("fiction","mathias enard's grammar of estrangement","restricted"),

        	expected = {
        		"event_navigation_action" : "navigation",
				"event_navigation_name" : "widget:fiction:mathias enard's grammar of estrangement",
				"event_navigation_browsing_method" : "click",
				"page_restrictions" : "restricted"
			}

		expect(test).toEqual(expected)

		var test = tealium.cardLink("subscriber exclusive","wall street journal","restricted"),

        	expected = {
        		"event_navigation_action" : "navigation",
				"event_navigation_name" : "widget:subscriber exclusive:wall street journal",
				"event_navigation_browsing_method" : "click",
				"page_restrictions" : "restricted"
			}

		expect(test).toEqual(expected)

	}));

})