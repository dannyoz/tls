.directive('tlsCard',['$sce','tealium',function ($sce,tealium) {
	return{
		restrict:"A",
		templateUrl : "tls-card.html",
		scope : {
			data : "=tlsCard",
			type : "@",
			copy : "=copy"
		},
		link : function(scope) {

			scope.sce = $sce;
			var card = scope.data;	
			var typeAttr = scope.type; // passed as attribute
			card.hasCopy = (scope.copy == undefined) ? true : scope.copy; 

			// Function to check value is undefined
			var isUndefined = function(val) {							
				return angular.isUndefined(val);
			}

			if (card.type == "listen_blog") {
				card.type  = "blog"
				card.category = {
					slug: "listen",
					title: "Listen"
				}
			}

			if(card.type == "tls_articles"){
				card.type  = "article"
			}

			// Change type using data-type attribute
			if (typeAttr && card.type != 'mpu') {
				card.type = scope.type;
			}

			// Type of card (Object or Array)
			var cardObjType = Object.prototype.toString.call(card);

			// Homepage case: array of blog objects			
			if (cardObjType === '[object Array]' && cardObjType.length > 0) {
				card.type = 'blog_homepage';
			} 

			
			
			// Function that format final object in a consistent way
			scope.formatCard = function(card) {

				var cardType;

				// Card type
				if (card.hasOwnProperty('type')) {
					cardType = card.type;
				}

				switch (cardType) {

					case 'article':

						// Visibility
						if (!isUndefined(card.taxonomy_article_visibility)
						&& !isUndefined(card.taxonomy_article_visibility[0])) {
							if (card.taxonomy_article_visibility[0]['slug'] != '') {
								card.visibility = card.taxonomy_article_visibility[0]['slug'];
							}
						}
						
						// Author name
						if (typeof card.author == 'object') {
							card.author = card.author.name;
						}

						// Thumbnail image
						if (!isUndefined(card.custom_fields)) {
							if (card.custom_fields.thumbnail_image_url != '') {
								card.image = card.custom_fields.thumbnail_image_url;
							}							
						}

						// Section name and section link						
						if (!isUndefined(card.taxonomy_article_section) 
						&& !isUndefined(card.taxonomy_article_section[0])) {

							if (!isUndefined(card.taxonomy_article_section[0])) {

								card.section = {};
								if (!isUndefined(card.taxonomy_article_section[0]['name'])) {
									card.section.name = card.taxonomy_article_section[0]['name'];			
								}
								
								if (!isUndefined(card.taxonomy_article_section[0]['title'])) {
									card.section.name = card.taxonomy_article_section[0]['title'];			
								}

								if (!isUndefined(card.taxonomy_article_section_url)) {
									if (card.taxonomy_article_section_url != '') {
										card.section.link = card.taxonomy_article_section_url;	
									}									
								}
							}													
						}

						// Article URL
						if (!isUndefined(card.link)) {
							if (card.link != '') {
								card.url = card.link;
							}							
						}

						// Article Image URL
						if (!isUndefined(card.image)) {

							// It's a string
							if (typeof card.image == 'string') {
								card.image_url = card.image;
							}
							// It's an Array
							if (typeof card.image == 'object') {
								if (!isUndefined(card.image) && card.image != null) {
									card.image_url = card.image[0];
								}								
							}
						}

						//Excerpt
						if(!card.excerpt && card.text){
							card.excerpt = card.text[0]
						}

					break;

					case 'blog':
						console.log(card);
						if (!isUndefined(card.categories) &&
							!isUndefined(card.categories[0])) {
							card.category = {};
							card.category.slug = card.categories[0]['slug'];
							card.category.title = card.categories[0]['title'];
						}

						if (!isUndefined(card.custom_fields)
						&& !isUndefined(card.custom_fields['soundcloud_embed_code'])) {
							card.soundcloud =  card.custom_fields['soundcloud_embed_code'][0];
						}

                        card.excerpt = card.excerpt;

					break;
				}		

			}(card);

			scope.tealiumTag = function(card){

				var cat   = (card.section) ? card.section.name : card.category.title,
					title = card.title,
					state = (card.taxonomy_article_visibility) ? card.taxonomy_article_visibility[0].name : "Public",
					restr = (state == "Public") ? "public" : "restricted";

				tealium.cardLink(cat,title,restr);
			}
		}
	}
}])