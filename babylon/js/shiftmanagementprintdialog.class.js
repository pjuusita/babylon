//***********************************************************************************************************************************************************
//**
//**
//***********************************************************************************************************************************************************

function createShiftManagementPrintDialog(parentElement,updateAction,printAction,employees,tasks,projects,startDate) {
	
	var dialogDiv = document.createElement('div');
	
	dialogDiv.employees 	= employees;
	dialogDiv.taks			= tasks;
	dialogDiv.projects  	= projects;
	
	grouping 				= [];
	
	grouping['project'] 	= 'Projektit';
	grouping['task'] 		= 'Työtehtävät';
	grouping['employee'] 	= 'Työntekijät';
	
	createShiftManagementPrintDialogInner(dialogDiv,grouping,employees,tasks,projects,startDate);
	
	$(dialogDiv).dialog({
		
		title 		: "Tulostus",
		width 		: 500,
		height 		: 300,
		autoOpen 	: false,
		
		buttons: [
		           {
		             text: "Tulosta",
		             
		             click: function() {
		            	 
		               $( this ).dialog( "close" );
		               
		               var startDate	= document.getElementById('shiftManagementControlStartDate').value;
		               var endDate 		= document.getElementById('shiftManagementControlEndDate').value;
		               var group		= document.getElementById('groupSelection').value;
		               var subject		= document.getElementById('subjectSelection').value;
		                 
		               $urlString = printAction + "&startdate=" + startDate + "&enddate=" + endDate + "&subject=" + subject + "&group="+group;
		               window.open($urlString);
		              
		             }
		           
		           },{
		        	 text : "Peruuta",
		        	 
		        	 click : function() {
		        		 
		        		 $( this ).dialog( "close" );
		        	 }   
		           }
		         ]

		
	});
	
	return dialogDiv;
}

function createShiftManagementPrintDialogInner(dialogDiv,grouping,employees,tasks,projects,startDate) {
	
	var innerTable = document.createElement('table');
	
	var groupingSelect = createShiftManagementPrintDialogGroupingRow(innerTable,grouping);
	var subjectSelect  = createShiftManagementPrintDialogSubjectRow(innerTable);
	createShiftManagementPrintDialogDatePickers(innerTable,startDate);
	
	createGroupingEvents(groupingSelect,subjectSelect,employees,projects,tasks);
	
	createSubjectOptions(subjectSelect,projects,'projects'); 
	
	dialogDiv.appendChild(innerTable);
	
}

function createShiftManagementPrintDialogGroupingRow(innerTable,grouping) {
	
	var groupingRow = innerTable.insertRow();
	
	var cell		= groupingRow.insertCell();
	cell.innerHTML  = "Jaottelu";
	
	cell			= groupingRow.insertCell();
	
	var select		= document.createElement('select');
	select.id		= 'groupSelection';
	
	createOptions(select,grouping);
		
	cell.appendChild(select);
	
	return select;
}


function createGroupingEvents(groupingSelect,subjectSelect,employees,projects,tasks) {
	
	$(groupingSelect).change(function() {
		
		if ($(groupingSelect).val() == 'employee') 	createSubjectOptions(subjectSelect,employees,'employees');
		if ($(groupingSelect).val() == 'task') 		createSubjectOptions(subjectSelect,tasks,'tasks');
		if ($(groupingSelect).val() == 'project') 	createSubjectOptions(subjectSelect,projects,'projects');
		
	}); 
	
}

function createSubjectOptions(select,data,dataType) {
	
	$(select).empty();
	
	var option			= document.createElement('option');
	option.value		= -1;
	option.text			= "Kaikki";
	
	select.appendChild(option);
	
	for(var index in data) {
		
		option 		= document.createElement('option');
		
		var dataRow 	= data[index];
		
		if (dataType=='employees') 	option.value	= dataRow['employeeID'];
		if (dataType=='tasks') 		option.value	= dataRow['taskID'];
		if (dataType=='projects') 	option.value	= dataRow['projectID'];

		option.text		= dataRow['name'];
		
		select.appendChild(option);
		
	}
	
	
}

function createShiftManagementPrintDialogSubjectRow(innerTable) {
	
	var subjectRow = innerTable.insertRow();
	
	var cell		= subjectRow.insertCell();
	cell.innerHTML  = "Kohde";
	
	cell			= subjectRow.insertCell();
	var select		= document.createElement('select');
	select.id		= 'subjectSelection';
		
	cell.appendChild(select);
	
	return select;
	
}

function createShiftManagementPrintDialogDatePickers(innerTable,startDate) {

	var startDateInput 		= document.createElement('input');
	var endDateInput	  	= document.createElement('input');
	
	startDateInput.id		= "shiftManagementControlStartDate";
	endDateInput.id			= "shiftManagementControlEndDate";
	
	
	createShiftManagementPrintDialogDatePicker(startDateInput,startDate);
	createShiftManagementPrintDialogDatePicker(endDateInput,startDate);
	
	var startDateRow	 = innerTable.insertRow();
	var cell	 		 = startDateRow.insertCell();
	cell.innerHTML		 = "Päivämäärästä";
	cell				 = startDateRow.insertCell();
	
	cell.appendChild(startDateInput);
	
	var endDateRow		 = innerTable.insertRow();
	var cell	 		 = endDateRow.insertCell();
	cell.innerHTML		 = "Päivämäärään";
	cell				 = endDateRow.insertCell();
	
	cell.appendChild(endDateInput);
	
}

function createShiftManagementPrintDialogDatePicker(input,date) {
	
	$(input).datepicker();
	$(input).datepicker('setDate', new Date());
	$(input).datepicker('option','dateFormat','yy-mm-dd');
	
	input.value			= date;

}

function createOptions(select,options) {
	
	for(var index in options) {
		
		var option 	 = document.createElement('option');
		option.text	 = options[index];
		option.value = index;
		
		select.appendChild(option);
		
	}
}

