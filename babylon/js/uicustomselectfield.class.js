//**********************************************************************************************************************
//**
//**
//**********************************************************************************************************************

function createAncestorAgrementItems(selectID) {
	
	var select 		= document.getElementById(selectID);
	var dataRows	= select.selection;
	
	console.log('Creating ancestors from ' + dataRows);
	
	var empty = document.createElement('option');
	empty.value = 0;
	empty.text  = 'Ei valittu';
	select.add(empty);
		
	for(var index in dataRows) {
		
		var dataRow = dataRows[index];
		var option = document.createElement('option');
		option.value = dataRow['labouragreementID'];
		option.text  = dataRow['name'];
	
		select.add(option);
		
		console.log(dataRow);
		
	}

}

//**********************************************************************************************************************

function createPreSelectedTasks(selectID) {
	
	var select 		= document.getElementById(selectID);
	var dataRows	= select.selection;
	
	var empty = document.createElement('option');
	empty.value = 0;
	empty.text  = 'Ei valittu';
	select.add(empty);
		
	for(var index in dataRows) {
		
		var dataRow = dataRows[index];
		var option = document.createElement('option');
		option.value = dataRow['taskID'];
		option.text  = dataRow['name'];
	
		select.add(option);
		
		console.log(dataRow);
		
	}
	
	select.value = select.customParameters;

}


//**********************************************************************************************************************

function updateVoimassaSelect(selectID) {
	
	console.log(selectID);
	
	var select = document.getElementById(selectID);
	var value  = select.value;
	
	var div    = document.getElementById('palkanosatDiv');
	$(div).empty();

	updateVoimassaSelectOptions(value);
	
}

//**********************************************************************************************************************

function updateVoimassaSelectOptions(value) {
	
	var select 		= document.getElementById('voimassaSelect');
	var dataRows 	= select.selection;
	
	console.log('Searchig with ' + value);
	
	$(select).empty();
	
	var empty = document.createElement('option');
	empty.value = 0;
	empty.text  = 'Ei valittu';
	select.add(empty);
	
	for(var index in dataRows) {
		
		var dataRow = dataRows[index];
		
		if (dataRow['labouragreementID']==value) {
			
			var option = document.createElement('option');
			option.value = dataRow['labouragreementperiodID'];
			option.text  = dataRow['startdate'] + '-' + dataRow['enddate'];
			select.add(option);
			
		}
	
	}
	
}

//**********************************************************************************************************************

function updatePalkanOsat(SelectID) {
	
	var select  = document.getElementById(SelectID);
	var value   = select.value;
	
	createPalkanOsat(value);
	
}

//**********************************************************************************************************************

function emptyCreation(selectID) {
	
	console.log('Empty creation');
	
}

//**********************************************************************************************************************

function createPalkanOsat(value) {
	
	var div 	  = document.getElementById('palkanosatDiv');
	var dataRows  = div.selection;

	$(div).empty();

	var table = document.createElement('table');
	
	for(var index in dataRows) {
		
		var dataRow = dataRows[index];

		if (dataRow['labouragreementperiodID']==value) {
		
		if (dataRow['parttype']!=3) createNonSelectionItem(table,dataRow);
		if (dataRow['parttype']==3) createSubSelectionItems(table,dataRow);
		
		}
	}
		
	div.appendChild(table);

}

//**********************************************************************************************************************

function createNonSelectionItem(table,dataRow) {
	
	var row 				= table.insertRow();
	
	var label	 			= document.createElement('label');
	var newInput 			= document.createElement('input');
	
	newInput.dataID 		= dataRow['salaryitemID'];
	newInput.name			= 'salaryItem';
	newInput.value			= dataRow['value'];
	newInput.style.maxWidth = '115px';
	
	if (dataRow['parttype']==2) newInput.disabled = true;
	
	label.innerHTML = dataRow['name'];
	
	var labelCell 		= row.insertCell(-1);
	var inputCell 		= row.insertCell(-1);
	var valueUnitCell 	= row.insertCell(-1);

	labelCell.appendChild(label);
	inputCell.appendChild(newInput);
	valueUnitCell.innerHTML = dataRow['valueUnit'];
	
}
	
//**********************************************************************************************************************

function createSubSelectionItems(table,dataRow) {
	
	var div 			= document.getElementById('palkanosatDiv');
	var subSelection 	= div.subSelection;
	
	console.log('Creating from ' + subSelection);
	
	var select 				= document.createElement('select');
	
	select.dataID  			= '6';
	select.name	   			= 'salaryItem';
	select.style.maxWidth 	= '125px';
	
	for(var index in subSelection) {
	
		var item = subSelection[index];
		
		if (dataRow['salaryitemID']==item['salaryitemID']) {
			
			var option   = document.createElement('option');
			option.value = item['salaryitemselectID'];
			option.text  = item['name'];
			
			select.add(option);
			
		}
		
	}
	
	var row 				= table.insertRow();
	var label		 		= document.createElement('label');
	label.innerHTML			= "Palkka";

	var labelCell			= row.insertCell(-1);
	var selectCell			= row.insertCell(-1);
	
	labelCell.appendChild(label);
	selectCell.appendChild(select);
	
	
}

//**********************************************************************************************************************

function saveContent(url,redirect) {
	
	var labouragreementID = document.getElementById('sopimusSelect').value;
	var labouragreementperiodID = document.getElementById('voimassaSelect').value;
	var workassignmentID		= document.getElementById('nimikeSelect').value;
	
	var wageParts		  = document.getElementsByName('salaryItem');
	var length			  = wageParts.length;
	
	console.log('len' + wageParts.length);
	
	var valueString = ""; 
	
	for(var index=0;index<length;index++) {
		
		var param = wageParts[index].dataID;
		var value = wageParts[index].value;
		
		valueString = valueString + param + ':' + value + ',';
		
	}
	
	valueString = valueString.slice(0,-1);
	
	var paramString = "&labouragreementID=" + labouragreementID;
	paramString 	+="&labouragreementperiodID=" + labouragreementperiodID;
	paramString		+="&workassignmentID=" + workassignmentID;
	paramString 	+="&values=" + valueString;
	
	console.log('Inserting with ' + url + 'parameters = ' + paramString);
	
	$.getJSON(url, paramString, function() {																			
	})																														
	.done(function(reply) {																									
		console.log(reply[0]);
		console.log('Done inserting');
		console.log(redirect);
		window.location = redirect;
	});																														
	
	
}

//**********************************************************************************************************************
//**
//**
//**********************************************************************************************************************

function createPreSelectedAgreementSelection(selectID) {
	
	createAncestorAgrementItems(selectID);
	
	var select 		= document.getElementById(selectID);
	select.value 	= select.customParameters;
	
} 

//**********************************************************************************************************************

function setPreSelectedPeriod(selectID) {
	
	var select = document.getElementById(selectID);
	var value  = select.customParameters;
	
	updateVoimassaPreSelectOptions(value);

	select.value = value;

}

//**********************************************************************************************************************
 
function updateVoimassaPreSelectOptions(value) {
	
	var select 		= document.getElementById('voimassaPreSelect');
	var dataRows 	= select.selection;
	
	console.log('Searchig with ' + value);
	
	$(select).empty();
	
	var empty = document.createElement('option');
	empty.value = 0;
	empty.text  = 'Ei valittu';
	select.add(empty);
	
	for(var index in dataRows) {
		
		var dataRow = dataRows[index];
		
		if (dataRow['labouragreementID']==value) {
			
			var option = document.createElement('option');
			option.value = dataRow['labouragreementperiodID'];
			option.text  = dataRow['startdate'] + '-' + dataRow['enddate'];
			select.add(option);
			
		}
	
	}
	
}

//**********************************************************************************************************************

function updateVoimassaPreSelect(selectID) {
	
	console.log(selectID);
	
	var select = document.getElementById(selectID);
	var value  = select.value;
	
	var div    = document.getElementById('palkanosatDiv');
	$(div).empty();

	updateVoimassaPreSelectOptions(value);
	
}

//**********************************************************************************************************************

function createPrePalkanOsat(value) {
	
	var div 	  = document.getElementById('palkanosatDiv');
	var dataRows  = div.selection;

	$(div).empty();

	var table = document.createElement('table');
	
	for(var index in dataRows) {
		
		var dataRow = dataRows[index];

		if (dataRow['labouragreementperiodID']==value) {
		
		if (dataRow['parttype']!=3) createNonPreSelectionItem(table,dataRow);
		if (dataRow['parttype']==3) createSubPreSelectionItems(table,dataRow);
		
		}
	}
		
	div.appendChild(table);

}

//**********************************************************************************************************************

function createNonPreSelectionItem(table,dataRow) {
	
	var row 				= table.insertRow();
	
	var label	 			= document.createElement('label');
	var newInput 			= document.createElement('input');
	
	newInput.dataID 		= dataRow['salaryitemID'];
	newInput.name			= 'salaryItem';
	newInput.style.maxWidth = '115px';
	
	if (dataRow['parttype']==2) newInput.disabled = true;
	
	label.innerHTML = dataRow['name'];
	
	var labelCell 		= row.insertCell(-1);
	var inputCell 		= row.insertCell(-1);
	var valueUnitCell 	= row.insertCell(-1);

	labelCell.appendChild(label);
	inputCell.appendChild(newInput);
			
	var parameters = document.getElementById('palkanosatDiv').customParameters;
	var values	   = parameters.split(",");
		
	for(var index in values) {
			
		console.log('Paramval' + values[index]);
			
		var pair = values[index].split(":");
			
		if (dataRow['salaryitemID']==pair[0]) newInput.value = pair[1];
			
	}
	
	valueUnitCell.innerHTML = dataRow['valueUnit'];
		
}

//**********************************************************************************************************************
	
function createSubPreSelectionItems(table,dataRow) {
	
	var div 			= document.getElementById('palkanosatDiv');
	var subSelection 	= div.subSelection;
	
	console.log('Creating from ' + subSelection);
	
	var select 				= document.createElement('select');
	
	select.dataID  			= '6';
	select.name	   			= 'salaryItem';
	//select.style.maxWidth 	= '125px';
	
	for(var index in subSelection) {
	
		var item = subSelection[index];
		
		if (dataRow['salaryitemID']==item['salaryitemID']) {
			
			var option   = document.createElement('option');
			option.value = item['salaryitemselectID'];
			option.text  = item['name'] + ' - ' + item['value'] + ' ' + item['valueUnit'];
			
			select.add(option);
			
		}
	}
	
	var row 				= table.insertRow();
	var label		 		= document.createElement('label');
	label.innerHTML			= "Palkka";

	var labelCell			= row.insertCell(-1);
	var selectCell			= row.insertCell(-1);
	selectCell.colSpan		= "2";
	
	labelCell.appendChild(label);
	selectCell.appendChild(select);
	
	var parameters = document.getElementById('palkanosatDiv').customParameters;
	var values	   = parameters.split(",");
		
	for(var index in values) {
			
		console.log('Paramval' + values[index]);
			
		var pair = values[index].split(":");
			
		if (dataRow['salaryitemID']==pair[0]) select.value = pair[1];
			
	}
}

//**********************************************************************************************************************

function updateContent(url,redirect) {
	
	var labouragreementID 		= document.getElementById('sopimusPreSelect').value;
	var labouragreementperiodID = document.getElementById('voimassaPreSelect').value;
	var workassignmentID		= document.getElementById('nimikePreSelect').value;
	
	var wageParts		  = document.getElementsByName('salaryItem');
	var length			  = wageParts.length;
	
	console.log('len' + wageParts.length);
	
	var valueString = ""; 
	
	for(var index=0;index<length;index++) {
		
		var param = wageParts[index].dataID;
		var value = wageParts[index].value;
		
		valueString = valueString + param + ':' + value + ',';
		
	}
	
	valueString = valueString.slice(0,-1);
	
	var paramString = "&labouragreementID=" + labouragreementID;
	paramString 	+="&labouragreementperiodID=" + labouragreementperiodID;
	paramString		+="&workassignmentID=" + workassignmentID;
	paramString 	+="&values=" + valueString;
	
	console.log('Updating ' + url +  ' with parameters = ' + paramString);
	
	$.getJSON(url, paramString, function() {																			
	})																														
	.done(function(reply) {																									
		console.log(reply[0]);
		console.log('Done updating.');
		console.log(redirect);
		window.location = redirect;
	});																														
	
	
}

