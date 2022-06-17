//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createTaskDivClassEvents(timestep) {
	
	log('Creating events for taskDiv-class.');
	
	var boundingDiv 	= 	document.getElementById('boundingDiv');
	
	$('.taskDiv').draggable({

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

function createTaskDiv(taskName,backgroundColor,borderColor) {
	
	log('Creating a task ' + taskName + ' with background color of ' + backgroundColor);
	
	var taskDiv		 				= document.createElement('div');
	taskDiv.innerHTML 				= taskName;
	taskDiv.className				= 'taskDiv';
	taskDiv.draggableClass			= 'taskDiv'

	createTaskDivStyle(taskDiv,backgroundColor,borderColor);
		
	var boundingDiv 	= 	document.getElementById('boundingDiv');
	boundingDiv.appendChild(taskDiv);

}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createTaskDivStyle(taskDiv,backgroundColor,borderColor) {
	
	log('Creating div style for ' + taskDiv.innerHTML);
	
	taskDiv.style.backgroundColor = backgroundColor;
	taskDiv.style.borderRadius	  = '4px';
	taskDiv.style.borderWidth	  = '2px 2px 2px 2px';
	taskDiv.style.borderStyle	  = 'solid solid solid solid';
	taskDiv.style.borderColor	  = borderColor;
	taskDiv.style.padding		  = '1px 5px 1px 5px';
	taskDiv.style.width			  = '200px';
	taskDiv.style.textAlign		  = 'center';
	
}
