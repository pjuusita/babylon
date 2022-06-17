
//************************************************************************************************************************************************************
//** Function()
//**
//************************************************************************************************************************************************************

function createEmployeeDivClassEvents(timestep) {
	
	log('Creating events for employeeDiv-class.');
	
	var boundingDiv 	= 	document.getElementById('boundingDiv');
	
	$('.employeeDiv').draggable({

		helper	 	: 'clone',
		appendTo 	: 'body',
		grid		: [1,1],
		containment : boundingDiv

	});

}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createEmployeeDiv(employeeName,backgroundColor,borderColor) {
	
	log('Creating an employee ' + employeeName + ' with background color of ' + backgroundColor);
	
	var employeeDiv 					= document.createElement('div');
	employeeDiv.innerHTML 				= employeeName;
	employeeDiv.className				= 'employeeDiv';
	employeeDiv.draggableClass			= 'employeeDiv';
	
	createEmployeeDivStyle(employeeDiv,backgroundColor,borderColor);
		
	var boundingDiv 	= 	document.getElementById('boundingDiv');
	boundingDiv.appendChild(employeeDiv);

}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

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

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createEmployeeDivEvents(employeeDiv) {
	
	log('Creating events for ' + employeeDiv.innerHTML);
	
	$(employeeDiv).draggable({
		
	});
	
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function log(logString) {
	
	if (globalVariables.debug==true) console.log(logString);
	
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function logObject(object) {
	
	for(var index in object) {
		
		log(index + ' : ' + object[index]);
		
	}
	
}
