//***************************************************************************************************************************************************************
//**  FUNCTION SHIFTOVERVIEW(PARENTDIV,DATE,DAYS)
//**  Creates shiftOverview-component. Created by Tuukka Rintala 2016.
//***************************************************************************************************************************************************************

function shiftOverview(parentDiv,date,days,daypadding,elementY,elementX) {
	
	elementY						= parseInt(elementY);
	elementX						= parseInt(elementX);	
	
	var olddate						= date;
	date 							= revDate(date);
	
	daypadding						= parseInt(daypadding);
	
	var draggableDiv 				= document.createElement('div');
	
	draggableDiv.style.textAlign	= 'left';
	draggableDiv.innerHTML			= 'Yleisnäkymä';
	draggableDiv.className			= 'ui-widget-header';
	draggableDiv.style.padding		= '5px';
	
	//createShiftOverviewDraggable(parentDiv,draggableDiv);
	parentDiv.appendChild(draggableDiv);
	
	var component 					= document.createElement('table');
	component.id  					= 'shiftOverviewTable';
	component.style.backgroundColor = 'white';
	component.style.borderCollapse	= 'collapse';
	var data	  					= parentDiv.employeeData;
	
	createShiftOverviewHeader(component,date,days,daypadding);
	createShiftOverviewRows(component,data,days,daypadding);
	
	//console.log(component);
	//console.log('Datacount ' + parentDiv.shiftData.length);
	
	parentDiv.appendChild(component);

	setExistingOverviewMarkings(parentDiv.employeeData,parentDiv.shiftData);
	
	//$(parentDiv).offset({top : elementY, left : elementX});
	
}

//***************************************************************************************************************************************************************
//**  FUNCTION CREATESHIFTOVERVIEWHEADER(TABLE,DATE,DAYS)
//**  Creates header.
//***************************************************************************************************************************************************************

function createShiftOverviewHeader(table,date,days,daypadding) {
	
	var jsdate 		= new Date(date);
	var headerRow 	= table.insertRow();
		
	console.log('From ' + date + ' , starting month number = ' + jsdate.getMonth() + ' Starting month string = ' + jsdate);

	headerRow.insertCell();
	
	var dayspan = parseInt(days) + daypadding * 2;
	
	for(var index=0;index<dayspan;index++) {
		
		var newCell = headerRow.insertCell();
		
		newCell.date = jsdate.getFullYear()+'-'+addZero(jsdate.getMonth()+1)+'-'+addZero(jsdate.getDate());
		newCell.innerHTML = jsdate.getDate() + '.';
		
		//console.log('Creating from ' + jsdate + ' created ' + newCell.date + ' using ' + jsdate.getFullYear() + '-' + jsdate.getMonth() + '-' + jsdate.getDate());
		
		jsdate.setDate(jsdate.getDate() + 1);
		
		//console.log('Creating new column with date ' + newCell.date + ' with month number of ' + newCell.date.getMonth());
		//console.log('Created date ' + newCell.date);
		
	}
	
}

//***************************************************************************************************************************************************************
//** CREATESHIFTOVERVIEWROWS(TABLE,DATA,DAYS)
//** Creates overview-rows. 
//***************************************************************************************************************************************************************

function createShiftOverviewRows(table,data,days,daypadding) {

	for(var index in data) {
		
		var dataRow 	= data[index];
		
		var newRow 		= table.insertRow();
		
		var newCell		= newRow.insertCell();
		var colorIndex  = parseInt(dataRow['color']);
	
		//console.log("ColorIndex " + colorIndex + ' ' + colorVariables.colorsIntIndexed);
		
		newRow.employeeName		  	  		= dataRow['name'];
		newRow.employeeID					= dataRow['employeeID'];
		newRow.style.paddingBottom			= 0;
		newRow.style.paddingTop				= 0;
		
		var employeeDiv				  		= document.createElement('div');
		
		employeeDiv.innerHTML 		  		= dataRow['name'];
		employeeDiv.className		  		= 'draggableElement';
		employeeDiv.style.backgroundColor 	= colorVariables.colorsIntIndexed[colorIndex].normal;
		employeeDiv.style.borderColor	  	= colorVariables.colorsIntIndexed[colorIndex].dark;
		
		newCell.appendChild(employeeDiv);
		
		var dayspan   = parseInt(days) + daypadding  * 2;
		days		  = parseInt(days);
		
		for(var p=0;p<dayspan;p++) {
			
			//console.log(p + ',' + days);
			
			var newCell 	= newRow.insertCell();
			var cellIndex	= newCell.cellIndex;
			
			//var date		= table.rows[0].cells[cellIndex].date;
			//newCell.date    = date.getFullYear()+'-'+addZero(date.getMonth()+1)+'-'+addZero(date.getDate());
			
			newCell.date	=  table.rows[0].cells[cellIndex].date;
			
			//console.log(newCell.date);
			
			newCell.style.borderLeft	= 'solid white';
			newCell.style.borderWidth	= '0px 0px 0px 1px';
			
			if ((p<daypadding) || (p>=days+daypadding)) {
				newCell.style.backgroundColor = colorVariables.colors['lightgray'].normal;
			} else {
				newCell.style.backgroundColor = colorVariables.colors['lightgray'].light;
			}
			
			if ((p==daypadding) || (p==days+daypadding)) {
				newCell.style.borderLeft 	= 'solid black';
				newCell.style.borderWidth	= '0px 0px 0px 1px';		
			}
			
			newCell.innerHTML 				= "&nbsp&nbsp&nbsp&nbsp";
				
			// Person can work on more than one location, hence reservationCount for date.
			newCell.reservationCount		= 0;
			
		}
	}
}

//***************************************************************************************************************************************************************
//** FUNCTION RESERVEOVERVIEWDATA(EMPLOYEENAME,DATE)
//** Initiates reserving process.
//***************************************************************************************************************************************************************

function reserveOverviewDate(employeeID,date) {
	
	//date = date.getFullYear() + '-' + addZero(date.getMonth()) + '-' + addZero(date.getDate());
	//console.log(employeeName + '->' + date);
	
	var table		= document.getElementById('shiftOverviewTable');
	var rowCount	= table.rows.length;
	
	for(var index=0;index<rowCount;index++) {
		
		var compareTo = table.rows[index].employeeID;
		
		//console.log(compareTo + '?' + employeeID);
		
		if (employeeID == compareTo) {
			
			
			//console.log('FOUND MATCH SEARCHING DATE ' + date);
			
			searchAndReserveDate(table.rows[index],date);
			
		}
		
	}
	
}

//***************************************************************************************************************************************************************
//** FUNCTION SEARCHANDRESERVEDATA(TABLEROW,DATE)
//** Search and reserves date.
//***************************************************************************************************************************************************************

function searchAndReserveDate(tableRow,date) {
	
	var cellCount	= tableRow.cells.length;
	
	for(var index=0;index<cellCount;index++) {
		
		var cell = tableRow.cells[index];
		
		//console.log(cell.date + '?' + date);
		
		if (cell.date==date) {
		
			//console.log('FOUND MATCHING DATE');
			
			cell.style.backgroundColor = 'black';
			cell.reservationCount++;
		}
		
	}
	
}

//***************************************************************************************************************************************************************
//** FUNCTION SETEXISTINGOVERVIEWMARKINGS(EMPLOYEEDATA,SHIFTDATA)
//** Sets reservations from database.
//***************************************************************************************************************************************************************

function setExistingOverviewMarkings(employeeData,shiftData) {
	
	var len = shiftData.length;
	
	for(var index=0;index<len;index++) {
		
		var shiftRow	 = shiftData[index];
			
		var employeeID 	 = parseInt(shiftRow['employeeID']);
		
		//if (employeeID!=0) {
			
			var employeeRow  = employeeData[employeeID]; 
			var employeeID 	 = employeeRow['employeeID'];
			reserveOverviewDate(employeeID,shiftRow['date']);
		//}
	}
}

//***************************************************************************************************************************************************************
//** FUNCTION REMOVESHIFTOVERVIEWRESERVATION(EMPLOYEENAME,DATE)
//** Initiates freeing process.
//***************************************************************************************************************************************************************

function removeShiftOverviewReservation(employeeID,date) {
	
	var table		= document.getElementById('shiftOverviewTable');
	var rowCount	= table.rows.length;
	
	console.log('Trying to remove with employeeID ' + employeeID);
	
	for(var index=0;index<rowCount;index++) {
		
		var compareTo = table.rows[index].employeeID;
		
		if (employeeID == compareTo) {
			
			console.log('FOUND MATCHING EMPLOYEE ON REMOVE.');
			
			searchAndFreeReservationDate(table.rows[index],date);
			
		}
		
	}
	
}

//***************************************************************************************************************************************************************
//** FUNCTION SEARCHANDFREERESERVATIONDATA(TABLEROW,DATA)
//** Frees reservation.
//***************************************************************************************************************************************************************

function searchAndFreeReservationDate(tableRow,date) {
	
	var cellCount	= tableRow.cells.length;
	
	//console.log('Trying to free reservation for date ' + date);
	
	for(var index=0;index<cellCount;index++) {
		
		var cell = tableRow.cells[index];
		
		console.log(cell.date + '?' + date);
		
		if (cell.date==date) {
			
			console.log(cell.date + ' freed.');
			cell.reservationCount--;
		
			if (cell.reservationCount<=0) cell.style.backgroundColor = 'darkgray';
		}
		
	}
}


//***************************************************************************************************************************************************************
//** FUNCTION CREATESHIFTOVERVIEWDRAGGABLE(PARENTDIV,DRAGGABLEDIV)
//** Implements draggable.
//***************************************************************************************************************************************************************

function createShiftOverviewDraggable(parentDiv,draggableDiv) {
	
	$(draggableDiv).draggable({
		
		helper		: 'hide',
		appendTo 	: 'body',
		
		drag : function(event,ui) {
	
			var mouseX = event.clientX;
			var mouseY = event.clientY;
			
			$(parentDiv).offset({top : mouseY, left : mouseX});
			
		}
	});
} 


//***************************************************************************************************************************************************************
//**
//**
//***************************************************************************************************************************************************************

function revDate(date) {
	
	var jsdate = new Date(date);
	
	for(var rev=0;rev<14;rev++) jsdate.setDate(jsdate.getDate()-1);
	
	var retval = jsdate.getFullYear()+'-'+addZero(jsdate.getMonth()+1)+'-'+addZero(jsdate.getDate());
	
	return retval;
	
}
//***************************************************************************************************************************************************************
//**
//**
//***************************************************************************************************************************************************************

//************************************************************************************************************************************************************
//**  FUNCTION ADDZERO(timeBit)
//**  Add a following zero to timeBit if it consists of only one number.
//************************************************************************************************************************************************************

function addZero(timeBit) {
	
	if (timeBit<10) timeBit = '0' + timeBit;
	
	return timeBit;
	
}