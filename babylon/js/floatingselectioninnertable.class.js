//****************************************************************************************************************************************************************
//** FUNCTION FLOATINGSELECTIONINNERTABLE(title,selectionWindow)
//** Creates inner-table for floating selection.
//****************************************************************************************************************************************************************

function floatingSelectionInnerTable(title,selectionWindow) {
	
		document.createElement('table');
		
		var innerTable			= document.createElement('table');

		innerTable.className	= 'floatingSelectionTable';
		innerTable.style.width	= '100%';
		innerTable.style.height = '100%';
		
		createHeader(innerTable,title,selectionWindow);
		createDraggableArea(innerTable);
		createFooter(innerTable,title,selectionWindow);
		
		return innerTable;
}

//****************************************************************************************************************************************************************
//**
//**
//****************************************************************************************************************************************************************

function createHeader(innerTable,title,selectionWindow) {
	
	var headerRow  			= innerTable.insertRow(0);
	
	headerRow.className		= 'floatingSelectionTableHeader';
	
	var left 				= headerRow.insertCell(0);
	var middle				= headerRow.insertCell(1);
	var right				= headerRow.insertCell(2);

	left.style.width   = '10%';
	middle.style.width = '80%';
	right.style.width  = '10%';
	
	left.innerHTML			= '&nbsp';
	middle.innerHTML 		= title;
	right.innerHTML			= '&nbsp';
	
	left.object = left;
	left.object.draggableClass = 'sizer';
	
	right.object = right;
	right.object.draggableClass = 'sizer';
	
	createResizeHandleHeaderLeft(left,selectionWindow);
	createPopToFront(middle,selectionWindow);
	createResizeHandleHeaderRight(right,selectionWindow);
	
}

//****************************************************************************************************************************************************************
//**
//**
//****************************************************************************************************************************************************************

function createDraggableArea(innerTable) {
	
	var draggableRow 		 	= innerTable.insertRow(1);
	draggableRow.className   	= 'floatingSelectionDraggableArea';
	
	var draggableArea 		 	= draggableRow.insertCell();
	
	draggableArea.colSpan		= '3';
	draggableArea.style.width	= '100%';
	
	innerTable.draggableArea 	= draggableArea;
	
}

//****************************************************************************************************************************************************************
//**
//**
//****************************************************************************************************************************************************************

function createFooter(innerTable,title,selectionWindow) {
	
	var footerRow  			= innerTable.insertRow(2);
	footerRow.className		= 'floatingSelectionDraggableArea';
	
	var left 				= footerRow.insertCell(0);
	var middle				= footerRow.insertCell(1);
	var right				= footerRow.insertCell(2);

	left.style.width  		= '10%';
	middle.style.width 		= '80%';
	right.style.width  		= '10%';
	
	left.innerHTML			= '&nbsp';
	middle.innerHTML 		= '&nbsp';
	right.innerHTML			= '&nbsp';
	
	createResizeHandleFooterLeft(left,selectionWindow);
	createResizeHandleFooterRight(right,selectionWindow);
	
}

//****************************************************************************************************************************************************************
//**
//**
//****************************************************************************************************************************************************************

function createResizeHandleHeaderRight(element,selectionWindow) {
		
	$(element).draggable({

		helper	 	: 'clone',
		appendTo 	: 'body',
		
	drag : function(event,ui) {
			
		var mouseX 						= event.clientX;
		var mouseY						= event.clientY;
		var windowY						= $(window).scrollTop();
		var top							= parseInt(selectionWindow.style.top);
		var left						= parseInt(selectionWindow.style.left);
		
		var newWidth 					= mouseX - left;
		var newHeight 					= mouseY - top;
		
		selectionWindow.style.height  	= newHeight + 'px';
		selectionWindow.style.width 	= newWidth + 'px';
		
		$(selectionWindow).offset({top : top + windowY, left : left});
		
	}
	
	});
	
}

//****************************************************************************************************************************************************************
//**
//**
//****************************************************************************************************************************************************************

function createResizeHandleHeaderLeft(element,selectionWindow) {
		
	$(element).draggable({

		helper	 	: 'clone',
		appendTo 	: 'body',
		
	drag : function(event,ui) {
		
		var boundingRect			= selectionWindow.getBoundingClientRect();
		var windowY					= $(window).scrollTop();
		var mouseX 					= event.clientX;
		var mouseY					= event.clientY; 
		var top						= boundingRect.top;
		var left					= boundingRect.left; 
		var oldWidth				= parseInt(selectionWindow.style.width);
		var oldHeight				= parseInt(selectionWindow.style.heigh);
		
		//console.log('WindowY : ' + windowY + ', mouseY : ' + mouseY + ', top : ' + top);
		
		var widthChange				= left - mouseX;
		var heightChange 			= top  - mouseY;
		
		selectionWindow.style.height  	= oldHeight + heightChange + 'px';
		selectionWindow.style.width 	= oldWidth + widthChange + 'px';
		
		$(selectionWindow).offset({top : windowY + mouseY , left : mouseX});
		
	}
	
	});
	
}

//****************************************************************************************************************************************************************
//**
//**
//****************************************************************************************************************************************************************

function createResizeHandleFooterLeft(element,selectionWindow) {
	
	$(element).draggable({

		helper	 	: 'clone',
		appendTo 	: 'body',
		
	drag : function(event,ui) {
			
		var windowY					= $(window).scrollTop();
		var mouseX 					= event.clientX;
		var mouseY					= event.clientY; 
		var top						= boundingRect.top;
		var left					= boundingRect.left; 
		var oldWidth				= parseInt(selectionWindow.style.width);
		var oldHeight				= parseInt(selectionWindow.style.heigh);
		
		//console.log('WindowY : ' + windowY + ', mouseY : ' + mouseY + ', top : ' + top);
		
		var widthChange				= left - mouseX;
		var heightChange 			= top  - mouseY;
		
		selectionWindow.style.height  	= oldHeight + heightChange + 'px';
		selectionWindow.style.width 	= oldWidth + widthChange + 'px';
		
		$(selectionWindow).offset({top : windowY + mouseY , left : mouseX});
			
	}
	
	});
	
}

//****************************************************************************************************************************************************************
//**
//**
//****************************************************************************************************************************************************************

function createResizeHandleFooterRight(element,selectionWindow) {
	
	$(element).draggable({

		helper	 	: 'clone',
		appendTo 	: 'body',
		
	drag : function(event,ui) {
			
		var mouseX 					= event.clientX;
		var mouseY					= event.clientY;
		var top						= parseInt(selectionWindow.style.top);
		var left					= parseInt(selectionWindow.style.left);
		
		
		selectionWindow.style.height  	= (mouseY - top) + 'px';
		selectionWindow.style.width 	= (mouseX - left) + 'px';
		
		$(selectionWindow).offset({top : top, left : left});
				
	}
	
	});
}

//****************************************************************************************************************************************************************
//**
//**
//****************************************************************************************************************************************************************

function createPopToFront(element,selectionWindow) {
	
	$(selectionWindow).click(function() {
		
		console.log('Floating-section drag-portion clicked.');
		$(selectionWindow).parent().append($(selectionWindow));
		
	});
		
}