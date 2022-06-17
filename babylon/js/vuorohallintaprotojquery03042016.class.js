function createEmployeeDivClassEvents() {
	
	log('Creating events for employeeDiv-class.');
	
	var boundingDiv 	= 	document.getElementById('boundingDiv');
	var boundingRect	= 	boundingDiv.getBoundingClientRect();
	
	$('.employeeDiv').draggable({
		helper	 	: 'clone',
		appendTo 	: 'body',
		containment : [boundingRect.top,boundingRect.left,boundingRect.right,boundingRect.bottom]
		
	});

}

function createEmployeeDiv(employeeName,backgroundColor,borderColor) {
	
	log('Creating an employee ' + employeeName + ' with background color of ' + backgroundColor);
	
	var employeeDiv 					= document.createElement('div');
	employeeDiv.innerHTML 				= employeeName;
	employeeDiv.className				= 'employeeDiv';

	createEmployeeDivStyle(employeeDiv,backgroundColor,borderColor);
		
	var boundingDiv 	= 	document.getElementById('boundingDiv');
	boundingDiv.appendChild(employeeDiv);

}

function createEmployeeDivStyle(employeeDiv,backgroundColor,borderColor) {
	
	log('Creating div style for ' + employeeDiv.innerHTML);
	
	employeeDiv.style.backgroundColor = backgroundColor;
	employeeDiv.style.borderRadius	  = '4px';
	employeeDiv.style.borderWidth	  = '2px 2px 2px 2px';
	employeeDiv.style.borderStyle	  = 'solid solid solid solid';
	employeeDiv.style.borderColor	  = borderColor;
	employeeDiv.style.padding		  = '1px 5px 1px 5px';
	employeeDiv.style.width			  = '200px';
	employeeDiv.style.textAlign		  = 'center';
	
}


function createEmployeeDivEvents(employeeDiv) {
	
	log('Creating events for ' + employeeDiv.innerHTML);
	
	$(employeeDiv).draggable({
		
	});
	
}

function log(logString) {
	
	if (globalVariables.debug==true) console.log(logString);
	
}




