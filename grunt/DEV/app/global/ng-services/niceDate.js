.factory('niceDate',function(){
	return{
		format : function(d){

			var date     = new Date(d),
				day      = date.getDate(),
				months   = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
				m        = date.getMonth(),
				year     = date.getFullYear(),
				niceDate = day + " " + months[m]+ " " + year;

			return niceDate;
		}
	}
})