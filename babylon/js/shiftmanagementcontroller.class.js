//********************************************************************************************************************************************************************
//** FUNCTION SHIFTMANAGEMENTCONTROLLER(UPDATEACTION,GROUPBY,COLORBY,DATA,DAYS)
//** Creates shiftManagementController.
//********************************************************************************************************************************************************************

function shiftManagementController(parentElement,updateAction,printAction,groupBy,colorBy,date,days) {
	
	//console.log('DATE -> ' + date);
	
	this.element = createShiftManagementComponent(parentElement,updateAction,printAction,groupBy,colorBy,date,days);
	return this;
	
}

//********************************************************************************************************************************************************************
//** FUNCTION CREATESHIFTMANAGEMENTCOMPONENT(updateAction,groupBy,colorBy,date,days)
//** Creates shiftManagementComponent.
//********************************************************************************************************************************************************************

function createShiftManagementComponent(ancestorElement,updateAction,printAction,groupBy,colorBy,date,days) {
	
	var toolbar 				= document.createElement('div');
	
	toolbar.className 			= "ui-widget-header ui-corner-all";
	toolbar.style.padding		= '4px';
	
	var toolbarTable			= document.createElement('table');
	var headerRow 				= toolbarTable.insertRow();
	var toolRow 				= toolbarTable.insertRow();
	
	createShiftManagementControllerHeader(headerRow);
	createShiftManagementControllerToolRow(toolRow,ancestorElement,updateAction,printAction,groupBy,colorBy,date,days);
	
	toolbar.appendChild(toolbarTable);
	
	return toolbar;
	
}

//********************************************************************************************************************************************************************
//** FUNCTION CREATESHIFTMANAGEMENTCONTROLLERTOOLROW(toolRow,updateAction,groupBy,colorBy,date,days)
//** Creates tooltow.
//********************************************************************************************************************************************************************

function createShiftManagementControllerToolRow(toolRow,ancestorElement,updateAction,printAction,groupBy,colorBy,date,days) {
	
	var datePick			= toolRow.insertCell();
	createShiftManagementControllerDatePicker(datePick,date,updateAction);
	
	var timeWindow			= toolRow.insertCell();
	createShiftManagementControllerTimeWindow(timeWindow,days,updateAction);

	//var viewWindow			= toolRow.insertCell();
	//createShiftManagementControllerViewWindow(viewWindow);
	
	var groupingWindow		= toolRow.insertCell();
	createShiftManagementControllerGroupingWindow(groupingWindow,updateAction,groupBy);
	
	var colorCodingWindow	= toolRow.insertCell();
	createShiftManagementControllerColorCodingWindow(colorCodingWindow,updateAction,colorBy);
	
	var printDialogCell			= toolRow.insertCell();
			
	var printDialog 			= createShiftManagementPrintDialog(printDialog,updateAction,printAction,ancestorElement.employeeData,ancestorElement.taskData,ancestorElement.projectData,date);
	
	var printButton				= document.createElement('button');
	printButton.style.height	= '25px';
	printButton.innerHTML		= 'PDF';
	printButton.value			= 'PDF';
	printButton.onclick			= function(event) { $(printDialog).dialog("open");};
	
	printDialogCell.appendChild(printButton);
	
}

//********************************************************************************************************************************************************************
//** FUNCTION CREATESHIFTMANAGEMENTCONTROLLERCOLORCODINGWINDOW(colorCodingWindow,updateAction,colorBy)
//** Creates color coding controls.
//********************************************************************************************************************************************************************

function createShiftManagementControllerColorCodingWindow(colorCodingWindow,updateAction,colorBy) {
	
	colorCodingWindow.style.textAlign = 'center';
	
	var siteView			= document.createElement('input');
	siteView.id				= "siteColoring";
	siteView.name			= "colorSelection";
	siteView.value			= "project";
	
	var taskView			= document.createElement('input');
	taskView.id				= "taskColoring";
	taskView.name			= "colorSelection";
	taskView.value			= "task";
	
	var employeeView 		= document.createElement('input');
	employeeView.id			= "employeeColoring";
	employeeView.name		= "colorSelection";
	employeeView.value		= "employee";
	
	var siteLabel										= document.createElement('label');
	siteLabel.id										= "siteLabelColoring";
	if (colorBy=='project') siteLabel.className			= "projectLabelViewPressed";
	if (colorBy!='project') siteLabel.className			= "projectLabelView";
	
	
	var taskLabel										= document.createElement('label');
	taskLabel.id										= "taskLabelColoring";
	if (colorBy=='task') taskLabel.className			= "taskLabelViewPressed";
	if (colorBy!='task') taskLabel.className			= "taskLabelView";
	
	var employeeLabel									= document.createElement('label');
	employeeLabel.id									= "employeeLabelColoring";
	if (colorBy=='employee') employeeLabel.className	= "employeeLabelViewPressed";
	if (colorBy!='employee') employeeLabel.className	= "employeeLabelView";
	
	siteLabel.innerHTML		= "&nbsp&nbsp&nbsp&nbsp&nbsp";
	taskLabel.innerHTML		= "&nbsp&nbsp&nbsp&nbsp&nbsp";
	employeeLabel.innerHTML = "&nbsp&nbsp&nbsp&nbsp&nbsp";
	
	siteLabel.htmlFor		= "siteColoring";
	taskLabel.htmlFor		= "taskColoring";
	employeeLabel.htmlFor	= "employeeColoring";
	
	siteView.setAttribute("type","radio");
	taskView.setAttribute("type","radio");
	employeeView.setAttribute("type","radio");
	
	colorCodingWindow.appendChild(siteView);
	colorCodingWindow.appendChild(taskView);
	colorCodingWindow.appendChild(employeeView);
	
	$(siteView).hide();
	$(taskView).hide();
	$(employeeView).hide();
	
	colorCodingWindow.appendChild(siteLabel);
	colorCodingWindow.appendChild(taskLabel);
	colorCodingWindow.appendChild(employeeLabel);
	
	if (colorBy=='project') siteView.checked = true;
	if (colorBy=='task') 	taskView.checked = true;
	if (colorBy=='employee') employeeView.checked = true;
	
	createRadioButtonEventsThreeChoiceColoring(siteLabel,taskLabel,employeeLabel,updateAction);
	
}

//********************************************************************************************************************************************************************
//** FUNCTION CREATESHIFTMANAGEMENTCONTROLLERVIEWWINDOW(viewWindow)
//** Creates view controls.
//********************************************************************************************************************************************************************

function createShiftManagementControllerViewWindow(viewWindow) {
	
	viewWindow.style.textAlign = 'center';
	
	var horizontalView				= document.createElement('input');
	horizontalView.id				= "horizontalView";
	horizontalView.name				= "horizontalView";
	
	var verticalView				= document.createElement('input');
	verticalView.id					= "verticalView";
	verticalView.name				= "verticalView";
	
	var horizontalLabelView			= document.createElement('label');
	horizontalLabelView.id			= "horizontalLabelView";
	horizontalLabelView.className	= "horizontalLabelView";
	
	var verticalLabelView			= document.createElement('label');
	verticalLabelView.id			= "verticalLabelView";
	verticalLabelView.className		= "verticalLabelView";
	
	horizontalLabelView.innerHTML	= "&nbsp&nbsp&nbsp&nbsp&nbsp";
	verticalLabelView.innerHTML		= "&nbsp&nbsp&nbsp&nbsp&nbsp";
	
	horizontalLabelView.htmlFor		= "horizontalView";
	verticalLabelView.htmlFor		= "verticalView";
	
	horizontalView.setAttribute("type","radio");
	verticalView.setAttribute("type","radio");
	
	$(horizontalView).hide();
	$(verticalView).hide();
	
	
	viewWindow.appendChild(horizontalView);
	viewWindow.appendChild(verticalView);
	
	viewWindow.appendChild(horizontalLabelView);
	viewWindow.appendChild(verticalLabelView);
	
	createRadioButtonEventsTwoChoice(horizontalLabelView,verticalLabelView);
	
}

//********************************************************************************************************************************************************************
//** FUNCTION CREATESHIFTMANAGEMENTCONTROLLERGROUPINGWINDOW(groupingWindow,updateAction,groupBy);
//** Creates grouping controls.
//********************************************************************************************************************************************************************

function createShiftManagementControllerGroupingWindow(groupingWindow,updateAction,groupBy) {
	
	groupingWindow.style.textAlign = 'center';
	
	var siteView			= document.createElement('input');
	siteView.id				= "siteView";
	siteView.name			= "viewSelection";
	siteView.value			= "project";
	
	var taskView			= document.createElement('input');
	taskView.id				= "taskView";
	taskView.name			= "viewSelection";
	taskView.value			= "task";
	
	var employeeView 		= document.createElement('input');
	employeeView.id			= "employeeView";
	employeeView.name		= "viewSelection";
	employeeView.value		= "employee";
	
	var siteLabel			= document.createElement('label');
	siteLabel.id			= "siteLabelView";
	if (groupBy=='project') siteLabel.className			= "projectLabelViewPressed";
	if (groupBy!='project') siteLabel.className			= "projectLabelView";
	
	var taskLabel			= document.createElement('label');
	taskLabel.id			= "taskLabelView";
	if (groupBy=='task') taskLabel.className			= "taskLabelViewPressed";
	if (groupBy!='task') taskLabel.className			= "taskLabelView";
	
	var employeeLabel		= document.createElement('label');
	employeeLabel.id		= "employeeLabelView";
	if (groupBy=='employee') employeeLabel.className	= "employeeLabelViewPressed";
	if (groupBy!='employee') employeeLabel.className	= "employeeLabelView";
	
	siteLabel.innerHTML		= "&nbsp&nbsp&nbsp&nbsp&nbsp";
	taskLabel.innerHTML		= "&nbsp&nbsp&nbsp&nbsp&nbsp";
	employeeLabel.innerHTML = "&nbsp&nbsp&nbsp&nbsp&nbsp";
	
	siteLabel.htmlFor		= "siteView";
	taskLabel.htmlFor		= "taskView";
	employeeLabel.htmlFor	= "employeeView";
	
	siteView.setAttribute("type","radio");
	taskView.setAttribute("type","radio");
	employeeView.setAttribute("type","radio");
	
	groupingWindow.appendChild(siteView);
	groupingWindow.appendChild(taskView);
	groupingWindow.appendChild(employeeView);
	
	$(siteView).hide();
	$(taskView).hide();
	$(employeeView).hide();
	
	if (groupBy=='project') siteView.checked = true;
	if (groupBy=='task') 	taskView.checked = true;
	if (groupBy=='employee') employeeView.checked = true;
	
	groupingWindow.appendChild(siteLabel);
	groupingWindow.appendChild(taskLabel);
	groupingWindow.appendChild(employeeLabel);
	
	createRadioButtonEventsThreeChoiceGrouping(siteLabel,taskLabel,employeeLabel,updateAction);
	
}

//********************************************************************************************************************************************************************
//** FUNCTION CREATESHIFTMANAGEMENTCONTROLLERDATAPICKER(datePick,date);
//** Creates datePicker.
//********************************************************************************************************************************************************************

function createShiftManagementControllerDatePicker(datePick,date,updateAction) {
		
	var datePicker 				= document.createElement('input');
	
	datePicker.id				= 'startDatePicker';
	datePicker.style.position 	= 'relative';
	datePicker.style.zIndex	  	= 11;
	
	$(datePicker).datepicker();
	$(datePicker).datepicker('setDate', new Date());
	$(datePicker).datepicker('option','dateFormat','yy-mm-dd');

	datePicker.value			= date;
	
	datePick.appendChild(datePicker);
	
	$(datePicker).change(
			
			function() {
			
			var colorBy		   		= '';
			var groupBy				= '';
			var colorSelection 		= document.getElementsByName('colorSelection');
			var len 		   		= colorSelection.length;
			var startDate	   		= document.getElementById('startDatePicker').value;
			var days		   		= document.getElementById('timeWindowSelect').value;
			
			for(var index = 0;index<len;index++) {
				if (colorSelection[index].checked) colorBy = colorSelection[index].value;	
			}
			
			var groupSelection = document.getElementsByName('viewSelection');
			var len 		   = groupSelection.length;
			
			for(var index = 0;index<len;index++) {
				if (groupSelection[index].checked) groupBy = groupSelection[index].value;	
			}
			
			window.location.href 	= updateAction  + '&groupby='+groupBy+'&colorby='+colorBy+'&startdate='+startDate+'&days='+days;
			

	});
}

//********************************************************************************************************************************************************************
//** FUNCTION CREATESHIFTMANAGEMENTCONTROLLERTIMEWINDOW(timeWindow,days)
//** Creates timeWindow selection.
//********************************************************************************************************************************************************************

function createShiftManagementControllerTimeWindow(timeWindow,days,updateAction) {
	
	var timeWindowSelect 	= document.createElement('select');
	timeWindowSelect.id		= 'timeWindowSelect';

	var option01			= document.createElement('option');
	option01.value			= '1';
	option01.text			= '1pv';
	
	var option02			= document.createElement('option');
	option02.value			= '2';
	option02.text			= '2pv';
	
	var option03			= document.createElement('option');
	option03.value			= '3';
	option03.text			= '3pv';
	
	var option04			= document.createElement('option');
	option04.value			= '4';
	option04.text			= '4pv';
	
	var option05			= document.createElement('option');
	option05.value			= '5';
	option05.text			= '5pv';
	
	var option06			= document.createElement('option');
	option06.value			= '6';
	option06.text			= '6pv';
	
	var option07			= document.createElement('option');
	option07.value			= '7';
	option07.text			= '7pv';
	
	var option10			= document.createElement('option');
	option10.value			= '10';
	option10.text			= '10pv';
	
	var option14			= document.createElement('option');
	option14.value			= '14';
	option14.text			= '14pv';
	
	timeWindowSelect.appendChild(option01);
	timeWindowSelect.appendChild(option02);
	timeWindowSelect.appendChild(option03);
	timeWindowSelect.appendChild(option04);
	timeWindowSelect.appendChild(option05);
	timeWindowSelect.appendChild(option06);
	timeWindowSelect.appendChild(option07);
	timeWindowSelect.appendChild(option10);
	timeWindowSelect.appendChild(option14);
	
	timeWindowSelect.value = days;
	
	$(timeWindowSelect).change(function() {
					
			var colorBy		   		= '';
			var groupBy				= '';
			var colorSelection 		= document.getElementsByName('colorSelection');
			var len 		   		= colorSelection.length;
			var startDate	   		= document.getElementById('startDatePicker').value;
			var days		   		= document.getElementById('timeWindowSelect').value;
			
			for(var index = 0;index<len;index++) {
				if (colorSelection[index].checked) colorBy = colorSelection[index].value;	
			}
			
			var groupSelection = document.getElementsByName('viewSelection');
			var len 		   = groupSelection.length;
			
			for(var index = 0;index<len;index++) {
				if (groupSelection[index].checked) groupBy = groupSelection[index].value;	
			}
			
			window.location.href 	= updateAction  + '&groupby='+groupBy+'&colorby='+colorBy+'&startdate='+startDate+'&days='+days;
	});
	
	timeWindow.appendChild(timeWindowSelect);
}

//********************************************************************************************************************************************************************
//** FUNCTION CREATESHIFTMANAGEMENTCONTROLLERHEADER(headerRow)
//** Creates header row.
//********************************************************************************************************************************************************************

function createShiftManagementControllerHeader(headerRow) {
	
	var dateHeader				= headerRow.insertCell();
	dateHeader.innerHTML 		= 'Päivämäärästä';

	var spanHeader				=  headerRow.insertCell();
	spanHeader.innerHTML 		= "Eteenpäin";
	
	//var viewTypeHeader			=  headerRow.insertCell();
	//viewTypeHeader.innerHTML	=  "Näkymä";
	
	var groupingHeader			= headerRow.insertCell();
	groupingHeader.innerHTML 	= "Jaottelu";

	var colorHeader				= headerRow.insertCell();
	colorHeader.innerHTML		= "Värikoodaus";
	
}

//********************************************************************************************************************************************************************
//** FUNCTION CREATERADIOBUTTONEVENTSTHREECHOICEGROUPING(project,task,employee,updateAction)
//** Creates events.
//********************************************************************************************************************************************************************

function createRadioButtonEventsThreeChoiceGrouping(project,task,employee,updateAction) {
	
	project.onclick = function() {
		
		var colorBy		   		= '';
		var colorSelection 		= document.getElementsByName('colorSelection');
		var len 		   		= colorSelection.length;
		var startDate	   		= document.getElementById('startDatePicker').value;
		var days		   		= document.getElementById('timeWindowSelect').value;
		
		for(var index = 0;index<len;index++) {
			console.log('ColorBy ' + colorSelection[index].value + "->" + colorSelection[index].checked);
			if (colorSelection[index].checked) colorBy = colorSelection[index].value;	
		}
			
		project.className 		= "projectLabelViewPressed";
		task.className	  		= "taskLabelView";
		employee.className 		= "employeeLabelView";
		window.location.href 	= updateAction  + '&groupby=project&colorby='+colorBy+'&startdate='+startDate+'&days='+days;
		
	}
	
	task.onclick = function() {
		
		var colorBy		   		= '';
		var colorSelection 		= document.getElementsByName('colorSelection');
		var len 		   		= colorSelection.length;
		var startDate	   		= document.getElementById('startDatePicker').value;
		var days		   		= document.getElementById('timeWindowSelect').value;
	
		
		for(var index = 0;index<len;index++) {
			console.log('ColorBy ' + colorSelection[index].value + "->" + colorSelection[index].checked);
			if (colorSelection[index].checked) colorBy = colorSelection[index].value;	
		}
		
		project.className 		= "projectLabelView";
		task.className	  		= "taskLabelViewPressed";
		employee.className 		= "employeeLabelView";
		window.location.href 	= updateAction + '&groupby=task&colorby='+colorBy+'&startdate='+startDate+'&days='+days;
		
	}

	employee.onclick = function() {
		
		var colorBy		   		= '';
		var colorSelection 		= document.getElementsByName('colorSelection');
		var len 		   		= colorSelection.length;
		var startDate	   		= document.getElementById('startDatePicker').value;
		var days		   		= document.getElementById('timeWindowSelect').value;
	
		for(var index = 0;index<len;index++) {
			console.log('ColorBy ' + colorSelection[index].value + "->" + colorSelection[index].checked);
			if (colorSelection[index].checked) colorBy = colorSelection[index].value;	
		}
	
		project.className 		= "projectLabelView";
		task.className	  		= "taskLabelView";
		employee.className 		= "employeeLabelViewPressed";
		window.location.href 	= updateAction  + '&groupby=employee&colorby='+colorBy+'&startdate='+startDate+'&days='+days;
		
	}
	
}

//********************************************************************************************************************************************************************
//** FUNCTION CREATERADIOBUTTONEVENTSTHREECHOICECOLORING(project,task,employee,updateAction)
//** Creates events.
//********************************************************************************************************************************************************************

function createRadioButtonEventsThreeChoiceColoring(project,task,employee,updateAction) {
	
	project.onclick = function() {
	
		var groupBy		   = '';
		var groupSelection = document.getElementsByName('viewSelection');
		var len 		   = groupSelection.length;
		var startDate	   = document.getElementById('startDatePicker').value;
		var days		   = document.getElementById('timeWindowSelect').value;
		
		for(var index = 0;index<len;index++) {
			console.log('GroupBy ' + groupSelection[index].value + "->" + groupSelection[index].checked);
			if (groupSelection[index].checked) groupBy = groupSelection[index].value;	
		}
			
		project.className 	= "projectLabelViewPressed";
		task.className	  	= "taskLabelView";
		employee.className 	= "employeeLabelView";
		window.location.href = updateAction  + '&groupby=' + groupBy + '&colorby=project'+'&startdate='+startDate+'&days='+days;
		
	}
	
	task.onclick = function() {
		
		var groupBy		   = '';
		var groupSelection = document.getElementsByName('viewSelection');
		var len 		   = groupSelection.length;
		var startDate	   = document.getElementById('startDatePicker').value;
		var days		   = document.getElementById('timeWindowSelect').value;
	
		for(var index = 0;index<len;index++) {		
			console.log('GroupBy ' + groupSelection[index].value + "->" + groupSelection[index].checked);
			if (groupSelection[index].checked) groupBy = groupSelection[index].value;
		}
		
		
		project.className 	= "projectLabelView";
		task.className	  	= "taskLabelViewPressed";
		employee.className 	= "employeeLabelView";
		window.location.href = updateAction + '&groupby=' + groupBy + '&colorby=task'+'&startdate='+startDate+'&days='+days;
		
	}

	employee.onclick = function() {
	
		var groupBy		   = '';
		var groupSelection = document.getElementsByName('viewSelection');
		var len 		   = groupSelection.length;
		var startDate	   = document.getElementById('startDatePicker').value;
		var days		   = document.getElementById('timeWindowSelect').value;
		
		for(var index = 0;index<len;index++) {
			console.log('GroupBy ' + groupSelection[index].value + "->" + groupSelection[index].checked);
			if (groupSelection[index].checked) groupBy = groupSelection[index].value;	
		}
			
		project.className 	= "projectLabelView";
		task.className	  	= "taskLabelView";
		employee.className 	= "employeeLabelViewPressed";
		window.location.href = updateAction  + '&groupby=' + groupBy + '&colorby=employee'+'&startdate='+startDate+'&days='+days;
		
	}
	
}

//********************************************************************************************************************************************************************
//** FUNCTION CREATERADIOBUTTONEVENTSTWOCHOICE(horizontal,vertical)
//** Creates events.
//********************************************************************************************************************************************************************

function createRadioButtonEventsTwoChoice(horizontal,vertical) {
	
	horizontal.onclick = function() {
		
		horizontal.className 	= "horizontalLabelViewPressed";
		vertical.className	  	= "verticalLabelView";
		
	}
	
	vertical.onclick = function() {
		
		horizontal.className 	= "horizontalLabelView";
		vertical.className	  	= "verticalLabelViewPressed";
		
	}

}

//********************************************************************************************************************************************************************
//** FUNCTION FINDMONDAY(DATE);
//** Finds monday.
//********************************************************************************************************************************************************************

function findDayByNumber(date,findDay) {
	
	var jsdate	  = new Date();
	var dayNumber = jsdate.getDay();
	
	while(dayNumber!=findDay) {
		
		jsdate.setDate(jsdate.getDate() + -1);
		
		console.log(jsdate);
		
		dayNumber = jsdate.getDay();
		
	}
	
	var retval = jsdate.getFullYear()+'-'+addZero(jsdate.getMonth()+1)+'-'+addZero(jsdate.getDate());
	
	return retval;
}