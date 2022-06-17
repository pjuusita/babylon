//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createFullShiftDiv(draggable,fromCell,event) {
	
	var sourceDiv 					= draggable.context;
	
	var newShift  					= document.createElement('div');
	newShift.className	   			= 'fullShift';
	newShift.selected				= false;
	var innerTable 					= document.createElement('table');
	
	innerTable.width				= '100%';

	newShift.style.backgroundColor 	= sourceDiv.style.backgroundColor;
	newShift.style.borderRadius	   	= sourceDiv.style.borderRadius;
	newShift.style.borderColor	  	= sourceDiv.style.borderColor;
	newShift.originalBorderColor	= sourceDiv.style.borderColor;
	newShift.style.borderWidth	  	= sourceDiv.style.borderWidth;
	newShift.style.padding		  	= sourceDiv.style.padding;
	newShift.style.width		  	= '450px';
	newShift.style.height		  	= sourceDiv.style.height;
	newShift.originalHeight			= sourceDiv.style.height;
	newShift.style.borderStyle	  	= sourceDiv.style.borderStyle;
	newShift.style.textAlign	  	= sourceDiv.style.textAlign;
	
	newShift.style.position		 	= 'absolute';
	
	var row 	   					= innerTable.insertRow();
	
	var leftDragCell   				= row.insertCell();
	var nameLabelCell  				= row.insertCell();
	var taskLabelCell  				= row.insertCell();
	var timeLabelCell				= row.insertCell();
	var destroyCell					= row.insertCell();
	var rightDragCell  				= row.insertCell();

	leftDragCell.style.width		= '10px';   				
	nameLabelCell.style.width		= '125px';  				
	taskLabelCell.style.width		= '125px';
	timeLabelCell.style.width		= '170px';
	destroyCell.style.width			= '10px';					
	rightDragCell.style.width		= '10px';  				

	var leftDrag   					= document.createElement('div');
	var nameLabel  					= document.createElement('div');
	var taskLabel 					= document.createElement('div');
	var timeLabel					= document.createElement('div');
	var destroy						= document.createElement('div');
	var rightDrag  					= document.createElement('div');

	leftDrag.innerHTML 				= 'O';
	leftDrag.style.textAling 		= 'center';
	leftDrag.droppableClass			= 'dragHandle';
	
	nameLabel.innerHTML 			= sourceDiv.innerHTML;
	nameLabel.style.textAling 		= 'center';
	
	taskLabel.innerHTML				= 'Työmies';
	taskLabel.style.textAling 		= 'center';
	
	timeLabel.style.borderRadius	= '4px';
	timeLabel.style.borderStyle		= 'solid solid solid solid';
	timeLabel.style.borderWidth		= '2px';
	timeLabel.style.width			= '170px';
	timeLabel.style.borderColor		= 'darkgray';
	timeLabel.style.backgroundColor = 'lightgray';

	createTimeDropDowns(timeLabel);

	
	timeLabel.childNodes[0].value	= parseInt(event.clientX / 60);
	timeLabel.childNodes[1].value	= parseInt(event.clientX % 60);
	timeLabel.childNodes[2].value	= parseInt((event.clientX + 300) / 60);
	timeLabel.childNodes[3].value	= parseInt((event.clientX + 300) % 60);
			
	destroy.innerHTML				= 'X';
	destroy.style.textAling 		= 'center';
	
	rightDrag.innerHTML 			= 'O';
	rightDrag.style.textAling 		= 'center';
	rightDrag.droppableClass		= 'dragHandle';

	leftDragCell.appendChild(leftDrag);
	nameLabelCell.appendChild(nameLabel);
	taskLabelCell.appendChild(taskLabel);
	timeLabelCell.appendChild(timeLabel);
	destroyCell.appendChild(destroy);
	rightDragCell.appendChild(rightDrag);
	
	newShift.appendChild(innerTable);
	
	createLengthHandlesLeft(leftDrag,fromCell);
	createDroppablenameArea(nameLabel);
	createDroppableTaskArea(taskLabel);
	createDeleteHandle(destroy);
	createLengthHandlesRight(rightDrag,fromCell);
	
	newShift.droppableClass		  	= 'shift'
	newShift.reservedRow			= fromCell.parentNode;
	
	newShift.timeLabelPointer		= timeLabel;
	
	createSelectable(row,newShift);
	
	return newShift;
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createSelectable(row_,shift_) {
	
	var shift = shift_;
	var row	  = row_;
	
	console.log('Selected shiftDivs');
	
	$(row).click(function() {

			shifts = document.getElementsByClassName('fullShift');
			
			console.log('Shifts found ' + shifts.length);
			
			for(var index=0;index<shifts.length;index++) {
			
				console.log('Setting '+shifts[index]+' unselected.');
				
					shifts[index].style.borderColor = shifts[index].originalBorderColor;
					shifts[index].selected 			= false;
					shifts[index].style.height		= shifts[index].originalHeight;
			}
			
			shift.style.borderColor = 'black';
			shift.style.height		= '100px';
			shift.selected			= true;			
	});
	
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createLengthHandlesLeft(handle,fromCell) {
	
	var containmentRow = fromCell;
	
	console.log('Setting containment to ' + containmentRow);
	
	$(handle).draggable({
		
		helper		: 'hide',
		appendTo 	: 'body',
		containment : containmentRow,
		
		drag : function(event,ui) {
			
			var mouseX		= 	event.clientX;
			var mouseY		= 	event.clientY;
			var parentDiv 	= 	this.parentNode.parentNode.parentNode.parentNode.parentNode;
			
			if (!parentDiv.selected) return;
			
			var origX		=	parseInt(parentDiv.style.left);
			var diff		= 	origX - mouseX;
			var origW		= 	parseInt(parentDiv.style.width);
			var newW		= 	origW + diff;
			var timeStep	= 	globalVariables.timeStep;
			
			var snapToGrid  = parseInt(mouseX / timeStep) * timeStep;
			var snapWidth	= parseInt(newW / timeStep) * timeStep;
			
			
			console.log('Dragged handles parentDiv ' +  parentDiv + ' has className ' + parentDiv.className + ' width change ' + diff);
			

			parentDiv.style.width = newW + 'px';
			$(parentDiv).offset({top : containmentRow.top, left : mouseX});


			//parentDiv.style.width = snapWidth + 'px';
			//$(parentDiv).offset({top : containmentRow.top, left : snapToGrid});

			
			updateTimeLabel(parentDiv);
		}
 
	});
	
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createLengthHandlesRight(handle,fromCell) {
	
	var containmentRow = fromCell;
	
	console.log('Setting containment to ' + containmentRow);
	
	$(handle).draggable({
		
		helper		: 'hide',
		appendTo 	: 'body',
		containment : containmentRow,
		
		drag : function(event,ui) {
			
			
			var mouseX		= 	event.clientX;
			var mouseY		= 	event.clientY;
			var parentDiv 	= 	this.parentNode.parentNode.parentNode.parentNode.parentNode;
			
			if (!parentDiv.selected) return;
			
			var origX		=	parseInt(parentDiv.style.left);
			var newW		= 	mouseX - origX;
			
			parentDiv.style.width = newW + 'px';
			
			updateTimeLabel(parentDiv);
			
			console.log('Dragged handles parentDiv ' +  parentDiv + ' has className ' + parentDiv.className + ' with new width ' + newW);
				
		}
 
	});
	
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createDeleteHandle(handle) {
	
	var destroy 		= handle;
	
	$(destroy).click(function() {
		
		var shift 		= this.parentNode.parentNode.parentNode.parentNode.parentNode;
		var row			= shift.reservedRow;
		
		if (!shift.selected) return;
		
		row.reserved	= false;
		
		console.log('Destroying element' + shift + ' setting row free ' + shift.reservedRow);
		
		shift.parentNode.removeChild(shift);
		
		
	});
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createDroppableTaskArea(area) {
	
	var taskArea = area;
	
	$(taskArea).droppable( {
		
		drop : function(event,ui) {
				
			if (ui.draggable.context.draggableClass=='taskDiv') {

				console.log('Dropped ' + ui.draggable.context.draggableClass + ' on ' + this);
				
				this.innerHTML 				= ui.draggable.context.innerHTML;
				this.style.backgroundColor 	= ui.draggable.context.style.backgroundColor;
				this.style.borderStyle		= ui.draggable.context.style.borderStyle;
				this.style.borderColor		= ui.draggable.context.style.borderColor;
				this.style.borderRadius		= ui.draggable.context.style.borderRadius;
				
			}	
		},
		
		over : function(event,ui) {		
		},
		
		out : function(event,ui) {	
		}
		
	});

}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createDroppablenameArea(area) {
	
	var nameArea = area;
	
	$(nameArea).droppable( {
		
		drop : function(event,ui) {
			
			if (ui.draggable.context.draggableClass=='employeeDiv') {

				console.log('Dropped ' + ui.draggable.context.draggableClass + ' on ' + this);
				
				this.innerHTML 				= ui.draggable.context.innerHTML;
				this.style.backgroundColor 	= ui.draggable.context.style.backgroundColor;
				this.style.borderStyle		= ui.draggable.context.style.borderStyle;
				this.style.borderColor		= ui.draggable.context.style.borderColor;
				this.style.borderRadius		= ui.draggable.context.style.borderRadius;
				
			}	
		},
		
		over : function(event,ui) {
			
		},
		
		out : function(event,ui) {
			
		}
	
	});
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function updateTimeLabel(shiftDiv) {
	
	var timeLabel	= shiftDiv.timeLabelPointer;
	var timeRect	= shiftDiv.getBoundingClientRect();
	
	var startHour 	= parseInt(timeRect.left / 60) + '';
	var startMinute = parseInt(timeRect.left % 60) + '';
	var endHour 	= parseInt(timeRect.right / 60) + '';
	var endMinute 	= parseInt(timeRect.right & 60) + '';
		
	timeLabel.childNodes[0].value = startHour;
	timeLabel.childNodes[1].value = startMinute;
	timeLabel.childNodes[2].value = endHour;
	timeLabel.childNodes[3].value = endMinute;
	
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createTimeDropDowns(timeLabel) {
	
	var startHourDropDown		= document.createElement('select');
	var startMinuteDropDown		= document.createElement('select');
	var endHourDropDown			= document.createElement('select');
	var endMinuteDropDown		= document.createElement('select');
	
	createHours(startHourDropDown);
	createMinutes(startMinuteDropDown);
	createHours(endHourDropDown);
	createMinutes(endMinuteDropDown);
	
	createDropDownOnChange(startHourDropDown);
	createDropDownOnChange(startMinuteDropDown);
	createDropDownOnChange(endHourDropDown);
	createDropDownOnChange(endMinuteDropDown);
	
	
	timeLabel.appendChild(startHourDropDown);
	timeLabel.appendChild(startMinuteDropDown);
	timeLabel.appendChild(endHourDropDown);
	timeLabel.appendChild(endMinuteDropDown);
	
}
	

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createDropDownOnChange(dropDown) {
	
	$(dropDown).change(function() {
		changeSizeOnTimeSelect(this.parentNode);
	});
	
}


//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createHours(dropDown) {
	
	for(hour=0;hour<24;hour++) {
		
		var newHour 				= document.createElement('option');
		newHour.value				= hour;	
		if (hour<10) newHour.text 	= '0' + hour;
		if (hour>9)  newHour.text 	= hour;
	
		dropDown.appendChild(newHour);
	}

}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createMinutes(dropDown) {
	
	var timeStep = 1;
	
	// Override on timebeing, snapgrid calculations doesn't represent timesteps at the moment 
	
	timeStep = 1;
	
	for(minute=0;minute<60;minute+=timeStep) {
		
		var newMinute 				  = document.createElement('option');
		newMinute.value				  = minute;
		
		if (minute<10) newMinute.text = '0' + minute;
		if (minute>9) newMinute.text  = minute;
		
		dropDown.appendChild(newMinute);
	}
	
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function changeSizeOnTimeSelect(timeDiv) {
		
	var startHour 	= parseInt(timeDiv.childNodes[0].value);
	var startMinute = parseInt(timeDiv.childNodes[1].value);
	var endHour 	= parseInt(timeDiv.childNodes[2].value);
	var endMinute 	= parseInt(timeDiv.childNodes[3].value);
	
	var shiftDiv 	= timeDiv.parentNode.parentNode.parentNode.parentNode.parentNode;
	var shiftRect	= shiftDiv.getBoundingClientRect();
	
	var newLeft = (startHour * 60) + startMinute;
	var newWidth = (endHour * 60 +  endMinute) - (startHour * 60 + startMinute);

	$(shiftDiv).offset({top : shiftRect.top, left : newLeft});
	
	shiftDiv.style.width =  newWidth + 'px';

	console.log('Onchange element ' + shiftDiv + ' new left ' + newLeft + ' and new width ' + newWidth + 'px');
		
}