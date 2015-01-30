.factory('niceDate',function(){
	return{
		testobj : {},
		format  : function(d){
			
			if (d) {

				var dateTime  = d.split(" "),
				    dateItems = dateTime[0].split("-"),
				    timeItems =  dateTime[1].split(":");

				var y  = Number(dateItems[0]), // year
					m  = Number(dateItems[1]) - 1, // month
					da = Number(dateItems[2]); // day

				var h  = Number(timeItems[0]), // hour
					mn = Number(timeItems[1]),	// minutes
					s  = Number(timeItems[2]);	// seconds

				this.testobj = {
					"year"    : y,
					"month"   : m + 1,
					"date"    : da,
					"hours"   : h,
					"mins"    : mn,
					"seconds" : s
  				}


				var date     = new Date(y, m, da, h, mn, s),
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