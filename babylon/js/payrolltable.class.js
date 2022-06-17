//*******************************************************************************************
//** FUNCTION EMPTYFUNCTION(ELEMENT)
//** Testifunktio.
//*******************************************************************************************

function emptyFunction(element) {
	
	console.log("Visited emptyFunction from " + element);
	
}

//*******************************************************************************************
//** FUNCTION TOFIXED2()
//** Muuttaa floatit kahden desimaalin tarkkuuteen.
//*******************************************************************************************

function toFixed2() {
	
	var table = document.getElementById('sumrow').parentNode;
	var rows  = table.rows.length;
	
	for(var index=1;index<rows-2;index++) {
		
		var row = table.rows[index];
		
		row.childNodes[1].childNodes[0].value = parseFloat(row.childNodes[1].childNodes[0].value).toFixed(2);
		row.childNodes[2].childNodes[0].value = parseFloat(row.childNodes[2].childNodes[0].value).toFixed(2);
		row.childNodes[3].childNodes[0].value = parseFloat(row.childNodes[3].childNodes[0].value).toFixed(2);
			
	}	
}

//*******************************************************************************************
//** FUNCTION COUNTTOTAL(ELEMENT)
//** Laskee summan ilman päivitystä tietokantaan. Hylätty, ei käytetetä. Käytettiin 
//** OnBlurin testaamiseen.
//*******************************************************************************************

function countTotal(element) {
	
	var table	 = element.parentNode.parentNode.parentNode;
	var tableRow = element.parentNode.parentNode;
	
	var amount	= tableRow.childNodes[1].childNodes[0];
	var perUnit = tableRow.childNodes[2].childNodes[0];
	var total	= tableRow.childNodes[3].childNodes[0];

	var totalCount = parseFloat(amount.value) * parseFloat(perUnit.value);
	
	total.value	   = totalCount.toFixed(2);
		
	var sumRow 	= document.getElementById('sumrow');
	var totalSum = sumRow.childNodes[3];
	
	var sum = 0;
	var len = sumRow.rowIndex;

	console.log('Sumrow index' + len);
	
	for(var index=1;index<len;index++) {
		
		var value = table.rows[index].childNodes[3].childNodes[0].value;
		console.log(value);
		sum = sum + parseFloat(value);
		
	}
	
	totalSum.innerHTML = sum.toFixed(2) + " Eur";
 	
}

//*******************************************************************************************
//** FUNCTION COUNTTOTALANDUPDATE(ELEMENT,UPDATEROWACTION)
//** Laskee summan ja päivittää tietokannan.
//*******************************************************************************************

function countTotalAndUpdate(element,updateRowAction) {

	var table	 = element.parentNode.parentNode.parentNode;
	var tableRow = element.parentNode.parentNode;
	
	var amount	= tableRow.childNodes[1].childNodes[0];
	var perUnit = tableRow.childNodes[2].childNodes[0];
	var total	= tableRow.childNodes[3].childNodes[0];

	var totalCount = parseFloat(amount.value) * parseFloat(perUnit.value);
	
	total.value	   = totalCount.toFixed(2);
	
	updateJSRow(tableRow,updateRowAction);
	
	var sumRow 	= document.getElementById('sumrow');
	var totalSum = sumRow.childNodes[3];
	
	var sum = 0;
	var len = sumRow.rowIndex;

	console.log('Sumrow index' + len);
	
	for(var index=1;index<len;index++) {
		
		var value = table.rows[index].childNodes[3].childNodes[0].value;
		console.log(value);
		sum = sum + parseFloat(value);
		
	}
	
	totalSum.innerHTML = sum.toFixed(2) + " Eur";
 	
}

//*******************************************************************************************
//** FUNCTION COUNTINITIALTOTALS()
//** Laskee summan. Kutsutaan document.readyssä.
//*******************************************************************************************

function countInitialTotals() {
	
	var sumRow 	 = document.getElementById('sumrow');
	var table	 = sumRow.parentNode;
	var totalSum = sumRow.childNodes[3];
	
	var sum = 0;
	var len = sumRow.rowIndex;

	console.log('Sumrow index' + len);
	
	for(var index=1;index<len;index++) {
		
		var value = table.rows[index].childNodes[3].childNodes[0].value;
		console.log(value);
		sum = sum + parseFloat(value);
		
	}
	
	totalSum.innerHTML = sum.toFixed(2) + " Eur";
	
}

//*******************************************************************************************
//** FUNCTION ADDROW(ELEMENT,ADDROWACTION)
//** Kloonaa rivin ja varaa riville tietokantarivin.
//*******************************************************************************************

function addRow(element,addrowAction) {
	
	var tableRow = element.parentNode.parentNode;
	var cloneRow = tableRow.cloneNode(true);
	
	console.log(tableRow);
	console.log(cloneRow);
	
	$(cloneRow).insertAfter(tableRow);
	
	reserveRow(cloneRow,addrowAction);
	
}

//*******************************************************************************************
//** FUNCTION ADDEMPTYROW(ELEMENT,OPTIONS,ADDEMPTYROWACTION...)
//** Luo uuden rivin tyhjästä. Tarpeellinen, koska on mahdollista, että kloonattavia rivejä
//** ei ole.
//*******************************************************************************************

function addEmptyRow(element,options,addEmptyRowAction,addRowAction,removeRowAction,updateRowAction) {
	
	console.log('Adding empty row');
	
	var table 		   = element.parentNode.parentNode.parentNode;
	var buttonRow 	   = element.parentNode.parentNode;
	var buttonRowIndex = buttonRow.rowIndex;
	var sumRow		   = table.childNodes[buttonRowIndex-1];
	var row	  		   = document.createElement("tr");
	
	$(row).insertBefore(sumRow);
	
	options 		= options.slice(0,-1);
	options 		= options.split(',');
	var len 		= options.length;
	
	var selectCell 	= row.insertCell(-1);
	
	var select 		= document.createElement('select');
	select.onchange = function() {
		updateJSRow(row,updateRowAction);
	}
	
	for(var n=0;n<len;n++) {
		
		var values	= options[n];
		values	  	= values.split(":");
		
		var option 	= document.createElement('option');
		option.value= values[0];
		option.text	= values[1];
		
		select.appendChild(option);
		
	}
	
	selectCell.appendChild(select);
	
	var amountCell 		= row.insertCell(-1);
	var amountInput 	= document.createElement('input');
	amountCell.appendChild(amountInput);
	amountInput.onkeyup = function() {
		countTotalAndUpdate(amountInput,updateRowAction);
	}
	
	var perunitCell 	 = row.insertCell(-1);
	var perunitInput 	 = document.createElement('input');
	perunitCell.appendChild(perunitInput);
	perunitInput.onkeyup = function() {
		countTotalAndUpdate(perunitInput,updateRowAction);
	}
	
	var totalCell		 = row.insertCell(-1);
	var totalInput		 = document.createElement('input');
	totalCell.appendChild(totalInput);
	totalInput.onkeyup	 = function() {
		countTotalAndUpdate(totalInput,updateRowAction);
	}
	
	var addCell			 = row.insertCell(-1);
	var addButton		 = document.createElement('button');
	addButton.innerHTML		 = 'Lisää';
	addButton.onclick	 = function() {
		addRow(addButton,addRowAction);
	}
	
	addCell.appendChild(addButton);
	
	
	var removeCell		 = row.insertCell(-1);
	var removeButton	 = document.createElement('button');
	removeButton.innerHTML	 = 'Poista';
	removeButton.onclick = function() {
		removeRow(removeButton,removeRowAction);
	}
	removeCell.appendChild(removeButton);
	
	reserveRow(row,addRowAction);
	
}

//*******************************************************************************************
//** FUNCTION UPDATEJSROW(TABLEROW,UPDATEROWACTION)
//** Rivin päivitys tietokantaan.
//*******************************************************************************************

function updateJSRow(tableRow,updateRowAction) {
	
	var salary  = tableRow.childNodes[0].childNodes[0];
	var amount	= tableRow.childNodes[1].childNodes[0];
	var perUnit = tableRow.childNodes[2].childNodes[0];
	var total	= tableRow.childNodes[3].childNodes[0];
	var rowID	= tableRow.id;
	
	var parameters = "&salary=" + salary.value + "&amount=" + amount.value + "&perunit=" + perUnit.value + "&total=" + total.value + "&rowid=" + rowID;
	
	console.log("Updating row with" + updateRowAction + parameters);
	
	$.getJSON(updateRowAction, parameters, function() {
		
	}).done(function(reply) {
				
		console.log('Done updating.');
		
	});	
	
}

//*******************************************************************************************
//** FUNCTION RESERVEROW(ROW,ADDROWACTION)
//** Varaa riville tietokanta rivin.
//*******************************************************************************************

function reserveRow(row,addRowAction) {
	
	var parameters = "";
	
	console.log("Reserving row.");
	
	$.getJSON(addRowAction, parameters, function() {
		
	}).done(function(reply) {
		
		var amount		= row.childNodes[1].childNodes[0];
		var perUnit 	= row.childNodes[2].childNodes[0];
		var total		= row.childNodes[3].childNodes[0];
		
		amount.value 	= '';
		perUnit.value 	= '';
		total.value		= '';
		
		row.id			= reply[0].rowID;
			
	});	
	
}

//*******************************************************************************************
//** FUNCTION REMOVEROW(ELEMENT,REMOVEROWACTION)
//** Poistaa rivin tablesta ja tietokannasta.
//*******************************************************************************************

function removeRow(element,removeRowAction) {

	var tableRow   = element.parentNode.parentNode;
	
	var parameters = "&id="+tableRow.id;
	
	console.log("Removing row with id"+tableRow.id);
	console.log(removeRowAction + parameters);

	$.getJSON(removeRowAction,parameters,function() {
		
	}).done(function(reply) {
		
		console.log("Removed row, removing associated elements");
		$(tableRow).remove();
		countInitialTotals();
	});
	
}