//********************************************************************************************************************************************************************
//**  FUNCTION CREATESHIFTTABLE(title,parentID,startDateString,days,timeStep,imgWidth,hours,bgImgUrl,headerImgUrl)
//**  Creates shiftTable. Created by Tuukka Rintala 2016.
//********************************************************************************************************************************************************************

function createShiftTable(title,parentID,startDateString,days,timeStep,imgWidth,hours,bgImgUrl,headerImgUrl,reserveAction,updateAction,deleteAction,groupBy,colorBy) {
	
	var newShiftTable 			= document.createElement('table');	
	var parentDiv	  			= document.getElementById(parentID);

	newShiftTable.updateAction  = updateAction;
	newShiftTable.reserveAction = reserveAction;
	newShiftTable.deleteAction  = deleteAction;

	newShiftTable.className		= 'shiftTable';
	newShiftTable.timeStep		= timeStep;
	newShiftTable.startDate 	= startDateString;
	newShiftTable.imgWidth		= imgWidth;
	newShiftTable.hours			= hours;
	newShiftTable.groupBy		= groupBy;
	newShiftTable.colorBy		= colorBy;
	
	newShiftTable.style.width 	= '1500px';
	
	var dateArray	  			= startDateString.split("-");
	var startDate	  			= new Date();

	startDate.setFullYear(dateArray[0],dateArray[1],dateArray[2]);

	createShiftTableHeader(newShiftTable,headerImgUrl);
	
	var last = -1;
	
	for(var n=0;n<days;n++) {
		
		createShiftTableRow(newShiftTable,startDate,bgImgUrl,last);
		createDropBetweenTableRow(newShiftTable,startDate,bgImgUrl,last);
		startDate.setDate(startDate.getDate()+1);
		
	}
	
	parentDiv.appendChild(newShiftTable);

	createOnWindowResize();
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION CREATESHIFTTABLEHEADER(table,headerImgUrl)
//**  Creates shiftTable-header.
//********************************************************************************************************************************************************************

function createShiftTableHeader(table,headerImgUrl) {
	
	var newRow		= table.insertRow();
	var dateCell 	= newRow.insertCell();
	var timeCell 	= newRow.insertCell();
	
	var headerImageDiv = document.createElement('div');
	timeCell.style.height				= '25px';
	headerImageDiv.style.height			= '25px';
	headerImageDiv.style.background	   	= '#f3f3f3 url('+headerImgUrl+') no-repeat left top';
	
	dateCell.style.width = '75px';
	timeCell.style.width = '1425px';
	
	timeCell.appendChild(headerImageDiv);
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION CREATEDROPBETWEENTABLEROW(table,date,bgImgUrl,rowIndex)
//**  Creates dropBetween shiftTable row.  
//********************************************************************************************************************************************************************

function createDropBetweenTableRow(table,date,bgImgUrl,rowIndex) {
	
	var newRow			     		= table.insertRow(rowIndex);
	
	newRow.date				 		= new Date();
	newRow.date.setFullYear(date.getFullYear(),date.getMonth(),date.getDate());
	newRow.dateString				= addZero(date.getFullYear()) + "-" + addZero(date.getMonth()) + "-" + addZero(date.getDate());
	newRow.type						= 'dropbetweenRow';
	
	newRow.bgImage					= bgImgUrl;
	
	var dateBetween					= newRow.insertCell();
	var dropBetween					= newRow.insertCell();
	
	dateBetween.style.width			= '75px';
	dropBetween.style.width			= '1425px';
	
	var dropBetweenDiv		 		= document.createElement('div');
	
	dropBetween.appendChild(dropBetweenDiv);
	
	dropBetweenDiv.style.background = '#f3f3f3 url('+bgImgUrl+') no-repeat left top';
	dropBetweenDiv.style.width		= '100%';
	dropBetweenDiv.innerHTML		= '&nbsp';
	dropBetweenDiv.style.position	= 'relative';
	
	dropBetween.style.height		= '10px';
	
	$(dropBetweenDiv).droppable( {
		
		drop : function(event,ui) {
				
			var droppedElement 				= ui.draggable.context;
			var droppedOn	   				= event.target;
			var mouseX						= event.clientX;
			
			droppedOn.parentNode.style.backgroundColor = 'white';
			
			if (!((droppedElement.draggableClass=='task') || (droppedElement.draggableClass=='employee') || (droppedElement.draggableClass=='project'))) {
				console.log('shiftTable accepts only objects with draggableClass task, employee or project.');
				return;
			}
			
			var parentRow 					= droppedOn.parentNode.parentNode;	
				
			parentRow.style.backgroundColor = 'white';
			
			createDropBetweenTableRow(table,parentRow.date,parentRow.bgImage,parentRow.rowIndex);
			
			var newRow 						= createShiftTableRow(table,parentRow.date,parentRow.bgImage,parentRow.rowIndex);
			
			var droppableDiv 				= newRow.cells[1].childNodes[0];
			var dateCell					= newRow.cells[0];
			var parentCell					= droppableDiv.parentNode; 
					
			var parentBoundingBox  			= parentCell.getBoundingClientRect();
			var newShift   					= createShiftMarker(ui.draggable,event,table);
		
			console.log('TABLE ' + table.groupBy + ' : ' + table.colorBy);
			
			if (table.groupBy=='project') {
				
				newShift.projectData 			= table.parentNode.primaryData;
				newShift.projectArea.innerHTML 	= table.parentNode.primaryData['name'];
				
			}
			
			if (table.groupBy=='task') {
			
				newShift.taskData				= table.parentNode.primaryData;
				newShift.taskArea.innerHTML 	= table.parentNode.primaryData['name'];
			}
			
			if (table.groupBy=='employee') {
				
				newShift.employeeData 			= table.parentNode.primaryData;
				newShift.nameArea.innerHTML 	= table.parentNode.primaryData['name'];
			}
			
			if (table.colorBy=='project') {
				
				var colorIndex = 0;
				
				if (table.groupBy=='project') colorIndex = parseInt(table.parentNode.primaryData['color']);
				if (newShift.projectData!=null) colorIndex = parseInt(newShift.projectData['color']);
					
				var color	   = colorVariables.colorsIntIndexed[colorIndex];
				
				newShift.style.backgroundColor = color.normal;
				newShift.style.borderColor	   = color.light;
				
				newShift.backgroundColor		=  color.normal;
				newShift.borderColor			=  color.light;
				newShift.originalBorderColor	=  color.light;
				
			}
			
			if (table.colorBy=='task') {
				
				var colorIndex = 0;
				
				if (table.groupBy=='task') colorIndex = parseInt(table.parentNode.primaryData['color']);
				if (newShift.taskData!=null) colorIndex = parseInt(newShift.taskData['color']);
				
				var color	   = colorVariables.colorsIntIndexed[colorIndex];
				
				newShift.style.backgroundColor = color.normal;
				newShift.style.borderColor	   = color.light;
				
				newShift.backgroundColor		=  color.normal;
				newShift.borderColor			=  color.light;
				newShift.originalBorderColor	=  color.light;
				
			}
	
			if (table.colorBy=='employee') {
		
				var colorIndex = 0;
				
				if (table.groupBy=='employee') colorIndex = parseInt(table.parentNode.primaryData['color']);
				if (newShift.employeeData!=null) colorIndex = parseInt(newShift.employeeData['color']);
				
				var color	   = colorVariables.colorsIntIndexed[colorIndex];
		
				newShift.style.backgroundColor  = color.normal;
				newShift.style.borderColor	    = color.light;
		
				newShift.backgroundColor		=  color.normal;
				newShift.borderColor			=  color.light;
				newShift.originalBorderColor	=  color.light;
		
			}
			
			parentCell.appendChild(newShift);
			
			var offsettedY = $(window).scrollTop() + parentBoundingBox.top;
			
			//$(newShift).offset({top : offsettedY, left : newShift.style.left});
			$(newShift).offset({top : offsettedY, left : mouseX});
			
			
			droppableDiv.reserved 			= true;
			newRow.reserved					= true;
			dateCell.innerHTML 				= '';
			
			var containmentRect 	= dropBetweenDiv.getBoundingClientRect();
			
			updateTime(newShift,containmentRect);
			
			reserveShiftMarkerRow(newShift);
			
			onVisibilityChange();
				
		},
		
		over : function(event,ui) {
			
			var droppedOn	   		= event.target;
			var dragged				= ui.draggable.context;
			
			if ((dragged.draggableClass=='task') || (dragged.draggableClass=='employee') || (dragged.draggableClass=='project')) droppedOn.parentNode.style.backgroundColor = 'black';
	
		},
		
		out : function(event,ui) {
			
			var droppedOn	   		= event.target;
			droppedOn.parentNode.style.backgroundColor = 'white';
				
		}
		
	});
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION CREATESHIFTTABLEROW(table,date,bgImgUrl,rowIndex)
//**  Creates normal row for shiftTable.
//********************************************************************************************************************************************************************

function createShiftTableRow(table,date,bgImgUrl,rowIndex) {
	
	var newRow				= table.insertRow(rowIndex);

	newRow.style.height	 	= '25px';
	newRow.type				= 'mainRow';
	
	var dateCell			= newRow.insertCell();
	var timeCell			= newRow.insertCell();
	
	dateCell.style.height = '25px';
	timeCell.style.height = '25px';
	
	var dateString  	= date.getDate() + '.'  + date.getMonth();
	
	dateCell.innerHTML	= dateString;
	
	newRow.bgImage		= bgImgUrl;
	newRow.date			= new Date();
	newRow.date.setFullYear(date.getFullYear(),date.getMonth(),date.getDate());
	newRow.dateString	= addZero(date.getFullYear()) + "-" + addZero(date.getMonth()) + "-" + addZero(date.getDate());
		
	createDroppableArea(timeCell,bgImgUrl,table);
	
	return newRow;
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION CREATEDROPPABLEAREA(timeCell,bgImgUrl,table)
//**  Creates a div that accepts droppable objects.
//********************************************************************************************************************************************************************

function createDroppableArea(timeCell,bgImgUrl,table) {
	
	var droppableDiv					= document.createElement('div');
	droppableDiv.innerHTML 				= '&nbsp';
	
	var parentRow						= timeCell.parentNode;
	
	timeCell.appendChild(droppableDiv);
	
	droppableDiv.style.width		   	= '100%';
	droppableDiv.style.height		   	= '100%';
	droppableDiv.style.background	   	= '#f3f3f3 url('+bgImgUrl+') no-repeat left top';
	droppableDiv.style.position			= 'relative';
	
	$(droppableDiv).droppable({
		
		drop : function(event,ui) {
			
			var droppedElement		= ui.draggable.context;
			var mouseX				= event.clientX;
			
			source = event.target;
			source.parentNode.style.backgroundColor = 'white';
			
			if (parentRow.reserved==true) return;
			
			if (!((droppedElement.draggableClass=='task') || (droppedElement.draggableClass=='employee') || (droppedElement.draggableClass=='project'))) {
				console.log('shiftTable accepts only objects with draggableClass task or employee');
				return;
			}
			
			if (droppedElement.draggableClass==null) {
				console.log('shiftTable accepts only objects with draggableClass task or employee');
				return;		
			}
						
			var parentCell 			= event.target.parentNode;
			var parentBoundingBox   = parentCell.getBoundingClientRect();
			var newShift   			= createShiftMarker(ui.draggable,event,table);
			var shiftBoundingBox	= newShift.getBoundingClientRect();
			
			parentCell.appendChild(newShift);
			
			if (table.groupBy=='project') {
				
				newShift.projectData 			= table.parentNode.primaryData;
				newShift.projectArea.innerHTML 	= table.parentNode.primaryData['name'];
			}
			
			if (table.groupBy=='task') {
			
				newShift.taskData				= table.parentNode.primaryData;
				newShift.taskArea.innerHTML 	= table.parentNode.primaryData['name'];
			}
			
			if (table.groupBy=='employee') {
				
				newShift.employeeData 			= table.parentNode.primaryData;
				newShift.nameArea.innerHTML 	= table.parentNode.primaryData['name'];
			}
			
			if (table.colorBy=='project') {
			
				var colorIndex = 0;
				
				if (table.groupBy=='project') colorIndex = parseInt(table.parentNode.primaryData['color']);
				if (newShift.projectData!=null) colorIndex = parseInt(newShift.projectData['color']);
				
				var color	   = colorVariables.colorsIntIndexed[colorIndex];
				
				//console.log('Creating new with colorIndex ' + colorIndex);
				
				newShift.style.backgroundColor = color.normal;
				newShift.style.borderColor	   = color.light;
				
				newShift.backgroundColor		=  color.normal;
				newShift.borderColor			=  color.light;
				newShift.originalBorderColor	=  color.light;
				
			}
			
			if (table.colorBy=='task') {
				
				var colorIndex = 0;
				
				if (table.groupBy=='task') colorIndex = parseInt(table.parentNode.primaryData['color']);
				if (newShift.taskData!=null) colorIndex = parseInt(newShift.taskData['color']);
				
				var color	   = colorVariables.colorsIntIndexed[colorIndex];
				
				//console.log('Creating new with colorIndex ' + colorIndex);
				
				newShift.style.backgroundColor = color.normal;
				newShift.style.borderColor	   = color.light;
				
				newShift.backgroundColor		=  color.normal;
				newShift.borderColor			=  color.light;
				newShift.originalBorderColor	=  color.light;
				
			}
	
			if (table.colorBy=='employee') {
		
				var colorIndex = 0;
				
				if (table.groupBy=='employee') colorIndex = parseInt(table.parentNode.primaryData['color']);
				if (newShift.employeeData!=null) colorIndex = parseInt(newShift.employeeData['color']);
				
				var color	   = colorVariables.colorsIntIndexed[colorIndex];
		
				//console.log('Creating new with colorIndex ' + colorIndex);
				
				newShift.style.backgroundColor = color.normal;
				newShift.style.borderColor	   = color.light;
		
				newShift.backgroundColor		=  color.normal;
				newShift.borderColor			=  color.light;
				newShift.originalBorderColor	=  color.light;
		
			}
			
			var offsettedY 			= $(window).scrollTop() + parentBoundingBox.top;
			//var timeSteppedX 		= parseInt(newShift.style.left / newShift.timeStep) * newShift.timeStep;
			var timeSteppedX 		= parseInt(mouseX / newShift.timeStep) * newShift.timeStep;
			
			$(newShift).offset({top : offsettedY, left : timeSteppedX});
			
			parentRow.reserved 		= true;
			
			var containmentRect 	= droppableDiv.getBoundingClientRect();
			
			updateTime(newShift,containmentRect);
			
			reserveShiftMarkerRow(newShift);
				
		},
		
		over : function(event,ui) {
			
			var source  = event.target;
			var dragged = ui.draggable.context;
				
			if ((dragged.draggableClass=='task') || (dragged.draggableClass=='employee') || (dragged.draggableClass=='project')) source.parentNode.style.backgroundColor = 'black';
			
		},
		
		out : function(event,ui) {
			
			source = event.target;
			source.parentNode.style.backgroundColor = 'white';
			
		}
				
	});	
}

//********************************************************************************************************************************************************************
//**  FUNCTION PUSHSHIFTMARKERSDOWN(pusher)
//**  Pushes shiftMarkers which have greater y-coordinate than newly added row down. 
//********************************************************************************************************************************************************************

function pushShiftMarkersDown(pusher) {
	
	return;
	
	var shiftMarkers = document.getElementsByClassName('shiftMarker');
	var count		 = shiftMarkers.length;
	
	for(var index=0;index<count;index++) {
		
		var pushed = shiftMarkers[index];
		
		if (pushed!=pusher) {
			
			var pushedCoords = pushed.getBoundingClientRect();
			var pusherCoords = pusher.getBoundingClientRect();
			
			if (pushedCoords.top >= (pusherCoords.top - 5)) {
				
				var rowRect		 = pushed.parentNode.getBoundingClientRect();
				
				//console.log('PUSH AMOUNT' + pushAmount);
				
				//pushed.style.top = ($(window).scrollTop() +  rowRect.top) + 'px'; 
				
				//pushed.style.top = (rowRect.top) + 'px'; 
				
				//pushed.style.top = ($(window).scrollTop() +  pushedCoords.top + pushAmount) + 'px'; 
			
				//pushed.style.top = ($(window).scrollTop() +  pushedCoords.top + 47) + 'px'; 
				
			}
		
		}
	
	}
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION CREATEONWINDOWRESIZE()
//**  Creates custom event for window.resize() to update coordinates of shiftMarkers on resize.
//********************************************************************************************************************************************************************

function createExistingShiftMarkers(shiftTable) {
	
	var shiftMarkers = shiftTable.shiftData;
	var employees	 = shiftTable.employeeData;
	var tasks		 = shiftTable.taskData;
	var projects	 = shiftTable.projectData;
	
	for(var index in shiftMarkers) {
		
		var shiftMarkerData = shiftMarkers[index];
		var employeeData	= employees[shiftMarkerData['employeeID']];
		var taskData		= tasks[shiftMarkerData['taskID']];
		var projectData		= projects[shiftMarkerData['projectID']];
		
		//console.log('TASKDATA : ' + taskData);
		//console.log('EMPLOYEEDATA : ' + employeeData);
		//console.log('PROJECTDATA : ' + projectData);
		
		createExistingShiftMarker(employeeData,taskData,projectData,shiftMarkerData,shiftTable)
		
	}
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION CREATEONWINDOWRESIZE()
//**  Creates custom event for window.resize() to update coordinates of shiftMarkers on resize.
//********************************************************************************************************************************************************************

function createOnWindowResize() {
	
	$(window).resize(function() {
	
		var shiftMarkers = document.getElementsByClassName('shiftMarker');
		var len			 = shiftMarkers.length;
		
			for(var index=0;index<len;index++) {
			
				var shiftMarker 	= shiftMarkers[index];
				var boundingDiv		= shiftMarker.parentNode;
				var boundingRect	= boundingDiv.getBoundingClientRect();
						
				$(shiftMarker).offset({top : boundingRect.top + $(window).scrollTop(),left : shiftMarker.style.left });
			
				var boundingShiftMarker = shiftMarker.getBoundingClientRect();
						
			}
			
	});
	
}

//********************************************************************************************************************************************************************
//**  FUNCTION onVisibilityChange()
//**  Creates custom event for window.resize() to update coordinates of shiftMarkers on resize.
//********************************************************************************************************************************************************************

function onVisibilityChange() {
	
		var shiftMarkers = document.getElementsByClassName('shiftMarker');
		var len			 = shiftMarkers.length;
		
			for(var index=0;index<len;index++) {
			
				var shiftMarker 	= shiftMarkers[index];
				var boundingDiv		= shiftMarker.parentNode;
				var boundingRect	= boundingDiv.getBoundingClientRect();
				
				$(shiftMarker).offset({top : boundingRect.top + $(window).scrollTop(),left : shiftMarker.style.left });
							
			}
			
}
	
//************************************************************************************************************************************************************
//**  FUNCTION ADDZERO(timeBit)
//**  Add a following zero to timeBit if it consists of only one number.
//************************************************************************************************************************************************************

function addZero(timeBit) {
	
	if (timeBit<10) timeBit = '0' + timeBit;
	
	return timeBit;
	
}

