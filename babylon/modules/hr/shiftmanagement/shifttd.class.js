//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************


function createShiftTDEvents() {
	
	log('Creating events for shiftTD-class.');
	
	$('.shiftTD').droppable ({
			
		drop : function(event,ui) {
		
			var fromCell = this;
			var droppedElement = ui.draggable.context;	
			
			fromCell.style.backgroundColor = 'white';
			
			log('Dropped element ' + ui.draggable.context + ' on ' +this + ' with droppableClass ' + ui.draggable.context.droppableClass);
			
			if (droppedElement.droppableClass=='dragHandle') return;
				
			if (!fromCell.parentNode.reserved) {
				
				if (droppedElement.draggableClass=='employeeDiv') shiftOnDrop(event,ui.draggable,fromCell);
				if (droppedElement.draggableClass=='taskDiv') shiftOnDrop(event,ui.draggable,fromCell);
			
			}
				
			
		},
		
		over : function(event,ui) {
			
			draggedOver = this;
			draggedOver.style.backgroundColor = 'black';
			
		},
		
		out : function(event,ui) {
			
			draggedOver = this;
			draggedOver.style.backgroundColor = 'white';
			
		}
		
	});
	
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function shiftOnDrop(event,draggable,fromCell) {
	
	var sourceDiv = draggable.context;
	var sourceRect= sourceDiv.getBoundingClientRect();
	var cellRect  = fromCell.getBoundingClientRect();
	var mouseX 	  = event.clientX;
	var mouseY 	  = event.clientY;
	
	log(sourceDiv + ' on coordinates ' + mouseX + ':' + mouseY + ' related to ' + fromCell);

	var fullShiftDiv = createFullShiftDiv(draggable,fromCell,event);

	$(fullShiftDiv).offset({top : cellRect.top, left : mouseX});

	document.body.appendChild(fullShiftDiv);
	
	fromCell.parentNode.reserved = true;
	
}