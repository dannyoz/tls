.directive('tlsColumns',function(){
	return{
		restrict:"A",
		templateUrl : "ng-views/tls-column.html",
		scope : {
			config : "=tlsColumns"
		},
		link : function(scope,element,attrs){

			// Breakpoint vars
			var tabletBP = 840,
				mobileBP = 450;

			scope.type   = scope.config.type
			scope.cards  = scope.config.cards 
			scope.width  = element[0].offsetWidth

			//Bind the viewport calculations to the resize event
			window.onresize = function(event) {

				scope.$apply(function(){
			    	scope.width = element[0].offsetWidth
			    	scope.current = scope.viewport();
			    })

			};

			//Calculate optimum viewport based on breakpoints
			scope.viewport = function(){

				if(scope.width>=tabletBP){
					return 'desktop'
				} else if(scope.width<tabletBP && scope.width>=mobileBP){
					return 'tablet'
				} else if(scope.width<mobileBP){
					return 'mobile'
				}
			}

			scope.sortCards = function(){

				var maxColumns = 3;

				function columnArrs(i){

					if(i>1){
						//console.log("column",i)
						scope.columns = {};
						scope.columns[i] = {}

						for (var k=0;k<i+1;k++){
							
							scope.columns[i][k] = []


						}

						for(var j = 0;j<scope.cards.length;j++){


							console.log(j)
						}

						console.log(scope.columns)
					}	
				}

				columnArrs(2);

				// for (var i = maxColumns; i >= 1; i--) {
				// 	columnArrs(i);
				// };
			}

			scope.sortCards();
			scope.current = scope.viewport();
		}
	}
})