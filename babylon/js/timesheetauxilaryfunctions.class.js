//****************************************************************************************************************************************************
//** GETROWTYPE(DATAROW)
//** Returns rowType contained in TimeSheettRowIdentifier.
//****************************************************************************************************************************************************

function getRowType(dataRow) {
	
	for(var dataIndex in dataRow) {
		
		dataObject = dataRow[dataIndex];
		
		if (dataObject.getClass()=='TimeSheetRowIdentifier') {
			
			return dataObject.getType();
		}
		
	}
	
}