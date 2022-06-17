//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function createDropBetweenTDEvents() {
	
	log('Creating events for dropBetween-class.');
	
	$('.dropBetween').droppable ({
			
		drop : function(event,ui) {
		
			var fromCell = this;
			fromCell.style.backgroundColor = 'white';
			dropDivBetween(event,ui.draggable,fromCell);
			
		},
		
		over : function(event,ui) {
			
			var source = event.target;
			source.style.backgroundColor = 'black';
			
		},
		
		out : function(event,ui) {
			
			source = event.target;
			source.style.backgroundColor = 'white';
			
		}
		
	});
	
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function dropDivBetween(event,draggable,fromCell) {
	
	fromCell.style.backgroundColor = 'white';
	event.target.style.backgorundColor = 'white';
	
	var row			   = fromCell.parentNode;
	var rowIndex	   = row.rowIndex;
	var table		   = row.parentNode;
	
	log('Creating new rows ' + table + row + rowIndex);
	
	var cloneRowActive = table.rows[rowIndex-1].cloneNode(true);
	var cloneInBetween = table.rows[rowIndex].cloneNode(true); 

	cloneInBetween 	   = table.insertBefore(cloneInBetween,table.rows[row.rowIndex]);
	createDropBetweenTDEvents();
	
	cloneRowActive 	   = table.insertBefore(cloneRowActive,table.rows[row.rowIndex]);
	cloneRowActive.cells[0].innerHTML = '&nbsp';
	createShiftTDEvents();
	
	shiftOnDropBetween(event,draggable,table,table.rows[row.rowIndex].cells[1]);
	
	
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function shiftOnDropBetween(event,draggable,table,fromCell) {
	
	var fullShiftDiv 	= createFullShiftDiv(draggable,fromCell,event);
	
	document.body.appendChild(fullShiftDiv);

	var cellRect  		= fromCell.getBoundingClientRect();
	var rowRect	  		= fromCell.parentNode.getBoundingClientRect();
	var mouseX 	  		= event.clientX;
	
	$(fullShiftDiv).offset({top : (cellRect.top - 15)  , left : mouseX});
	
	var shiftRect 		= fullShiftDiv.getBoundingClientRect();
	
	log(fromCell + ' has parent ' + fromCell.parentNode);	
	log('Cell.top at ' + cellRect.top + ', row.top' + rowRect.top + ', shift.top at ' + shiftRect.top);
	
	updateShiftPositions(cellRect.top,fullShiftDiv);
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function waitForFrame() {
	
	requestAnimationFrame(waitForFrame);
	
}

//************************************************************************************************************************************************************
//** Function()
//** 
//************************************************************************************************************************************************************

function updateShiftPositions(fromPoint,shift) {
	
	
	var shifts = document.getElementsByClassName('fullShift');
	var len	   = shifts.length;
	
	log('Updating shift positions of array of ' + len + ' shifts.');
	
	
	for(var n=0;n<len;n++) {
	
		var parentCell 	= shifts[n].parentNode;
		var shiftRect	= shifts[n].getBoundingClientRect();
		var parentRect 	= parentCell.getBoundingClientRect();
		
		log(shifts[n]);
	
		if ((shiftRect.bottom>=fromPoint) && (shift!=shifts[n])) $(shifts[n]).offset({top :  shiftRect.top + 40, left : shiftRect.left});
		
	}
	
}