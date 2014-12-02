.factory('niceDate',function(){
	return{
		format : function(d){

			var date     = new Date(d),
				day      = date.getDate(),
				months   = ["JANUARY","FEBRUARY","MARCH","APRIL","MAY","JUNE","JULY","AUGUST","SEPTEMBER","OCTOBER","NOVEMBER","DECEMBER"],
				m        = date.getMonth(),
				year     = date.getFullYear(),
				niceDate = day + " " + months[m]+ " " + year;

			return niceDate;
		}
	}
})