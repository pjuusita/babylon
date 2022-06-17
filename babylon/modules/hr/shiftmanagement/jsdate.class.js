
//******************************************************************************************************************************************************
//***
//***
//***
//***
//***
//***
//***
//***
//***
//***
//***
//******************************************************************************************************************************************************

function JSDate(dateString,dateFormat,dayFormat,monthFormat) {
	
	this.dayNumber 		= null;
	this.dayName   		= null;
	
	this.weekNumber		= null;
	
	this.monthNumber 	= null;
	this.monthName	 	= null;
	
	this.yearNumber	 	= null;

	this.dateString 	= dateString;
	
	this.dateFormat 	= dateFormat;
	this.dayFormat	 	= dayFormat;
	this.monthFormat 	= monthFormat;
	
	
//******************************************************************************************************************************************************
//*** 	OBJECT FUNCTION SETDATESTRING(DATESTRING)
//***	Sets datestring yyyy-mm-dd of the object  
//******************************************************************************************************************************************************
	
	this.setDateString = function (dateString) {
		this.dateString = dateString;		
	};
	
//******************************************************************************************************************************************************
//***	OBJECT FUNCTION SETDATEFORMAT(DATEFORMAT)
//***	Sets format for toString output.
//******************************************************************************************************************************************************
		
	this.setDateFormat = function (dateFormat) {
		this.dateFormat = dateFormat;			
	};

//******************************************************************************************************************************************************
//***	OBJECT FUNCTION SETVALUES(DATESTRING)
//***	Sets remaining values according to dateString.
//******************************************************************************************************************************************************
			
	this.setValues = function (dateString) {
					
		var splitted = dateString.split("-");
		
		var yearNumber 		= splitted[0];
		var monthNumber	 	= splitted[1];
		var dayNumber	 	= splitted[2]; 
		
		this.yearNumber 	= yearNumber;
		this.monthNumber 	= monthNumber;
		this.dayNumber		= dayNumber;
	
		this.dayName		= this.getDayName(this.dayFormat);
		this.monthName		= this.getMonthName(this.monthFormat);
	
	};
		
//******************************************************************************************************************************************************
//***	OBJECT FUNCTION GETDAYNAME(DAYNUMBER)
//***	Returns string of day name.
//******************************************************************************************************************************************************
				
	this.getDayName = function (dayNumber,dayFormat) {

		if (dayFormat=='long')	 return dayNamesLong[dayNumber];
		if (dayFormat=='short')  return dayNamesShort[dayNumber];
		if (dayFormat=='tiny') 	 return dayNamesTiny[dayNumber];
			
	};
	
//******************************************************************************************************************************************************
//***	OBJECT FUNCTION GETMONTHNAME(MONTHNUMBER)
//***	Returns string of month name.
//******************************************************************************************************************************************************
					
	this.getMonthName = function (monthNumber,monthFormat) {
								
		if (monthFormat=='long')  return monthNamesLong[monthNumber];
		if (monthFormat=='short') return monthNamesShort[monthNumber];
		if (monthFormat=='tiny')  return monthNamesTiny[monthNumber];
						
	};
	
//******************************************************************************************************************************************************
//***	OBJECT FUNCTION TOSTRING()
//***	Returns toStringed string of date.
//******************************************************************************************************************************************************
					
	this.toString = function () {
								
		return dateString;
				
	};
	
}