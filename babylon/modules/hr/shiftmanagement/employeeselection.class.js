//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function employeeSelection(employees) {
	
	var employeeSelection	  		 = document.createElement('div');
	var employeeSelectionTable 		 = document.createElement('table');
	
	employeeSelection.className 	 = 'employeeSelection';
	employeeSelection.draggableClass = 'employeeSelection';
	employeeSelectionTable.className = 'employeeSelectionTable';
	
	createEmployeeSelectionHeader(employeeSelection,employeeSelectionTable);
	createEmployeeSelectionMiddle(employeeSelection,employeeSelectionTable);
	createEmployeeSelectionFooter(employeeSelection,employeeSelectionTable);
	
	employeeSelection.appendChild(employeeSelectionTable);
	
	createDraggableEmployeeSelection(employeeSelection);
	
	deployeEmployeeSelection(employeeSelection);

	
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createDraggableEmployeeSelection(employeeSelection) {
	
	
	
	$(employeeSelection).draggable({

		appendTo 	: 'body'
		
		
	});
	
	
	
	
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function deployeEmployeeSelection(employeeSelection) {
	
	employeeSelection.style.position = 'fixed';
	employeeSelection.style.top	     = '0px';
	employeeSelection.style.left	 = '200px';
	employeeSelection.style.width	 = '960px';
	
	employeeSelection.headerLeft.style.width 	= '5%';
	employeeSelection.headerMiddle.style.width = '90%';
	employeeSelection.headerRight.style.width 	= '5%';
	
	document.body.appendChild(employeeSelection);
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createEmployeeSelectionHeader(employeeSelection,employeeSelectionTable) {
	
	var header							= employeeSelectionTable.createTHead();
	var headerRow						= header.insertRow(0);
	headerRow.className					= 'employeeSelectionHeader';
	
	var employeeSelectionHeaderLeft		= headerRow.insertCell(0);
	var employeeSelectionHeaderMiddle 	= headerRow.insertCell(1);
	var employeeSelectionHeaderRight	= headerRow.insertCell(2);
	
	createEmployeeSelectionHeaderLeft(employeeSelectionHeaderLeft);
	createEmployeeSelectionHeaderMiddle(employeeSelectionHeaderMiddle);
	createEmployeeSelectionHeaderRight(employeeSelectionHeaderRight,employeeSelection);
	
	employeeSelection.headerRow			= headerRow;
	employeeSelection.headerLeft		= employeeSelectionHeaderLeft;
	employeeSelection.headerMiddle		= employeeSelectionHeaderMiddle;
	employeeSelection.headerRight		= employeeSelectionHeaderRight;
	
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createEmployeeSelectionHeaderLeft(employeeSelectionHeaderLeft) {
	
	//employeeSelectionHeaderLeft.innerHTML = 'Header-left';
	
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createEmployeeSelectionHeaderMiddle(employeeSelectionHeaderMiddle) {
	
	employeeSelectionHeaderMiddle.innerHTML = 'Employees';
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createEmployeeSelectionHeaderRight(employeeSelectionHeaderRight,employeeSelection) {
	
	
	var element 		= employeeSelectionHeaderRight;
	var selectionWindow	= employeeSelection;
	
	$(element).draggable({

		helper	 	: 'clone',
		appendTo 	: 'body',
		
	drag : function(event,ui) {
			
		var mouseX 					= event.clientX;
		var mouseY					= event.clientY;
		var top						= parseInt(selectionWindow.style.top);
		var left					= parseInt(selectionWindow.style.left);
		
		var newWidth 				= mouseX - left;
		var newHeight 				= mouseY - top;
		
		selectionWindow.style.height  	= newHeight + 'px';
		selectionWindow.style.width 	= newWidth + 'px';
			
	}
	

	});
	
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createEmployeeSelectionMiddle(employeeSelection,employeeSelectionTable) {

	var middleRow						= employeeSelectionTable.insertRow();
	middleRow.className					= 'employeeSelectionMiddle';
	
	var employeeSelectionMiddleLeft		= middleRow.insertCell(0);
	var employeeSelectionEmployeeArea 	= middleRow.insertCell(1);
	var employeeSelectionMiddleRight	= middleRow.insertCell(2);
	
	createEmployeeSelectionMiddleLeft(employeeSelectionMiddleLeft);
	createEmployeeSelectionEmployeeArea(employeeSelectionEmployeeArea);
	createEmployeeSelectionMiddleRight(employeeSelectionMiddleRight);
	
	employeeSelection.middleRow			= middleRow;
	employeeSelection.middleLeft		= employeeSelectionMiddleLeft;
	employeeSelection.employeeArea		= employeeSelectionEmployeeArea;
	employeeSelection.middleRight		= employeeSelectionMiddleRight;

}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createEmployeeSelectionMiddleLeft(employeeSelectionHeaderLeft) {
	
	//employeeSelectionHeaderLeft.innerHTML = 'Middle-left';
	
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createEmployeeSelectionEmployeeArea(employeeSelectionArea) {
	
	var colors 					= globalVariables.colors;
	var resizingDiv 			= document.createElement('div');

	resizingDiv.style.width 	= '100%';
	resizingDiv.style.height 	= '100%';
	
	employeeSelectionArea.appendChild(resizingDiv);
	
	for(var colorName in colors) {
		
		log(colorName);
		
		var draggableEmployeeElement = draggableEmployee('Etunimi','Sukunimi',colors[colorName].normal,colors[colorName].dark);
		resizingDiv.appendChild(draggableEmployeeElement);
	}
	
	
	
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createEmployeeSelectionMiddleRight(employeeSelectionHeaderRight) {
	
	//employeeSelectionHeaderRight.innerHTML = 'Middle-right';
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createEmployeeSelectionFooter(employeeSelection,employeeSelectionTable) {
	
	var footer							= employeeSelectionTable.insertRow();
	footer.className					= 'employeeSelectionFooter';
	
	var employeeSelectionFooterLeft		= footer.insertCell(0);
	var employeeSelectionFooterMiddle 	= footer.insertCell(1);
	var employeeSelectionFooterRight	= footer.insertCell(2);
	
	//createEmployeeSelectionHeaderLeft(employeeSelectionFooterLeft);
	//createEmployeeSelectionHeaderMiddle(employeeSelectionFooterMiddle);
	//createEmployeeSelectionHeaderRight(employeeSelectionFooterRight);
	
	employeeSelection.footerRow			= footer;
	employeeSelection.footerLeft		= employeeSelectionFooterLeft;
	employeeSelection.footerMiddle		= employeeSelectionFooterMiddle;
	employeeSelection.footerRight		= employeeSelectionFooterRight;
	
}