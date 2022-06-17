function draggableEmployee(foreName,sureName,backgroundColor,borderColor) {
	
	var draggableEmployeeElement = document.createElement('div');
	
	draggableEmployeeElement.className 		= 'draggableEmployee';
	draggableEmployeeElement.draggableClass = 'employeeDiv';
	
	draggableEmployeeElement.innerHTML	= foreName + ' ' + sureName;

	draggableEmployeeElement.style.backgroundColor = backgroundColor;
	draggableEmployeeElement.style.borderColor	   = borderColor;
	
	createDraggableEmployee(draggableEmployeeElement);
	
	return draggableEmployeeElement;
	
}

function createDraggableEmployee(draggableEmployeeElement) {
	
	$(draggableEmployeeElement).draggable({

		helper	 	: 'clone',
		appendTo 	: 'body'

	});
}

