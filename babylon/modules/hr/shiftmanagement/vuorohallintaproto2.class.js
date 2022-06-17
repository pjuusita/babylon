
draggedShift = null;

//*************************************************************************************************************************************************************

function employeeBlockOnStartDrag(event) {
	
	var employeeName	 = event.target.innerHTML;
	var backgroundColor  = event.target.style.backgroundColor;
	var id				 = 'newShift';
	var data		     = employeeName + ':' + backgroundColor + ':' + id;
	
	console.log('Setting as data ' + data);
	
	event.dataTransfer.setData('text',data);		
}

//*************************************************************************************************************************************************************

function hourTableOnDragEnter(event) {
	
	preventDefaults(event);
	
}

//*************************************************************************************************************************************************************

function hourTableOnDragOver(event) {
	
	preventDefaults(event);
	
	var element				  = event.target;																							
	var elementPos			  = element.getBoundingClientRect();																		
	var elementHeightMid	  = (elementPos.bottom - elementPos.top) / 2;																
	var	mouseX		  		  = event.clientX; 																							
	var mouseY		  		  = event.clientY;
	
	var elementMid			  = elementPos.top + elementHeightMid;																	
	
	var row 				  = element.parentNode.parentNode;																										
	var rowIndex 			  = row.rowIndex;																												
	var table				  = row.parentNode;
	
	if (elementMid<mouseY) {
		
		//console.log('On bottom' + mouseY );	
		
		if (rowIndex>1) {
			table.rows[rowIndex-1].style.display = 'table-row';
		}
		
		table.rows[rowIndex+1].style.display = 'none';
	}
	
	if (elementMid>=mouseY){
		
		//console.log('On top' + mouseY);	
		
		if (rowIndex>1) {
			table.rows[rowIndex-1].style.display = 'none';
		}
	
		table.rows[rowIndex+1].style.display = 'table-row';	
	
	}
}

//*************************************************************************************************************************************************************

function hourTableOnDragLeave(event) {
	
	preventDefaults(event);
	
}

//*************************************************************************************************************************************************************

function hourTableOnDrop(event) {
	
	preventDefaults(event);

	if (draggedShift==null) createNewShift(event);
	if (draggedShift!=null) moveOldShift(event);

}

//*************************************************************************************************************************************************************

function createNewShift(event) {
	
	var shift			  = null;
	var data 			  = event.dataTransfer.getData('text').split(':');	
	var draggedID		  = data[2];
	
	var coords 			  = event.target.getBoundingClientRect();
	var mouseX			  = event.clientX;
	var mouseY			  = event.clientY;
	
	shift 	= createDraggableDiv();		
	document.body.appendChild(shift);

	$(shift).offset({top : coords.top , left : mouseX});
	
}

//*************************************************************************************************************************************************************

function moveOldShift(event) {
	
	var coords			  = event.target.getBoundingClientRect();
	var mouseX			  = event.clientX;
	var mouseY			  = event.clientY;
	
	$(draggedShift).offset({top : coords.top , left : mouseX});
	
	draggedShift = null;
	
}

//*************************************************************************************************************************************************************
//*************************************************************************************************************************************************************
//*************************************************************************************************************************************************************
//*************************************************************************************************************************************************************
//*************************************************************************************************************************************************************
//*************************************************************************************************************************************************************
//*************************************************************************************************************************************************************
//*************************************************************************************************************************************************************
//*************************************************************************************************************************************************************
//*************************************************************************************************************************************************************
//*************************************************************************************************************************************************************
//*************************************************************************************************************************************************************
//*************************************************************************************************************************************************************

function createDraggableDiv() {
	
	var shift 						= document.createElement("div");
	
	shift.id						= 'draggableDiv';
	shift.style.width				= '200px';
	shift.draggable					= true;
	shift.style.position			= 'absolute';
	
	var table 						= document.createElement("table");
	table.style.width				= '100%';
	table.style.padding				= '0px 0px 0px 0px';
	table.style.borderCollapse		= 'collapse';
	
	var row 						= table.insertRow();
	
	var leftHandle 					= row.insertCell();
	var nameLabel  					= row.insertCell();
	var destroyHandle 				= row.insertCell();
	var rightHandle 				= row.insertCell();
	
	var data 						= event.dataTransfer.getData('text').split(':');	
	
	console.log(data);
	
	leftHandle.style.backgroundColor 	= data[1];
	
	nameLabel.style.backgroundColor 	= data[1];
	
	leftHandle.draggable				 	= true;
	nameLabel.draggable				 		= true;
	destroyHandle.draggable				 	= true;
	rightHandle.draggable				 	= true;
	
	destroyHandle.style.backgroundColor = data[1];
	rightHandle.style.backgroundColor 	= data[1];
	
	leftHandle.innerHTML 				= 'O';
	nameLabel.innerHTML  				= data[0];
	nameLabel.style.textAlign			= 'center';
	destroyHandle.innerHTML 			= 'X';
	rightHandle.innerHTML 				= 'O';
	
	var emptyImage						= document.createElement('img');

	var startCoords;
	
	leftHandle.addEventListener('dragstart',function(ev) {
		
		ev.dataTransfer.setDragImage(emptyImage,0,0);
		
		draggedShift = shift;
		
		shift.style.borderStyle = 'solid solid solid solid';
		
		shift.stringData = 'Dynamically included string object';
	
		startCoords		 = shift.getBoundingClientRect();
		
	},false);

	leftHandle.addEventListener('drag',function(ev) {
		
		var target = shift;
		
		var mouseX = ev.clientX;
		var mouseY = ev.clientY;
		var coords = shift.getBoundingClientRect();
		
		console.log('dragging left handle ' + target);
		
		var width = startCoords.right - coords.left;
		
		shift.style.width = width + 'px';
		
		$(target).offset({top : coords.top,left:mouseX+5});
		
		
		
	},false);
	
	leftHandle.addEventListener('dragend',function(ve) {
		
		var target = shift;
		
		target.style.borderStyle = 'none none none none';
		
		console.log(target.stringData);
		
	},false);
	
	rightHandle.addEventListener('dragstart',function(ev) {
		
		ev.dataTransfer.setDragImage(emptyImage,0,0);
		
		draggedShift = shift;
		
		shift.style.borderStyle = 'solid solid solid solid';
		
		shift.stringData = 'Dynamically included string object';
	
		startCoords		 = shift.getBoundingClientRect();
		
	},false);

	rightHandle.addEventListener('drag',function(ev) {
		
		var target = shift;
		
		var mouseX = ev.clientX;
		var mouseY = ev.clientY;
		var coords = shift.getBoundingClientRect();
		
		console.log('dragging right handle ' + target);
		
		var width = (mouseX - coords.left); 
		
		shift.style.width = width + 'px';

		
	},false);
	
	rightHandle.addEventListener('dragend',function(ve) {
		
		var target = shift;
		
		target.style.borderStyle = 'none none none none';
		
		console.log(target.stringData);
		
	},false);
	
	
	nameLabel.addEventListener('dragstart',function(ev) {
		
		ev.dataTransfer.setDragImage(emptyImage,0,0);
		
		draggedShift = shift;
		
		shift.style.borderStyle = 'solid solid solid solid';
		
		shift.stringData = 'Dynamically included string object';
	
	},false);
	
	nameLabel.addEventListener('drag',function(ev) {
		
		var target = shift;
		
		var mouseX = event.clientX;
		var mouseY = event.clientY;
		
		mouseX = Math.round(mouseX / 10) * 10;
		
		$(target).offset({top : mouseY,left:mouseX + 5}); 
		
	},false);
	
	nameLabel.addEventListener('dragend',function(ve) {
		
		var target = shift;
		
		target.style.borderStyle = 'none none none none';
		
		console.log(target.stringData);
		
	},false);
	

	shift.appendChild(table);
	
	return shift;
	
}

//*************************************************************************************************************************************************************

function preventDefaults(event) {
	
	event.preventDefault();
	
}