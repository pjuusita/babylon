function draggabletask(foreName,sureName,backgroundColor,borderColor) {
	
	var draggabletaskElement = document.createElement('div');
	
	draggabletaskElement.className = 'draggableTask';
	draggabletaskElement.draggableClass = 'taskDiv';
	draggabletaskElement.innerHTML	= 'Työtehtävä jokutehtävä';

	draggabletaskElement.style.backgroundColor = backgroundColor;
	draggabletaskElement.style.borderColor	   = borderColor;
	
	createDraggabletask(draggabletaskElement);
	
	return draggabletaskElement;
	
}

function createDraggabletask(draggabletaskElement) {
	
	$(draggabletaskElement).draggable({

		helper	 	: 'clone',
		appendTo 	: 'body'

	});
}

