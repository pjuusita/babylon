//********************************************************************************************************************************************************
//*** FUNCTION PRESERVESINGLEDATES(FROMINDEX)
//*** Allows only removal of double dates.
//********************************************************************************************************************************************************
																											
function PreserveSingleDates(fromIndex) {																					
																																					
	var sheet = document.getElementById('sheet');																						
	var dataRow = dataRows[fromIndex];																									
																																				
	var compareTo;
	var compareToName;																													
	var compareIndex;
	var matches = [];																													
			
	// Seeks RemoveColumn to get necessary data.
	
	for(var column in dataRow) { 																										
																																					
		if(dataRow[column].getClass()=='TimeSheetRemoveColumn') { 																																																			
			compareTo = dataRow[column].getCompareTo();																					
			compareToName = dataRow[column].getCompareToName();
			compareIndex  = fromIndex;
		}																																
	}																																	
		
	// Counts matches by pushing objects to an array.
	
	var firstMatch = null;
	
	for(var dataIndex in dataRows) {																									
																																					
		var dataRow = dataRows[dataIndex];																								
																																					
			for(var column in dataRow) { 																									
																																					
				if (compareToName == dataRow[column].getName()) {																			
																																					
					if (compareTo.getValue()==dataRow[column].getValue()) {
						if (firstMatch==null) firstMatch = dataIndex;
						matches.push(dataRow[column]);														
					}																															
				}																															
			}																																																																		
		}																																	
									
	// Returns false if only one match.
	if (matches.length<=1) return false;																										
				
	var date = compareTo.getDate();
	
	// Update if original row with visible date was removed.
	if (firstMatch==compareIndex) matches[1].setValue(date.dateString,compareTo.dateFormat);
	
	// Returns true on multiple matches.
	return true;																														
																																					
}						

//********************************************************************************************************************************************************
//*** FUNCTION PRESERVESINGLEVALUES(FROMINDEX)
//*** Allows only removal of double values.
//********************************************************************************************************************************************************
																											
function PreserveSingleValues(fromIndex) {																					
																																					
	var sheet = document.getElementById('sheet');																						
	var dataRow = dataRows[fromIndex];																									
																																				
	var compareTo;
	var compareToName;																													
	var compareIndex;
	var matches = [];																													
			
	// Seeks RemoveColumn to get necessary data.
	
	for(var column in dataRow) { 																										
																																					
		if(dataRow[column].getClass()=='TimeSheetRemoveColumn') { 																																																			
			compareTo = dataRow[column].getCompareTo();																					
			compareToName = dataRow[column].getCompareToName();
			compareIndex  = fromIndex;
		}																																
	}																																	
		
	// Counts matches by pushing objects to an array.
	
	var firstMatch = null;
	
	for(var dataIndex in dataRows) {																									
																																					
		var dataRow = dataRows[dataIndex];																								
																																					
			for(var column in dataRow) { 																									
																																					
				if (compareToName == dataRow[column].getName()) {																			
																																					
					if (compareTo.getValue()==dataRow[column].getValue()) {
						if (firstMatch==null) firstMatch = dataIndex;
						matches.push(dataRow[column]);														
					}																															
				}																															
			}																																																																		
		}																																	
									
	// Returns false if only one match.
	if (matches.length<=1) return false;																										

	return true;																														
																																					
}	


//********************************************************************************************************************************************************
//*** FUNCTION ALWAYSREMOVE(FROMINDEX)
//*** Always remove.
//********************************************************************************************************************************************************
		
function AlwaysRemove(fromIndex) {
	
	return true;
	
}


																																					
		