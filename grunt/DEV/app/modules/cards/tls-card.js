.directive('tlsCard',['$sce',function ($sce) {
	return{
		restrict:"A",
		templateUrl : "tls-card.html",
		scope : {
			data : "=tlsCard",
			type : "@"
		},
		link : function(scope){

			console.log(scope.type)
						
			scope.sce = $sce;
			var card = scope.data;	
			// Type passed as attibute
			var typeAttr = scope.type;

			//Change type using data-type attribute
			if(scope.type){
				card.type = scope.type
			}

			// Type of card (Object or Array)
			var cardObjType = Object.prototype.toString.call(card);

			// Homepage case: array of blog objects			
			if (cardObjType === '[object Array]' && cardObjType.length > 0) {
				card.type = 'blog_homepage';
			} 

			// Type passed as attibutes in template
			if (typeAttr != undefined && typeAttr == 'blog') {
				card.type = 'blog';	
			}

			// Function to check value is undefined
			var isUndefined = function(val) {				
				
				var und = false;
				if (val == undefined) {
					und = true;
				}
				return und;
			}
			
			// Function that format final object in a consistent way
			scope.formatCard = function(card) {

				var cardType;

				console.log(card);

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
								if (!isUndefined(card.image[0])) {
									card.image_url = card.image[0];
								}								
							}
						}	

						//console.log(card);

					break;

					case 'blog':
						
						if (!isUndefined(card.categories[0])) {
							card.category = {};
							card.category.slug = card.categories[0]['slug'];
							card.category.title = card.categories[0]['title'];
						}

						//console.log(card);

					break;
				}		

			}(card);
		}
	}
}])