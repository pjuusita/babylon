//*****************************************************************************************************************
//** FUNCTION SETTASKMANAGEMENTACTIONS(TABLEID,UPDATESTAGEACTION,UPDATESORTORDERACTION)
//**
//*****************************************************************************************************************

function setTaskManagementActions(tableID,updateStageAction,updateSortOrderAction,getFullTaskDataAction,processID) {
		
	var taskManagementTable 				  = document.getElementById(tableID);
	
	taskManagementTable.updateStageAction 	  = updateStageAction;
	taskManagementTable.updateSortOrderAction = updateSortOrderAction;
	taskManagementTable.getFullTaskDataAction = getFullTaskDataAction;
	taskManagementTable.processID			  = processID;
	
}

//*****************************************************************************************************************
//**  FUNCTION REDIRECTFROMFILTERSELECTION(SELECT,DATAVAR,ACTION)
//**
//*****************************************************************************************************************

function redirectFromFilterSelection(select,datavar,action) {
	
	var value 		= select.value;
	var urlParam	= '&'+datavar+'='+value;

	window.location = action + urlParam;
	
}

function redirectFromFilterSelectionTest(select) {
	
	alert('Trying to redirect');
	
	
}


//*****************************************************************************************************************
//**  FUNCTION CREATETASKCARDDRAGGABLE(ELEMENT)
//**
//*****************************************************************************************************************

function createTaskCardDraggable(element) {
	
	$(element).draggable({
			
		helper : "clone",
		
		start : function(event,ui) {
			
			$(ui.helper).css({opacity : 0.5});
			element.className = 'taskCardSmallDragged';
			
		},
	
		stop : function(event,ui) {
			
			element.className = 'taskCardSmall';
			
		}
				
	});
			
}

//*****************************************************************************************************************
//**  FUNCTION CREATETASKCARDDROPPABLE(ELEMENT)
//**
//*****************************************************************************************************************

function createTaskCardDroppable(element) {
	
	$(element).droppable({
		
		greedy : true,
		tolerance : "pointer",
		
		drop : function(event,ui) {
			
			var taskCard = ui.draggable.context;
			
			$(element).after(taskCard);
			
			element.className	= 'taskCardSmall';
			updateSortOrders('after',element,taskCard);
		},
		
		over : function(event,ui) {
			
			element.className	= 'taskCardSmallHelperHover';
			
		},
		
		out : function(event,ui) {
			
			element.className	= 'taskCardSmall';
			
		}
		
	});
	
}

//*****************************************************************************************************************
//**  FUNCTION CREATETASKDROPZONEFUNCTIONALITY()
//**
//*****************************************************************************************************************

function createTaskDropZoneFunctionality() {
	
	var dropZones	=	document.getElementsByClassName('taskArea');
	var length		=	dropZones.length;
	
	for(var index=0;index<length;index++) {
		
		var element = dropZones[index];
		
		createTaskDropZoneDroppable(element);
			
	}
	
}

//*****************************************************************************************************************
//**  FUNCTION CREATETASKDROPZONEDROPPABLE(ELEMENT)
//**
//*****************************************************************************************************************

function createTaskDropZoneDroppable(element) {

	$(element).droppable({
		
		greedy : true,
		tolerance : "pointer",
		
		drop : function(event,ui) {
			
			var taskCard = ui.draggable.context;	
			element.appendChild(taskCard);
			element.className	= 'taskArea';
			updateSortOrders('lastChild',element,taskCard);
		},
		
		over : function(event,ui) {
			
			element.className	= 'taskAreaHover';
		},
		
		out	: function(event,ui) {
			
			element.className	= 'taskArea';
	
		}
		
	});
	
}

function updateSortOrders(type,taskArea,taskCard) {
		
	if (type=='lastChild') {
		
		var nodeCount = taskArea.childNodes.length;
			
		if (nodeCount<=1) {
			
			// Just update stage.
			var stageID	  = taskArea.id;
			stageID		  = stageID.replace('stage-','');
			var stageNumber = parseInt(stageID);
				
			var url		   = taskCard.updateStageAction;
			var parameters = '&taskID='+taskCard.taskID+'&stageID='+stageNumber;
			
			$.getJSON(url, parameters, function() {
				
			}).done(function(reply) { 
			
				taskCard.stageID = stageNumber;
				
			});	
			
		} else {
		
			// Dropped to area instead of an another taskCard.
			
			var sortAfter 	= taskArea.childNodes[nodeCount-2].id;
			var stageID	  	= taskArea.id;
			stageID		  	= stageID.replace('stage-','');
			var stageNumber = parseInt(stageID);
			
			var url		   = taskCard.updateStageAction;
			var parameters = '&taskID='+taskCard.taskID+'&stageID='+stageNumber;
				
			$.getJSON(url, parameters, function() {
				
			}).done(function(reply) { 
			
				taskCard.stageID = stageNumber;
				
			});	
			
		}

	}
	
	// When dropped on other taskCard.
	
	if (type=='after') {
		
		taskArea		= taskArea.parentNode;
		var stageID	  	= taskArea.id;
		stageID		  	= stageID.replace('stage-','');
		var stageNumber = parseInt(stageID);
		
		var url		   = taskCard.updateStageAction;
		var parameters = '&taskID='+taskCard.taskID+'&stageID='+stageNumber;
			
		$.getJSON(url, parameters, function() {
			
		}).done(function(reply) { 
		
			taskCard.stageID = stageNumber;
			
		});	
				
	}
	
	// Update sortOrder
	
	var taskCount = taskArea.childNodes.length;
	var children  = taskArea.childNodes;
	var sortOrder = 1;
		
	for(var index=0;index<taskCount;index++) {
		
		var currentChild = taskArea.childNodes[index];
		
		if (currentChild.nonWidgetClassName=='taskCardSmall') {
			
			var url		   = currentChild.updateSortOrderAction;
			var parameters = '&taskID='+currentChild.taskID+'&sortOrder='+sortOrder;
					
			$.getJSON(url, parameters, function() {
				
			}).done(function(reply) { 
			
				taskCard.sortOrder = sortOrder;
				
			});	
			
			sortOrder++;
		}
		
	}
	
}


