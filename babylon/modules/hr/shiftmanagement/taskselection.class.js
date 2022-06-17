//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function taskSelection(tasks) {
	
	var taskSelection	   		 = document.createElement('div');
	var taskSelectionTable 		 = document.createElement('table');
	
	taskSelection.className 	 = 'taskSelection';
	taskSelection.draggableClass = 'taskSelection';
	taskSelectionTable.className = 'taskSelectionTable';
	
	
	createtaskSelectionHeader(taskSelection,taskSelectionTable);
	createtaskSelectionMiddle(taskSelection,taskSelectionTable);
	createtaskSelectionFooter(taskSelection,taskSelectionTable);
	
	taskSelection.appendChild(taskSelectionTable);
	
	createDraggabletaskSelection(taskSelection);
	
	deployetaskSelection(taskSelection);

	
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createDraggabletaskSelection(taskSelection) {
	
	$(taskSelection).draggable({

		appendTo 	: 'body'
		
	});
	
	
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function deployetaskSelection(taskSelection) {
	
	taskSelection.style.position = 'fixed';
	taskSelection.style.top		 = '80px';
	taskSelection.style.left	 = '200px';
	taskSelection.style.width	 = '960px';
	
	taskSelection.headerLeft.style.width 	= '5%';
	taskSelection.headerMiddle.style.width = '90%';
	taskSelection.headerRight.style.width 	= '5%';
	
	document.body.appendChild(taskSelection);
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createtaskSelectionHeader(taskSelection,taskSelectionTable) {
	
	var header							= taskSelectionTable.createTHead();
	var headerRow						= header.insertRow(0);
	headerRow.className					= 'taskSelectionHeader';
	
	var taskSelectionHeaderLeft		= headerRow.insertCell(0);
	var taskSelectionHeaderMiddle 	= headerRow.insertCell(1);
	var taskSelectionHeaderRight	= headerRow.insertCell(2);
	
	createtaskSelectionHeaderLeft(taskSelectionHeaderLeft);
	createtaskSelectionHeaderMiddle(taskSelectionHeaderMiddle);
	createtaskSelectionHeaderRight(taskSelectionHeaderRight);
	
	taskSelection.headerRow			= headerRow;
	taskSelection.headerLeft		= taskSelectionHeaderLeft;
	taskSelection.headerMiddle		= taskSelectionHeaderMiddle;
	taskSelection.headerRight		= taskSelectionHeaderRight;
	
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createtaskSelectionHeaderLeft(taskSelectionHeaderLeft) {
	
	taskSelectionHeaderLeft.innerHTML = '';
	
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createtaskSelectionHeaderMiddle(taskSelectionHeaderMiddle) {
	
	taskSelectionHeaderMiddle.innerHTML = 'Tasks';
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createtaskSelectionHeaderRight(taskSelectionHeaderRight) {
	
	taskSelectionHeaderRight.innerHTML = '';
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createtaskSelectionMiddle(taskSelection,taskSelectionTable) {

	var middleRow						= taskSelectionTable.insertRow();
	middleRow.className					= 'taskSelectionMiddle';
	
	var taskSelectionMiddleLeft		= middleRow.insertCell(0);
	var taskSelectiontaskArea 		= middleRow.insertCell(1);
	var taskSelectionMiddleRight	= middleRow.insertCell(2);
	
	createtaskSelectionMiddleLeft(taskSelectionMiddleLeft);
	createtaskSelectiontaskArea(taskSelectiontaskArea);
	createtaskSelectionMiddleRight(taskSelectionMiddleRight);
	
	taskSelection.middleRow			= middleRow;
	taskSelection.middleLeft		= taskSelectionMiddleLeft;
	taskSelection.taskArea			= taskSelectiontaskArea;
	taskSelection.middleRight		= taskSelectionMiddleRight;

}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createtaskSelectionMiddleLeft(taskSelectionHeaderLeft) {
	
	//taskSelectionHeaderLeft.innerHTML = 'Middle-left';
	
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createtaskSelectiontaskArea(taskSelectionHeaderMiddle) {
	
	var colors = globalVariables.colors;

	for(var colorName in colors) {
		
		log(colorName);
		
		var draggabletaskElement = draggabletask('Etunimi','Sukunimi',colors[colorName].normal,colors[colorName].dark);
		taskSelectionHeaderMiddle.appendChild(draggabletaskElement);
	}
	
	
	
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createtaskSelectionMiddleRight(taskSelectionHeaderRight) {
	
	//taskSelectionHeaderRight.innerHTML = 'Middle-right';
}

//*****************************************************************************************************************************************************************
//**
//**
//*****************************************************************************************************************************************************************

function createtaskSelectionFooter(taskSelection,taskSelectionTable) {
	
	var footer							= taskSelectionTable.insertRow();
	footer.className					= 'taskSelectionFooter';
	
	var taskSelectionFooterLeft		= footer.insertCell(0);
	var taskSelectionFooterMiddle 	= footer.insertCell(1);
	var taskSelectionFooterRight	= footer.insertCell(2);
	
	//createtaskSelectionHeaderLeft(taskSelectionFooterLeft);
	//createtaskSelectionHeaderMiddle(taskSelectionFooterMiddle);
	//createtaskSelectionHeaderRight(taskSelectionFooterRight);
	
	taskSelection.footerRow			= footer;
	taskSelection.footerLeft		= taskSelectionFooterLeft;
	taskSelection.footerMiddle		= taskSelectionFooterMiddle;
	taskSelection.footerRight		= taskSelectionFooterRight;
	
}