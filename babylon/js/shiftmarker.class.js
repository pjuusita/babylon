//********************************************************************************************************************************************************************
//** FUNCTION CREATESHIFTMARKER(draggable,event,droppedOnTable)
//** Creates a new shiftmarker. Created by Tuukka Rintala 2016.
//********************************************************************************************************************************************************************

function createShiftMarker(draggable,event,droppedOnTable) {
	
	var droppedElement		 			= draggable.context;
	var droppedOn		 	 			= event.target;
	var containmentRect 	 			= droppedOn.getBoundingClientRect();
	
	var shiftMarker 	 	 			= document.createElement('div');
	
	shiftMarker.selected	 			= false;
	shiftMarker.date		 			= droppedOn.parentNode.parentNode.date;
	
	shiftMarker.parentTable  			= droppedOn.parentNode.parentNode.parentNode.parentNode;
	
	shiftMarker.updateAction 			= shiftMarker.parentTable.updateAction;
	shiftMarker.reserveAction 			= shiftMarker.parentTable.reserveAction;
	shiftMarker.deleteAction	 		= shiftMarker.parentTable.deleteAction;
	
	shiftMarker.className				= 'shiftMarker'; 
	
	var shiftMarkerTable 				= document.createElement('table');
	
	shiftMarker.style.position 			= 'absolute';
	shiftMarker.style.width	   			= '350px';
	
	shiftMarker.height					= shiftMarker.style.height;
	shiftMarker.timeStep				= droppedOnTable.timeStep;
	shiftMarker.hours					= droppedOnTable.hours;
	shiftMarker.imgWidth				= droppedOnTable.imgWidth;
	
	var dropCoords		 				= droppedOn.getBoundingClientRect();
	var timeSteppedX	 				= parseInt(event.clientX / shiftMarker.timeStep) * shiftMarker.timeStep;
	
	$(shiftMarker).offset({top : 0 , left : timeSteppedX})
	
	createShiftMarkerTable(shiftMarker,shiftMarkerTable,droppedOn,droppedElement,containmentRect);
		
	shiftMarker.appendChild(shiftMarkerTable);
	
	var employee = null;
	
	if (shiftMarker.employeeData!=null) 	employee = shiftMarker.employeeData;
	if (droppedOnTable.groupBy=='employee') employee = droppedOnTable.primaryData; 
	
	var date							= shiftMarker.date;
	var dateString						= date.getFullYear() + '-' + addZero(date.getMonth()) + '-' + addZero(date.getDate());
	
	//console.log('Trying to create from ' + employee + ':' + dateString);
	
	var defaultEmployeeID = 0;
	
	if ((reserveOverviewDate!=null) && (employee!=null)) reserveOverviewDate(employee['employeeID'],dateString);
	if (employee==null) reserveOverviewDate(defaultEmployeeID,dateString);
	
	//updateTime(shiftMarker,containmentRect);
	
	return shiftMarker;
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION CREATEEXISTINGSHIFTMARKER(employeeData,taskDaata,shiftMarkerData,parentTable)
//**  Creates existing shiftMarker.
//********************************************************************************************************************************************************************

function createExistingShiftMarker(employeeData,taskData,projectData,shiftMarkerData,shiftTableDiv) {
	
	var shiftMarker 	 	 	= document.createElement('div');
	var parentTable				= shiftTableDiv.childNodes[0];
	var primaryData				= shiftTableDiv.primaryData;
	
	shiftMarker.employeeData	= employeeData;
	shiftMarker.taskData		= taskData;
	shiftMarker.projectData		= projectData;
	
	shiftMarker.parentTable		= parentTable;
	
	shiftMarker.selected	 	= false;
	
	var rowCount				= parentTable.rows.length;
	var searchDate				= shiftMarkerData['date'];
	var parentRow;
	var found					= false;
	
	for(var index=0;index<rowCount;index++) {
		
		var row = parentTable.rows[index];
			
		if ((row.dateString==searchDate) && (found!=true)) {
			
			parentRow = row;
			found	  = true;
		
		}
		
	} 
	
	if (!found) return;
		
	if (parentRow.reserved==true) {
		
		createDropBetweenTableRow(parentTable,parentRow.date,parentRow.bgImage,parentRow.rowIndex+2);
		parentRow	 = createShiftTableRow(parentTable,parentRow.date,parentRow.bgImage,parentRow.rowIndex+2);
		
	}
	
	parentRow.reserved	= true;
	var droppedOn 		= parentRow.childNodes[1].childNodes[0];

	shiftMarker.id				= shiftMarkerData['shiftmarkerID'];
	shiftMarker.date		 	= parentRow.date;
	
	shiftMarker.updateAction 	= shiftMarker.parentTable.updateAction;
	shiftMarker.reserveAction 	= shiftMarker.parentTable.reserveAction;
	shiftMarker.deleteAction	= shiftMarker.parentTable.deleteAction;
		
	shiftMarker.className	= 'shiftMarker'; 
	
	var shiftMarkerTable = document.createElement('table');
	
	shiftMarker.style.position 			= 'absolute';
	shiftMarker.style.width	   			= '350px';
	
	var color							= null;
	
	if (parentTable.colorBy=='project') {
	
		if (projectData!=null) {
		
			var colorIndex = parseInt(projectData['color']);
			color = colorVariables.colorsIntIndexed[colorIndex];
	
		} else {
			
			if (parentTable.groupBy=='project') {
				shiftMarker.projectData = primaryData;
				color = colorVariables.colorsIntIndexed[parseInt(primaryData['color'])];
			} else {
				color = colorVariables.colorsIntIndexed[0];
			}
			
		}
	}
	
	if (parentTable.colorBy=='task') {
		
		if (taskData!=null) {
			
			var colorIndex = parseInt(taskData['color']);
			color = colorVariables.colorsIntIndexed[colorIndex];
			
		} else {
			
			if (parentTable.groupBy=='task') {
				shiftMarker.taskData = primaryData;
				color = colorVariables.colorsIntIndexed[parseInt(primaryData['color'])];
			} else {
				color = colorVariables.colorsIntIndexed[0];
			}
		} 
	}
	
	if (parentTable.colorBy=='employee') {
		
		if (employeeData!=null) {
			
			var colorIndex = parseInt(employeeData['color']);
			color = colorVariables.colorsIntIndexed[colorIndex];
			
		} else {
			
			if (parentTable.groupBy=='employee') {
				shiftMarker.employeeData = primaryData;
				color = colorVariables.colorsIntIndexed[parseInt(primaryData['color'])];
			} else {
				color = colorVariables.colorsIntIndexed[0];
			}
		}
	}

	shiftMarker.style.backgroundColor	= color.normal;
	shiftMarker.style.borderColor		= color.dark;
	
	shiftMarker.backgroundColor			= color.normal;
	shiftMarker.borderColor				= color.dark;
	shiftMarker.originalBorderColor		= color.dark;
		
	shiftMarker.height					= shiftMarker.style.height;
	shiftMarker.timeStep				= parentTable.timeStep;
	shiftMarker.hours					= parentTable.hours;
	shiftMarker.imgWidth				= parentTable.imgWidth;
	
	var dropCoords		 				= droppedOn.getBoundingClientRect();
	var containmentRect 				= droppedOn.getBoundingClientRect();
	
	$(shiftMarker).offset({top : 0 , left : 0})
	
	createShiftMarkerTableExisting(employeeData,taskData,projectData,shiftMarkerData,droppedOn,shiftMarker,shiftMarkerTable);
		
	shiftMarker.appendChild(shiftMarkerTable);
	
	determineSize(shiftMarker,containmentRect);
	
	droppedOn.appendChild(shiftMarker);
	
	//if (reserveOverviewDate!=null) reserveOverviewDate(employeeData['name'],shiftMarker.date);
	
	return shiftMarker;
	
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION CREATESHIFTMARKERTABLE(shiftMarker,shiftMarkerTable,droppedOn,droppedElement,containmentRect)
//**  Creates table inside shiftMarker.
//********************************************************************************************************************************************************************

function createShiftMarkerTableExisting(employeeData,taskData,projectData,shiftMarkerData,droppedOn,shiftMarker,shiftMarkerTable) {
	
	var containmentRect						= droppedOn.getBoundingClientRect();
	
	shiftMarkerTable.style.width  		  	= '100%';
	shiftMarkerTable.style.borderCollapse 	= 'collapse';
	shiftMarkerTable.style.padding		  	= '0px';
	shiftMarkerTable.style.borderStyle 	  	= 'none none none none';
	shiftMarkerTable.style.borderWidth 	  	= '0px';
	
	var row								  	= shiftMarkerTable.insertRow();

	var leftHandle  					  	= row.insertCell();
	var nameArea    						= row.insertCell();
	var taskArea   							= row.insertCell();
	var timeArea							= row.insertCell();
	var projectArea							= row.insertCell();
	var deleteArea							= row.insertCell();
	var rightHandle 						= row.insertCell();
	
	nameArea.innerHTML 						= 'työntekijä';
	taskArea.innerHTML 						= 'työtehtävä';
	projectArea.innerHTML					= 'työkohde';
	leftHandle.innerHTML 					= 'O';
	rightHandle.innerHTML 					= 'O';
	deleteArea.innerHTML					= 'X';
	
	leftHandle.style.textAlign 				= 'left';
	rightHandle.style.textAlign 			= 'right';
	
	createLeftHandle(leftHandle,droppedOn,shiftMarker);
	createRightHandle(rightHandle,droppedOn,shiftMarker);
	createDroppableTaskArea(taskArea,shiftMarker,droppedOn);
	createDroppableNameArea(nameArea,shiftMarker,droppedOn);
	createTimeAreaDivs(shiftMarker,timeArea,containmentRect);
	createDroppableProjectArea(projectArea,shiftMarker,droppedOn);
	createDeleteArea(deleteArea,shiftMarker);
	
	shiftMarker.nameArea					= nameArea;
	shiftMarker.taskArea					= taskArea;
	shiftMarker.timeArea					= timeArea;
	shiftMarker.deleteArea					= deleteArea;
	
	shiftMarker.employeeData				= employeeData;
	
	if (employeeData!=null) nameArea.innerHTML = employeeData['name'];
	if (employeeData==null) nameArea.innerHTML = "työntekijä";	
	
	shiftMarker.taskData					= taskData;	
	
	if (taskData!=null) taskArea.innerHTML	= taskData['name'];
	if (taskData==null) taskArea.innerHTML  = "työtehtävä";	
	
	shiftMarker.projectData					= projectData;
	
	if (projectData!=null) projectArea.innerHTML = projectData['name'];
	if (projectData==null) projectArea.innerHTML = "työkohde";
	
	shiftMarker.bigTime.childNodes[0].value = shiftMarkerData['starthour'];
	shiftMarker.bigTime.childNodes[1].value = shiftMarkerData['startminute'];
	shiftMarker.bigTime.childNodes[2].value = shiftMarkerData['endhour'];
	shiftMarker.bigTime.childNodes[3].value = shiftMarkerData['endminute'];
	
	var startHour 							= addZero(shiftMarkerData['starthour']);
	var startMinute							= addZero(shiftMarkerData['startminute']);
	var endHour								= addZero(shiftMarkerData['endhour']);
	var endMinute							= addZero(shiftMarkerData['endminute']);
	
	shiftMarker.smallTime.innerHTML = startHour + ':' + startMinute + '-' + endHour + ':' + endMinute;
	 
	$(row).click(function() { 
		
		shifts = document.getElementsByClassName('shiftMarker');
			
		for(var index=0;index<shifts.length;index++) {
			
			shifts[index].style.borderColor = shifts[index].originalBorderColor;
			shifts[index].style.height		= shifts[index].originalHeight;
			shifts[index].selected 			= false;
			
			$(shifts[index].deleteArea).hide();
			$(shifts[index].bigTime).hide();
			$(shifts[index].smallTime).show();
				
		}
	
		row.selected = true;
		
		shiftMarker.style.borderColor = 'black';
		
		$(shiftMarker.deleteArea).show();
		
		$(shiftMarker.bigTime).show();
		$(shiftMarker.smallTime).hide();
		
	});
	
}
	
//********************************************************************************************************************************************************************
//**  FUNCTION CREATESHIFTMARKERTABLE(shiftMarker,shiftMarkerTable,droppedOn,droppedElement,containmentRect)
//**  Creates table inside shiftMarker.
//********************************************************************************************************************************************************************

function createShiftMarkerTable(shiftMarker,shiftMarkerTable,droppedOn,droppedElement,containmentRect) {

	shiftMarkerTable.style.width  		  	= '100%';
	shiftMarkerTable.style.borderCollapse 	= 'collapse';
	shiftMarkerTable.style.padding		  	= '0px';
	shiftMarkerTable.style.borderStyle 	  	= 'none none none none';
	shiftMarkerTable.style.borderWidth 	  	= '0px';
	
	var row								  	= shiftMarkerTable.insertRow();

	var leftHandle  					  	= row.insertCell();
	var nameArea    						= row.insertCell();
	var taskArea   							= row.insertCell();
	var timeArea							= row.insertCell();
	var projectArea							= row.insertCell();
	var deleteArea							= row.insertCell();
	var rightHandle 						= row.insertCell();
	
	nameArea.innerHTML 						= 'työntekijä';
	taskArea.innerHTML 						= 'työtehtävä';
	projectArea.innerHTML					= 'työkohde';
	leftHandle.innerHTML 					= 'O';
	rightHandle.innerHTML 					= 'O';
	deleteArea.innerHTML					= 'X';
	
	leftHandle.style.textAlign 				= 'left';
	rightHandle.style.textAlign 			= 'right';
	
	createLeftHandle(leftHandle,droppedOn,shiftMarker);
	createRightHandle(rightHandle,droppedOn,shiftMarker);
	createDroppableTaskArea(taskArea,shiftMarker,droppedOn);
	createDroppableNameArea(nameArea,shiftMarker,droppedOn);
	createTimeAreaDivs(shiftMarker,timeArea,containmentRect);
	createDroppableProjectArea(projectArea,shiftMarker,droppedOn);
	createDeleteArea(deleteArea,shiftMarker);
	
	shiftMarker.nameArea					= nameArea;
	shiftMarker.taskArea					= taskArea;
	shiftMarker.timeArea					= timeArea;
	shiftMarker.projectArea					= projectArea;
	shiftMarker.deleteArea					= deleteArea;
	
	if (droppedElement.draggableClass=='employee') {
		
		shiftMarker.employeeData	= droppedElement.data;
		nameArea.innerHTML		 	= droppedElement.innerHTML;
		
	}
	
	if (droppedElement.draggableClass=='task') {
			
		shiftMarker.taskData	= droppedElement.data;	
		taskArea.innerHTML		= droppedElement.innerHTML;
		
	}
	
	if (droppedElement.draggableClass=='project') {
		
		shiftMarker.projectData	= droppedElement.data;
		projectArea.innerHTML	= droppedElement.innerHTML;
		
	}
	
	$(row).click(function() { 
		
		shifts = document.getElementsByClassName('shiftMarker');
			
		for(var index=0;index<shifts.length;index++) {
		
			shifts[index].style.borderColor = shifts[index].originalBorderColor;
			shifts[index].style.height		= shifts[index].originalHeight;
			shifts[index].selected 			= false;
			
			$(shifts[index].bigTime).hide();
			$(shifts[index].smallTime).show();
				
		}
	
		row.selected = true;
		
		shiftMarker.style.borderColor = 'black';
		
		$(shiftMarker.bigTime).show();
		$(shiftMarker.smallTime).hide();
		
	});
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION CREATETIMEAREADIVS(shiftMarker,timeArea,containmentRect)
//**  Creates time-controls for shiftMarkerTable.
//********************************************************************************************************************************************************************

function createTimeAreaDivs(shiftMarker,timeArea,containmentRect) {
	
	var smallTime	= document.createElement('div');
	var bigTime		= document.createElement('div');
	var timeStep	= shiftMarker.timeStep;
	
	smallTime.innerHTML = '00:00-00:00';
	
	var startHour 	= document.createElement('select');
	var startMinute	= document.createElement('select');
	var endHour		= document.createElement('select');
	var endMinute	= document.createElement('select');
	
	createHours(startHour,timeStep,shiftMarker,containmentRect);
	createMinutes(startMinute,timeStep,shiftMarker,containmentRect);
	
	createHours(endHour,timeStep,shiftMarker,containmentRect);
	createMinutes(endMinute,timeStep,shiftMarker,containmentRect);
	
	bigTime.appendChild(startHour);
	bigTime.appendChild(startMinute);
	bigTime.appendChild(endHour);
	bigTime.appendChild(endMinute);
	
	timeArea.appendChild(smallTime);
	timeArea.appendChild(bigTime);
	
	shiftMarker.smallTime = smallTime;
	shiftMarker.bigTime	  = bigTime;
	
	$(bigTime).hide();
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION CREATEDROPPABLENAMEAREA(nameArea)
//**  Creates an area where employee can be dropped.
//********************************************************************************************************************************************************************

function createDroppableNameArea(nameArea,shiftMarker,droppedOn) {
	
	$(nameArea).droppable({
		
		drop : function(event,ui) {
				
			var droppedElement = ui.draggable.context;
			var parentDiv 	   = this.parentNode.parentNode.parentNode.parentNode;
			var colorBy		   = droppedOn.parentNode.parentNode.parentNode.parentNode.colorBy;
				
			
			if (droppedElement.draggableClass!='employee') {
				console.log('Failed to drop, wrong draggableClass.');
				return;
			}
			
			var oldEmployee			= parentDiv.employeeData;
			
			parentDiv.employeeData = droppedElement.data;
			nameArea.innerHTML	   = droppedElement.innerHTML;
			
			if (colorBy = 'employee') {
	
				parentDiv.style.backgroundColor 	= droppedElement.backgroundColor;
				parentDiv.style.borderColor			= droppedElement.borderColor;
				parentDiv.originalBorderColor		= droppedElement.borderColor;
				
				shiftMarker.backgroundColor 		= droppedElement.backgroundColor;
				shiftMarker.borderColor				= droppedElement.borderColor;
				shiftMarker.originalBorderColor 	= droppedElement.borderColor;
				
			}
		
			updateShiftMarkerData(shiftMarker);
			
			var employee						= parentDiv.employeeData;
			var date							= shiftMarker.date;
			var dateString						= date.getFullYear() + '-' + addZero(date.getMonth()) + '-' + addZero(date.getDate());
			
			console.log('Trying to reserve for ' + employee + ':' + dateString + '. Trying to remove from ' + oldEmployee);
			
			if ((reserveOverviewDate!=null)) {
				
				defaultEmployeeID = 0;
				
				if ((oldEmployee!=null) && (employee!=null)) {
					removeShiftOverviewReservation(oldEmployee['employeeID'],dateString);
					reserveOverviewDate(employee['employeeID'],dateString);
				}
				
				if ((oldEmployee==null) && (employee!=null)) {
					removeShiftOverviewReservation(defaultEmployeeID,dateString);
					reserveOverviewDate(employee['employeeID'],dateString);
				}
				
				if (employee==null) {
					reserveOverviewDate(defaultEmployeeID,dateString);
					console.log('Trying to reserve for employeeID = 0');
				}
			}	
		}
		
	});
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION CREATEDROPPABLETASKAREA(taskArea)
//**  Creates an area where task can be dropped.
//********************************************************************************************************************************************************************

function createDroppableTaskArea(taskArea,shiftMarker,droppedOn) {
	
	$(taskArea).droppable({
		
		drop : function(event,ui) {
				
			var droppedElement = ui.draggable.context;
			var parentDiv 	   = this.parentNode.parentNode.parentNode.parentNode;
			var colorBy		   = droppedOn.parentNode.parentNode.parentNode.parentNode.colorBy;
			
			
			if (droppedElement.draggableClass!='task') {
				console.log('Failed to drop, wrong draggableClass.');
				return;
			}
			
			shiftMarker.taskData	= droppedElement.data;
			taskArea.innerHTML	   	= droppedElement.innerHTML;
			
			if (colorBy == 'task') {
				
				parentDiv.style.backgroundColor 	= droppedElement.backgroundColor;
				parentDiv.style.borderColor			= droppedElement.borderColor;
				parentDiv.originalBorderColor 		= droppedElement.borderColor;
				
				shiftMarker.backgroundColor 		= droppedElement.backgroundColor;
				shiftMarker.borderColor				= droppedElement.borderColor;
				shiftMarker.originalBorderColor 	= droppedElement.borderColor;
				
			}
		
			updateShiftMarkerData(shiftMarker);
			
		}
		
	});
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION CREATEDROPPABLEPROJECTAREA(projectArea,shiftMarker)
//**  Creates an area where task can be dropped.
//********************************************************************************************************************************************************************

function createDroppableProjectArea(projectArea,shiftMarker,droppedOn) {
	
	$(projectArea).droppable({
		
		drop : function(event,ui) {
				
			var droppedElement = ui.draggable.context;
			var parentDiv 	   = this.parentNode.parentNode.parentNode.parentNode;
			var colorBy		   = droppedOn.parentNode.parentNode.parentNode.parentNode.colorBy;
			
			if (droppedElement.draggableClass!='project') {
				console.log('Failed to drop, wrong draggableClass.');
				return;
			}
		
			shiftMarker.projectData		= droppedElement.data;
			projectArea.innerHTML	   	= droppedElement.innerHTML;
			
			
			if (colorBy=='project') {
				
				parentDiv.style.backgroundColor 	= droppedElement.backgroundColor;
				parentDiv.style.borderColor			= droppedElement.borderColor;
				parentDiv.originalBorderColor 		= droppedElement.borderColor;
				
				shiftMarker.backgroundColor 		= droppedElement.backgroundColor;
				shiftMarker.borderColor				= droppedElement.borderColor;
				shiftMarker.originalBorderColor 	= droppedElement.borderColor;
				
			}
		
			updateShiftMarkerData(shiftMarker);
			
		}
		
	});
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION CREATELEFTHANDLE(leftHandle,droppedOn,shiftMarker)
//**  Creates left size-position drag-handle  
//********************************************************************************************************************************************************************

function createDeleteArea(deleteArea,shiftMarker) {
	
	$(deleteArea).click(function() {
		
		console.log('Trying to delete shiftMarker ' + shiftMarker.id);
		
		var parameters = "&shiftmarkerid=" + shiftMarker.id;
		var employee   = shiftMarker.employeeData;
		var date	   = shiftMarker.date;
		var dateStr	   =  date.getFullYear() + "-"  + addZero(date.getMonth()) + "-" + addZero(date.getDate());
		
		//console.log('DATE ' + dateStr);
		
		$.getJSON(shiftMarker.deleteAction, parameters, function() {
			
		}).done(function(reply) { 
			
			console.log('Deleted shiftMarker row with shiftMarkerID' + shiftMarker.id);
			
			//if ((removeShiftOverviewReservation!=null) && (employee!=null))  removeShiftOverviewReservation(employee['employeeID'],date);
			
			removeShiftOverviewReservation(employee['employeeID'],dateStr);
			
			shiftMarker.parentNode.removeChild(shiftMarker);
		});	
		
	});
	
	$(deleteArea).hide();
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION CREATELEFTHANDLE(leftHandle,droppedOn,shiftMarker)
//**  Creates left size-position drag-handle  
//********************************************************************************************************************************************************************

function createLeftHandle(leftHandle,droppedOn,shiftMarker) {
	
	var containmentDiv	= droppedOn;
		
	$(leftHandle).draggable( {
				
			helper		: 'hide',
			appendTo 	: 'body',
			containment : containmentDiv,
			
			drag : function(event,ui) {
				
				var containmentRect = containmentDiv.getBoundingClientRect();
				var boundingRect	= shiftMarker.getBoundingClientRect();
				
				var mouseX		= 	event.clientX;
				var mouseY		= 	event.clientY;
				
				var origX		=	boundingRect.left;
				var diff		= 	origX - mouseX;
				var origW		= 	parseFloat(shiftMarker.style.width);
				var newW		= 	origW + diff;
				
				console.log(origX + ':' + mouseX);
				
				shiftMarker.style.width = newW + 'px';
				$(shiftMarker).offset({top : containmentDiv.top, left : mouseX});

				updateTime(shiftMarker,containmentRect);
				
		},
		
		start : function(event,ui) {
			
			var boundingRect 			= shiftMarker.getBoundingClientRect();
					
			shiftMarker.originalLeft 	= boundingRect.left;
			shiftMarker.originalRight	= boundingRect.right;
			
		},
		
		stop : function(event,ui) {
					
			var containmentRect = 	containmentDiv.getBoundingClientRect();
			
			var mouseX			= 	event.clientX;
			var mouseY			= 	event.clientY;
			var timeStep		= 	shiftMarker.timeStep;
			var pixelStep		= 	(shiftMarker.imgWidth / shiftMarker.hours) * (timeStep / 60);
					
			var timeSteppedX	= parseInt(mouseX / pixelStep) * pixelStep;
			var newWidth		= shiftMarker.originalRight - timeSteppedX;//  + containmentRect.left;
			
			$(shiftMarker).offset({top : containmentDiv.top, left : timeSteppedX});
			
			
			shiftMarker.style.width = newWidth + 'px';
		
			//var newBoundingRect		= shiftMarker.getBoundingClientRect();
			
			//console.log('Old rect right = ' + shiftMarker.originalRight + ', new rect right = ' + newBoundingRect.right);
			
			//var correction 			= shiftMarker.originalRight - newBoundingRect.right;
			//var correctedWidth 		= parseInt(shiftMarker.style.width) + correction;
			//shiftMarker.style.width = correctedWidth + 'px';
			
			updateTime(shiftMarker,containmentRect);
			
			updateShiftMarkerData(shiftMarker);
		}
		
	});
	
}

//********************************************************************************************************************************************************************
//**  PUBLIC FUNCTION CREATERIGHTHANDLE(rightHandle,droppedOn,shiftMarker)
//**  Creates right size-position drag-handle.
//********************************************************************************************************************************************************************

function createRightHandle(rightHandle,droppedOn,shiftMarker) {
	
	var containmentDiv	= droppedOn;
	
	$(rightHandle).draggable( {
				
			helper		: 'hide',
			appendTo 	: 'body',
			containment : containmentDiv,
			
			drag : function(event,ui) {
				
				var containmentRect = containmentDiv.getBoundingClientRect();
				
				var mouseX		= 	event.clientX - containmentRect.left;
				var mouseY		= 	event.clientY;
				
				var origX		=	parseInt(shiftMarker.style.left);
				var newW		= 	mouseX - origX;
				
				shiftMarker.style.width = newW + 'px';
				
				updateTime(shiftMarker,containmentRect);
		},
		
		start : function(event,ui) {
			
			var boundingRect 			= shiftMarker.getBoundingClientRect();
				
			shiftMarker.originalLeft 	= boundingRect.left;
			shiftMarker.originalRight	= boundingRect.right;
		},
		
		stop : function(event,ui) {
				
			var containmentRect = containmentDiv.getBoundingClientRect();
			
			var mouseX		 = 	event.clientX - containmentRect.left;
			var mouseY		 = 	event.clientY;
			
			var timeStep	 = 	shiftMarker.timeStep;
			var pixelStep	 =  ((shiftMarker.imgWidth / shiftMarker.hours) * (timeStep / 60));
				
			var timeSteppedX = (mouseX / pixelStep) * pixelStep;
			var newWidth	 = timeSteppedX - shiftMarker.originalLeft + containmentRect.left;
				
			shiftMarker.style.width = newWidth + 'px';
		
			updateTime(shiftMarker,containmentRect);
			
			updateShiftMarkerData(shiftMarker);
			
		}
		
		
	});
	
}

//************************************************************************************************************************************************************
//**  FUNCTION CREATEHOURS(dropDown,timeStep,shiftMarker,containmentRect)
//**  Creates hour-dropdown options.
//************************************************************************************************************************************************************

function createHours(dropDown,timeStep,shiftMarker,containmentRect) {
	
	dropDown.timeStep = timeStep;
	
	for(hour=0;hour<24;hour++) {
		
		var newHour 				= document.createElement('option');
		newHour.value				= hour;	
		
		if (hour<10) newHour.text 	= '0' + hour;
		if (hour>9)  newHour.text 	= hour;
	
		createTimeOnChange(dropDown,shiftMarker,containmentRect);
		
		dropDown.appendChild(newHour);
	}

}

//************************************************************************************************************************************************************
//**  FUNCTION CREATEHOURS(dropDown,timeStep,shiftMarker,containmentRect)
//**  Creates hour-dropdown options.
//************************************************************************************************************************************************************

function setTimeValue(select,value) {
	
	var items = select.options;
	var len	  = items.length;
	
	for(var index=0;index<len;index++) {
		
		var option = items[index];
		if (option.value==value) option.selected = true;
	}
	
}

//************************************************************************************************************************************************************
//**  FUNCTION CREATEMINUTES(dropDown,timeStep,shiftMarker,containmentRect)
//**  Creates minute-dropdown options.
//************************************************************************************************************************************************************

function createMinutes(dropDown,timeStep,shiftMarker,containmentRect) {
	
	dropDown.timeStep =timeStep;

	for(minute=0;minute<60;minute+=parseInt(timeStep)) {
		
		var newMinute 				  = document.createElement('option');
		newMinute.value				  = minute;
		
		if (minute<10) newMinute.text = '0' + minute;
		if (minute>9) newMinute.text  = minute;
		
		createTimeOnChange(dropDown,shiftMarker,containmentRect);
		
		dropDown.appendChild(newMinute);
	}
	
}

//************************************************************************************************************************************************************
//**  FUNCTION UPDATETIME(shiftMarker,containmentRect)
//**  Updates time-controls.
//************************************************************************************************************************************************************

function updateTime(shiftMarker,containmentRect) {
	
	var shiftRect 			= shiftMarker.getBoundingClientRect();
	
	var timeStep			= shiftMarker.timeStep;
	var startX				= (shiftRect.left - containmentRect.left);
	var endX				= (shiftRect.right - containmentRect.left);
	var hourStep  			= (shiftMarker.imgWidth / shiftMarker.hours);
	
	var startHour   		= Math.floor(startX / hourStep);
	var startMinute 		= Math.round(((startX % hourStep) / hourStep) * 60);
	
	var endHour				= Math.floor(endX / hourStep);
	var endMinute			= Math.round(((endX % hourStep) / hourStep) * 60);
	
	var minuteCorrection 	= Math.round(endMinute / timeStep);
	endMinute				= Math.round(timeStep * minuteCorrection);
	
	minuteCorrection		= Math.round(startMinute / timeStep);
	startMinute				= Math.round(timeStep * minuteCorrection);
	
	//var minuteCorrecton = (endMinute % timeStep);
	//endMinute			= endMinute - minuteCorrecton;
	
	if (startMinute==60) {
		startHour++;
		startMinute 	= 0;
	}
	
	if (endMinute==60) {
		endHour++;
		endMinute 		= 0;
	}

	shiftMarker.bigTime.childNodes[0].value = startHour;
	shiftMarker.bigTime.childNodes[1].value = startMinute;
	shiftMarker.bigTime.childNodes[2].value = endHour;
	shiftMarker.bigTime.childNodes[3].value = endMinute;
	
	startHour 			= addZero(startHour);
	startMinute 		= addZero(startMinute);
	endHour				= addZero(endHour);
	endMinute 			= addZero(endMinute);
	
	shiftMarker.smallTime.innerHTML = startHour + ':' + startMinute + '-' + endHour + ':' + endMinute;

}

//************************************************************************************************************************************************************
//**  FUNCTION CREATETIMEONCHANGE(dropDown,shiftMarker,containmentRect)
//**  Creates onchange-event for time-controls.
//************************************************************************************************************************************************************

function createTimeOnChange(dropDown,shiftMarker,containmentRect) {
	
	$(dropDown).change(function() {
			
		var boundingRect = shiftMarker.getBoundingClientRect();
		
		var parent	 	= dropDown.parentNode;
		var startHour 	= parent.childNodes[0].value;
		var startMinute = parent.childNodes[1].value;
		var endHour		= parent.childNodes[2].value;
		var endMinute 	= parent.childNodes[3].value;
		
		var hourStep	= shiftMarker.imgWidth / shiftMarker.hours;
		
		var newLeft		= (hourStep * startHour) + (startMinute * (hourStep/60));
		var newRight	= (hourStep * endHour) + (endMinute * (hourStep / 60));
		var newWidth	= newRight - newLeft;
		
		//newLeft 		= newLeft + containmentRect.left;
		
		$(shiftMarker).offset({top : boundingRect.top, left : newLeft});
		
		shiftMarker.style.width = newWidth + 'px';
		
		updateShiftMarkerData(shiftMarker)
		
	});
	
	
}

//************************************************************************************************************************************************************
//**  FUNCTION CREATETIMEONCHANGE(dropDown,shiftMarker,containmentRect)
//**  Creates onchange-event for time-controls.
//************************************************************************************************************************************************************

function determineSize(shiftMarker,containmentRect) {
	
	var boundingRect = shiftMarker.getBoundingClientRect();
	
	var parent	 	= shiftMarker.bigTime;
	var scrollPos 	= $(shiftMarker.parentTable.parentNode).offset();
	
	var startHour 	= parent.childNodes[0].value;
	var startMinute = parent.childNodes[1].value;
	var endHour		= parent.childNodes[2].value;
	var endMinute 	= parent.childNodes[3].value;
	
	var hourStep	= shiftMarker.imgWidth / shiftMarker.hours;
	
	var newLeft		= (hourStep * startHour) + (startMinute * (hourStep/60));
	var newRight	= (hourStep * endHour) + (endMinute * (hourStep / 60));
	var newWidth	= newRight - newLeft;
	
	//newLeft 		= newLeft + scrollPos.left;
	//newLeft 		= newLeft + containmentRect.left;
	
	$(shiftMarker).offset({top : boundingRect.top, left : newLeft});
	
	shiftMarker.style.width = newWidth + 'px';
	
	updateShiftMarkerData(shiftMarker)
	
}


//************************************************************************************************************************************************************
//**  FUNCTION ADDZERO(timeBit)
//**  Add a following zero to timeBit if it consists of only one number.
//************************************************************************************************************************************************************

function addZero(timeBit) {
	
	if (timeBit<10) timeBit = '0' + timeBit;
	
	return timeBit;
	
}

//************************************************************************************************************************************************************
//**  FUNCTION RESERVESHIFTMARKERROW()
//**  Reserves shiftMarker.
//************************************************************************************************************************************************************

function reserveShiftMarkerRow(shiftMarker) {
	
	var employeeData = shiftMarker.employeeData;
	var taskData	 = shiftMarker.taskData;
	var projectData  = shiftMarker.projectData;
	
	var startHour	= shiftMarker.bigTime.childNodes[0].value;
	var startMinute = shiftMarker.bigTime.childNodes[1].value;
	var endHour		= shiftMarker.bigTime.childNodes[2].value;
	var endMinute	= shiftMarker.bigTime.childNodes[3].value;
	
	var date		= shiftMarker.date;
	var dateString	= date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate();
	
	if (employeeData!=null) {
		
		var employeeID	= employeeData['employeeID'];
		var firstName	= employeeData['firstname'];
		var lastName	= employeeData['lastname'];
		
	}
	
	if (taskData!=null) {

		var taskID		= taskData['taskID'];
		var taskName	= taskData['name'];
	
	}
	
	if (projectData!=null) {
		
		var projectID	= projectData['projectID'];
		var projectName	= projectData['name'];
	}
	
	var parameters  = '&employeeid='+employeeID;
	parameters		= parameters + '&firstname='+firstName;
	parameters		= parameters + '&lastname='+lastName;
	parameters		= parameters + '&starthour='+startHour;
	parameters		= parameters + '&startminute='+startMinute;
	parameters		= parameters + '&endhour='+endHour;
	parameters		= parameters + '&endminute='+endMinute;
	parameters		= parameters + '&datestring='+dateString;
	parameters		= parameters + '&taskid='+taskID;
	parameters		= parameters + '&taskname='+taskName;
	//parameters		= parameters + '&bgcolor='+(shiftMarker.backgroundColor).replace("#","");
	//parameters		= parameters + '&bordercolor='+(shiftMarker.originalBorderColor).replace("#","");
	parameters		= parameters + '&projectid='+projectID;
	parameters		= parameters + '&projectname='+projectName;
	
	//console.log(shiftMarker.reserveAction + parameters);

	$.getJSON(shiftMarker.reserveAction, parameters, function() {
		
	}).done(function(reply) { 
			
		shiftMarker.id = reply[0].insertID;
		
	});	

}

//************************************************************************************************************************************************************
//**  FUNCTION SAVE()
//**  Saves data to database.
//************************************************************************************************************************************************************

function updateShiftMarkerData(shiftMarker) {
	
	var employeeData = shiftMarker.employeeData;
	var taskData	 = shiftMarker.taskData;
	var projectData	 = shiftMarker.projectData;
	
	var startHour	= shiftMarker.bigTime.childNodes[0].value;
	var startMinute = shiftMarker.bigTime.childNodes[1].value;
	var endHour		= shiftMarker.bigTime.childNodes[2].value;
	var endMinute	= shiftMarker.bigTime.childNodes[3].value;

	var date		= shiftMarker.date;
	var dateString	= date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate();
	
	if (employeeData!=null) {
	
	var employeeID	= employeeData['employeeID'];
	var firstName	= employeeData['firstname'];
	var lastName	= employeeData['lastname'];
	
	}

	if (taskData!=null) {
	
		var taskID		= taskData['taskID'];
		var taskName	= taskData['name'];
	
	}
	
	if (projectData!=null) {
		
		var projectID	= projectData['projectID'];
		var projectName	= projectData['name'];
		
	}
	
	var parameters  = '&shiftmarkerid='+shiftMarker.id;
	parameters		= parameters + '&employeeid='+employeeID;
	parameters		= parameters + '&firstname='+firstName;
	parameters		= parameters + '&lastname='+lastName;
	parameters		= parameters + '&starthour='+startHour;
	parameters		= parameters + '&startminute='+startMinute;
	parameters		= parameters + '&endhour='+endHour;
	parameters		= parameters + '&endminute='+endMinute;
	parameters		= parameters + '&datestring='+dateString;
	parameters		= parameters + '&taskid='+taskID;
	parameters		= parameters + '&taskname='+taskName;
	//parameters		= parameters + '&bgcolor='+(shiftMarker.backgroundColor).replace("#","");
	//parameters		= parameters + '&bordercolor='+(shiftMarker.originalBorderColor).replace("#","");
	parameters		= parameters + '&projectid='+projectID;
	parameters		= parameters + '&projectname='+projectName;
	
	
	//console.log(shiftMarker.updateAction + parameters);

	$.getJSON(shiftMarker.updateAction, parameters, function() {
		
	}).done(function(reply) { 
		
		console.log('Updated shiftMarker row with shiftMarkerID' + shiftMarker.id);
	});	

}

