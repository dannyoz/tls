.factory('niceDate',function(){
	return{
		format : function(d){
			
			if (d) {

				var dateTime  = d.split(" "),
				    dateItems = dateTime[0].split("-"),
				    timeItems =  dateTime[1].split(":");

				var y = dateItems[0], // year
					m = dateItems[1], // month
					d = dateItems[2]; // day

				var h = timeItems[0], // hour
					mn = timeItems[1],	// minutes
					s = timeItems[2];	// seconds


				var date     = new Date(y, m, d, h, mn, s),
					day      = date.getDate(),
					months   = ["JANUARY","FEBRUARY","MARCH","APRIL","MAY","JUNE","JULY","AUGUST","SEPTEMBER","OCTOBER","NOVEMBER","DECEMBER"],
					m        = date.getMonth(),
					year     = date.getFullYear(),
					niceDate = months[m]+ " " + day + " " + year;
				
				return niceDate;

			}
		}
	}
})