//************************************************************************************************************************************************************
//**
//**
//************************************************************************************************************************************************************

function preventDefaults(event) {
	
	event.preventDefault();
	
}

//************************************************************************************************************************************************************
//**
//**
//************************************************************************************************************************************************************

function hourDivOnDragEnter(event) {
	
	preventDefaults(event);

	
}

//************************************************************************************************************************************************************
//**
//**
//************************************************************************************************************************************************************

function hourDivOnDragOver(event) {
	
	preventDefaults(event);

	return;
	
	// Snap to grid hinatessa
	
	var sourceElement	= event.target;
	var sourceCoords	= sourceElement.getBoundingClientRect();
	
	var mouseX	 		= event.clientX;
	var mouseY 			= event.clientY;

	var timeStep 		= globalVariables.timeStep;
	var tableWidth  	= globalVariables.tableWidth;
	var step			= parseInt(tableWidth / 24 / timeStep);
	
	var posX   			= parseInt(mouseX / step) * step;
	
	$(dragTemplate).offset({top : sourceCoords.top, left : posX + 5});
	
}

//************************************************************************************************************************************************************
//**
//**
//************************************************************************************************************************************************************

function hourDivOnDragleave(event) {
	
	preventDefaults(event);
}

//************************************************************************************************************************************************************
//**
//**
//************************************************************************************************************************************************************

function hourDivOnDrop(event) {
	
	preventDefaults(event);

	var sourceDiv	 = event.target;
	var sourceCoords = sourceDiv.getBoundingClientRect();

	if (sourceDiv.reserved==true) return;
	
	var timeStep 	 = globalVariables.timeStep;
	var tableWidth   = globalVariables.tableWidth;
	
	var step		 = tableWidth / 24 / timeStep;
	
	var source		 = event.target;
	var sourceCoords = source.getBoundingClientRect();
	
	var mouseX		 = event.clientX;
	var mouseY		 = event.clientY;
	
	var shift		 = createNewShiftDiv(event);
	
	document.body.appendChild(shift);
	
	$(shift).offset({top : sourceCoords.top, left : mouseX + 5});
	
	reserveDiv(event);
	
}

function reserveDiv(event) {
	
	var sourceDiv = event.target;
	
	sourceDiv.reserved = true;
	
}

//************************************************************************************************************************************************************
//**
//**
//************************************************************************************************************************************************************

function createNewShiftDiv(event) {
	
	var shift 					= document.createElement('div');
	
	var sourceElement 			= event.target;
	
	var tableWidth  			= globalVariables.tableWidth;	
	var elementWidth 			= tableWidth / 4;
	var elementWidthPX			= elementWidth + 'px';
	
	console.log('Creating shift with widht' + elementWidth);
	
	shift.style.position 		= 'absolute';
	shift.innerHTML				= 'New Shift';
	shift.style.textAlign		= 'center';
	shift.style.backgroundColor = dragTemplate.style.backgroundColor;
	shift.style.width			= elementWidthPX;
	
	return shift;
}

//************************************************************************************************************************************************************
//**
//**
//************************************************************************************************************************************************************

function startEmployeeDrag(event) {
	
	var emptyImage		= document.createElement('img');
	var sourceElement 	= event.target;
	
	event.dataTransfer.setDragImage(emptyImage,0,0);
	
	dragTemplate 	= document.createElement('div');
	
	dragTemplate.style.position = 'absolute';
	
	dragTemplate.style.backgroundColor = sourceElement.style.backgroundColor;
	dragTemplate.innerHTML			   = sourceElement.innerHTML;
	
	document.body.appendChild(dragTemplate);
}

//************************************************************************************************************************************************************
//**
//**
//************************************************************************************************************************************************************

function onEmployeeDrag(event) {
	
	var mouseX = event.clientX;
	var mouseY = event.clientY;
	
	$(dragTemplate).offset({top : mouseY , left : mouseX + 5});
	
}

//************************************************************************************************************************************************************
//**
//**
//************************************************************************************************************************************************************

function onEmployeeEndDrag(event) {
	
	preventDefaults(event);
	
	dragTemplate.parentNode.removeChild(dragTemplate);
	
}



var dragTemplate = null;

