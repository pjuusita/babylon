
//****************************************************************************************************************************************************
//	FUNCTION CALCULATEHOURS(DATAROWS)
//	Calculates hours from dataRows.
//****************************************************************************************************************************************************

function calculateHours(dataRows) {

	// Tunnit
	var viikkotunnit = [];
	var listatunnit = [];
	
	// Käydään läpi kaikki rivit.
	for(var rowIndex in dataRows) {
		
		var dataRow = dataRows[rowIndex];
		
		// Käydään läpi rivin sisäinen data.
		for(var dataIndex in dataRow) {
			
			// Haetaan tyyppiä TimeSpanColumn olevat objektit, joihin lasketaan tuntimäärät.
			if (dataRow[dataIndex].getClass() == 'TimeSpanColumn') {
				
				var timeSpan = getHours(dataRow);
				var columnName = dataRow[dataIndex].getName();
				dataRow[dataIndex].setValue(timeSpan);
				
				// Alustus, jos null.
				if (viikkotunnit[columnName]==null) viikkotunnit[columnName] = 0;
				if (listatunnit[columnName]==null) listatunnit[columnName] = 0;
				
				// Yhteenlasku
				viikkotunnit[columnName] = parseFloat(viikkotunnit[columnName]) + parseFloat(timeSpan);
				listatunnit[columnName]	= parseFloat(listatunnit[columnName]) + parseFloat(timeSpan);
				
				// Jos on tyyppiä weekly, tiedetään että objekti edustaa viikkosummaa.
				if (dataRow[dataIndex].getType() == 'weekly') {
					dataRow[dataIndex].setValue(viikkotunnit[columnName].toFixed(2));
					viikkotunnit[columnName] = 0;
				}
				
				// Jos on tyyppia sheettotal, tiedetään että objekti edustaa koko listan summaa.
				if (dataRow[dataIndex].getType() == 'sheettotal') {
					dataRow[dataIndex].setValue(listatunnit[columnName].toFixed(2));
				}
			}
		}
	}
	
	saveDataRowsToDatabase(dataRows);
	
}


//****************************************************************************************************************************************************
//	GETHOURS(DATAROW)
//	Gets and converts timeSelect-values to actual time.
//****************************************************************************************************************************************************

function getHours(dataRow) {

	//Käydään läpi rivin objektit.
	for(var dataIndex in dataRow) {
		
		// Etsitään tyyppiä TimeSelectColumn, josta voidaan hakea työaika.
		if (dataRow[dataIndex].getClass() == 'TimeSelectColumn') {
			
			// Haetaan objektin time objekti.
			var time = dataRow[dataIndex].getSelectedObject();
			
			// Muutetaan minuuteiksi
			var startHours = parseInt(time.starthours,10) * 60;
			var startMinutes = parseInt(time.startminutes);
			var startTime	 = startHours + startMinutes;
			
			var endHours	 = parseInt(time.endhours,10) * 60;
			var endMinutes	 = parseInt(time.endminutes,10)
			var endTime		 = endHours + endMinutes;
			
			// Palauteen tunneiksi desimaalimuotoon
			var hours = (endTime - startTime) / 60;
			
			// Jos ylitetään vuorokausiraja, lasketaan tunnit huomioimalla vuorokausiraja
			if (hours<0) hours = 24 + hours;
			
			hours = hours.toFixed(2);
			
			// Palautetaan tunnit desimaalimuodossa.
			return hours;
		}
	}
	
	// Jos ei löytynyt palautetaan 0 tuntia.
	return 0;
}

//****************************************************************************************************************************************************
//	FUNCTION SAVEDATAROWSTODATABASE(DATAROWS)
//	Saves updated dataRows to database.
//****************************************************************************************************************************************************

function saveDataRowsToDatabase(dataRows) {
	
	for(var dataIndex in dataRows) {
		
		dataRow = dataRows[dataIndex];

		if (dataRow[0].getClass()=='TimeSheetEmptyColumn') continue;
		
		var startHour;
		var starMinute;
		var endHour;
		var endMinute;
		var rowDate;
		var projectID;
		var taskID;
		var shiftID;
		var timesheetRowID 				 = null;
		var timesheetRowIdentifierObject = null;
		
		for(var index in dataRow) {
			
			dataObject = dataRow[index];
			
			
			if (dataObject.getName()=='TimesheetRowIdentifier') {
				timesheetRowIdentifierObject = dataObject;
				timesheetRowID = timesheetRowIdentifierObject.getValue();
			}
			
			if (dataObject.getName()=='Päivämäärä') {
				
				var dateObject = dataObject.getDate();
				rowDate 	   = dateObject.dateString;
			}
			
			if (dataObject.getName()=='Työaika') {
				
				var timeObject = dataObject.getSelectedObject();
				
				startHour 	= timeObject.starthours;
				startMinute = timeObject.startminutes;
				endHour		= timeObject.endhours;
				endMinute	= timeObject.endminutes;
			
			}
			
			if (dataObject.getName()=='Projekti') {
				
				var projectObject = dataObject.getSelectedObject();
				if (projectObject!=null) projectID = projectObject.data['projectID'];
				
			}
			
			if (dataObject.getName()=='Työtehtävä') {
				
				var taskObject = dataObject.getSelectedObject();
				if (taskObject!=null) taskID = taskObject.data['taskID'];
				
			}
			
			if (dataObject.getName()=='Vuoro') {
				
				shiftObject = dataObject.getSelectedObject();
				if (shiftObject!=null) shiftID = shiftObject.data['shiftID'];
				 
			}	
		}
	}
}


