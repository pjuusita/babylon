
function smallTaskCard(cardID,taskID,stageID,sortOrder,assignedTo,priorityName,stageID,taskName,assignedImage,priorityImage) {
			
	var smallTaskCard			= document.createElement('div');
	
	smallTaskCard.taskID		= taskID;
	smallTaskCard.stageID 		= stageID;
	smallTaskCard.sortOrder 	= sortOrder;
	smallTaskCard.assignedTo	= assignedTo;
	smallTaskCard.priorityName	= priorityName;
	smallTaskCard.stageID		= stageID;
	smallTaskCard.taskName		= taskName;
	smallTaskCard.assignedImage = assignedImage;
	smallTaskCard.priorityImage = priorityImage;
	
	smallTaskCard.id			= 'taskCard-'+taskID;
	
	smallTaskCard.className		= 'taskCardSmall';
	smallTaskCard.nonWidgetClassName = 'taskCardSmall';
	
	createSmallTaskCardDOMStructure(smallTaskCard);
	
	createTaskCardDraggable(smallTaskCard);
	createTaskCardDroppable(smallTaskCard);
	
	createTaskCardBigDialog(smallTaskCard);
		
}

function createSmallTaskCardDOMStructure(smallTaskCard) {
	
	var taskCardTable 		= document.createElement('table');
	taskCardTable.className = 'taskCardTable';
	var firstRow	  		= taskCardTable.insertRow(-1);
	var secondRow	 		= taskCardTable.insertRow(-1);
		
	var assignedImgCell		= firstRow.insertCell(-1);
	var assignedNameCell	= firstRow.insertCell(-1);

	assignedImgCell.className 	= 'firstColumnSmallTaskCard';
	assignedNameCell.className 	= 'secondColumnSmallTaskCard';
	
	var priorityImgCell		= secondRow.insertCell(-1);
	var taskNameCell		= secondRow.insertCell(-1);
	
	var assignedImage		= document.createElement('img');
	var priorityImage		= document.createElement('img');
	
	assignedImage.className	= 'taskCardImage';
	priorityImage.className	= 'taskCardImage';
	
	assignedImage.src		= smallTaskCard.assignedImage;
	priorityImage.src		= smallTaskCard.priorityImage;
	
	assignedImgCell.appendChild(assignedImage);
	assignedNameCell.innerHTML = smallTaskCard.assignedTo;
	
	priorityImgCell.appendChild(priorityImage);
	taskNameCell.innerHTML	= smallTaskCard.taskName;
	
	var parentID			= 'stage-' + smallTaskCard.stageID;
	var appendTo			= document.getElementById(parentID);
	
	smallTaskCard.updateStageAction 	= appendTo.parentNode.parentNode.parentNode.updateStageAction;
	smallTaskCard.updateSortOrderAction = appendTo.parentNode.parentNode.parentNode.updateSortOrderAction;
	smallTaskCard.getFullTaskDataAction = appendTo.parentNode.parentNode.parentNode.getFullTaskDataAction;
	smallTaskCard.processID				= appendTo.parentNode.parentNode.parentNode.processID;
	
	smallTaskCard.appendChild(taskCardTable);
	
	appendTo.appendChild(smallTaskCard);
	
}

function createTaskCardBigDialog(smallTaskCard) {
	
	$(smallTaskCard).click(function() {
		createTemporaryTaskDialog(smallTaskCard);
	});
	
}

function createTemporaryTaskDialog(smallTaskCard) {
	
	getFullTaskData(smallTaskCard);
	
	
}

function createTemporaryTaskDialogDom(smallTaskCard,dataObject) {
	
	
	var bigDialog 				= document.createElement('div');
	var informationTable 		= document.createElement('table');

	var insertTimeComponents 	= dataObject['assignDate'].split(" "); 
	
	var processTypeRow			= informationTable.insertRow(-1);
	var typeNameCell			= processTypeRow.insertCell(-1);
	var stateCell				= processTypeRow.insertCell(-1);
	stateCell.innerHTML			= '<br>' + dataObject['taskName'] + '<br><br>';
	typeNameCell.innerHTML		= '<br>' + dataObject['processType'] + '<br><br>';
	
	typeNameCell.className		= 'firstColumnBigDialog';
	stateCell.className			= 'secondColumnBigDialog';
	
	var stageStateRow			= informationTable.insertRow(-1);
	var stageCell				= stageStateRow.insertCell(-1);
	var stateCell				= stageStateRow.insertCell(-1);
	stageCell.innerHTML			= '<br>' + dataObject['stageName'] + '<br><br>';
	stateCell.innerHTML			= '<br>' + dataObject['stateName'] + '<br><br>';
	
	stageCell.className			= 'firstColumnBigDialog';
	stateCell.className			= 'secondColumnBigDialog';
	
	var priorityRow		 		= informationTable.insertRow(-1);
	var priorityImageCell		= priorityRow.insertCell(-1);
	var priorityNameCell		= priorityRow.insertCell(-1);
	var priorityImage	 		= document.createElement('img');
	priorityImage.src			= dataObject['priorityImage'];
	priorityImage.className		= 'taskCardImage';
	priorityImageCell.appendChild(priorityImage);
	priorityNameCell.innerHTML	= dataObject['priorityName'];
	
	priorityImageCell.className	= 'firstColumnBigDialog';
	priorityNameCell.className	= 'secondColumnBigDialog';
	
	var taskRow					= informationTable.insertRow(-1);
	var taskNameCell			= taskRow.insertCell(-1);
	var insertTimeCell			= taskRow.insertCell(-1);
	insertTimeCell.innerHTML	= '<br>' + insertTimeComponents[0] + '<br><br>';
	taskNameCell.innerHTML		= "<br>Annettu<br><br>";
	
	taskNameCell.className		= 'firstColumnBigDialog';
	insertTimeCell.className	= 'secondColumnBigDialog';
	
	var assignedRow		 		= informationTable.insertRow(-1);
	var assignedImageCell		= assignedRow.insertCell(-1);
	var assignedNameCell		= assignedRow.insertCell(-1);
	var assignedImage	 		= document.createElement('img');
	assignedImage.src			= dataObject['assignedImage'];
	assignedImage.className		= 'taskCardImage';
	var assignedLabel			= document.createElement('label');
	assignedLabel.innerHTML		= '<br>Kenelle';
	assignedImageCell.appendChild(assignedImage);
	assignedImageCell.appendChild(assignedLabel);
	assignedNameCell.innerHTML	= dataObject['assigned'];
	
	assignedImageCell.className	= 'firstColumnBigDialog';
	assignedNameCell.className	= 'secondColumnBigDialog';
	
	var assigneeRow				= informationTable.insertRow(-1);
	var assigneeImageCell		= assigneeRow.insertCell(-1);
	var assigneeNameCell		= assigneeRow.insertCell(-1);
	var assigneeImage			= document.createElement('img');
	assigneeImage.className		= 'taskCardImage';
	assigneeImage.src			= dataObject['assigneeImage'];
	var assigneeLabel			= document.createElement('label');
	assigneeLabel.innerHTML		= '<br>Keneltä';
	assigneeImageCell.appendChild(assigneeImage);
	assigneeImageCell.appendChild(assigneeLabel);
	assigneeNameCell.innerHTML	= dataObject['assignee'];
	
	assigneeImageCell.className	= 'firstColumnBigDialog';
	assigneeNameCell.className	= 'secondColumnBigDialog';

	var spacerRow				= informationTable.insertRow(-1);
	var spacerCell				= spacerRow.insertCell(-1);
	spacerCell.innerHTML		= "&nbsp";
	
	var descriptionRow				= informationTable.insertRow(-1);
	var descriptionNameCell			= descriptionRow.insertCell(-1);
	var descriptionInfoCell			= descriptionRow.insertCell(-1);
	descriptionNameCell.innerHTML   = "Kuvaus";
	descriptionInfoCell.innerHTML   = dataObject['description'];
	descriptionNameCell.className	= 'firstColumnBigDialog';
	descriptionInfoCell.className	= 'secondColumnBigDialog';
	
	var spacerRow				= informationTable.insertRow(-1);
	var spacerCell				= spacerRow.insertCell(-1);
	spacerCell.innerHTML		= "&nbsp";
	
	
	if (dataObject['comments']!=null) {
		
		var comments				= dataObject['comments'].split(",");
		var commentDates			= dataObject['commentDates'].split(",");
		var commenters				= dataObject['commenters'].split(",");
		
		var commentCount			= comments.length;
	
		for(var index=0;index<commentCount-1;index++) {
		
			var commentRow			= informationTable.insertRow(-1);
			var commentInfo			= commentRow.insertCell(-1);
			var commentText			= commentRow.insertCell(-1);
		
			var commentTimeComponents 	= commentDates[index].split(" "); 
			
			commentInfo.innerHTML	= commentTimeComponents[0];
			commentText.innerHTML	= commenters[index] + " : " + comments[index];
		
		}
	}
	
	
	bigDialog.appendChild(informationTable);
	
	var titleString = ""+dataObject['processType']; 
	
	$(bigDialog).dialog({
		
		minWidth 	  : 750,
		title		  : titleString,
		closeOnEscape : true, 
		
		buttons 	: [{
			
			text	: "Ok",
			click	: function() {
				
				$(bigDialog).dialog("close");
			}
			
		}]
		
	});
	
}

function getFullTaskData(smallTaskCard) {
	
	var url 		= smallTaskCard.getFullTaskDataAction;
	var parameters  = "&taskID="+smallTaskCard.taskID+"&processID="+smallTaskCard.processID+"&stageID="+smallTaskCard.stageID;
	var dataObject  = [];
	
	$.getJSON(url, parameters, function() {
			
	}).done(function(reply) { 
	
		replyObject = reply[0];
		
		dataObject['taskName'] 		= replyObject['name'];
		dataObject['assignDate']	= replyObject['assignDate'];
		dataObject['assigned'] 		= replyObject['assigned'];
		dataObject['assignee'] 		= replyObject['assignee'];
		dataObject['priorityImage'] = replyObject['priorityImage'];
		dataObject['assignedImage'] = replyObject['assignedImage'];
		dataObject['assigneeImage'] = replyObject['assigneeImage'];
		dataObject['processName'] 	= replyObject['processName'];
		dataObject['stageName'] 	= replyObject['stageName'];
		dataObject['priorityName']	= replyObject['priorityName'];
		dataObject['processType']	= replyObject['processType'];
		dataObject['description']	= replyObject['description'];
		dataObject['stateName']		= replyObject['stateName'];
		
		
		if (replyObject['comments']!=null) {
			dataObject['comments']		= replyObject['comments'];;
			dataObject['commentDates']	= replyObject['commentDate'];
			dataObject['commenters']	= replyObject['commenters'];
		}
		
		console.log(reply[0]);
		
		createTemporaryTaskDialogDom(smallTaskCard,dataObject);
		
		
	});		
		
	
}