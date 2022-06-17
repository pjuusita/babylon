<?php

echo "<a href='".getUrl('accounting/purchases/showpurchases')."'>Palaa tositelistalle</a><br>";
echo "<br>";
//echo "<h1>" . $registry->company->name . "</h1>";



$section = new UISection("Ostolaskun tiedot", "700px");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'accounting/purchases/updatepurchase', 'purchaseID');


if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$field = new UISelectField($dimension->name,"dimension" . $dimension->dimensionID,"dimension" . $dimension->dimensionID,$registry->dimensionvalues[$dimension->dimensionID], "name");
		$section->addField($field);
	}
}


$field = new UISelectField("Toimittaja","supplierID","supplierID",$registry->suppliers, "name");
$section->addField($field);

$field = new UISelectField("Ostotapa","paymentmethodID","paymentmethodID", $registry->paymentmethods, 'name');
$section->addField($field);

// TODO: alv käsittelyä ei toistaiseksi ole toteutettu, tarvitaan...
//$field = new UISelectField("ALV-käsittely","vatcodeID","vatcodeID", $registry->vatreportcodes, 'name');
//$section->addField($field);


//$field = new UITextField("Ostotapa2", "purchasetype", 'purchasetype');
//$section->addField($field);
//echo "<br>Paymenttype - " . $registry->purchase->paymenttype;
//echo "<br>State2 - " . Collections::PAYMENTSTATUS_OPEN;


//if ($registry->purchase->state == Collections::PAYMENTSTATUS_OPEN) {
//	$field = new UISelectField("Maksutapa","paymenttype","paymenttype", $registry->paymenttypes);
//	$section->addField($field);
//}


$field = new UIDateField("Ostopäivä", "purchasedate", 'purchasedate');
$section->addField($field);

$field = new UIDateField("Eräpäivä", "duedate", 'duedate');
$section->addField($field);

$field = new UITextField("Viitenumero", "referencenumber", 'referencenumber');
$section->addField($field);


$field = new UIFileField("Liite","purchaseID","accounting/purchases/upload", "files", "accounting/purchases/download");
$field->setRemoveAction("accounting/purchases/removeattachment&purchaseID=" . $registry->purchase->purchaseID);
$section->addField($field);


//$field = new UITextField("Netto", "netamount", 'netamount');
//$section->addField($field);

//if ($registry->purchase->state > 0) {
	$field = new UITextField("Tositenumero", "receiptnumber", 'receiptnumber');
	$section->addField($field);
//}


//$field = new UISelectField("Ostovelkatili","payableaccountID","payableaccountID", $registry->accounts, 'fullname');
//$field->setPredictable(true);
//$section->addField($field);

if (($registry->purchase->payableaccountID == null) || ($registry->purchase->payableaccountID == 0)) {
	$field = new UIFixedTextField("Ostovelkatili", null);
	$section->addField($field);
} else {
	$field = new UIFixedTextField("Ostovelkatili", $registry->accounts[$registry->purchase->payableaccountID]->fullname);
	$section->addField($field);
}


// TODO: ei oikein muistikuvaa mihin tätä tarvitaan, payablecostpoolID voitaneen poistaa
//$field = new UISelectField("Ostokustannuspaikka","payablecostpoolID","payablecostpoolID", $registry->costpools, "name");
//$section->addField($field);


//$registry->purchase->amountstr = number_format($registry->purchase->grossamount, 2, ',', '') . " €";
$field = new UITextField("Brutto", "grossamount", 'grossamount');
$field->setFormat(Column::COLUMNTYPE_FLOAT);
$field->setUnit("€");
$section->addField($field);


$field = new UIFixedTextField("Netto", number_format($registry->purchase->netamount, 2, ',', '') . " €");
$section->addField($field);

//$field = new UITextField("Netamount", "netamount", 'netamount');
//$field->setUnit("€");
//$section->addField($field);

$field = new UIFixedTextField("ALV", number_format($registry->purchase->vatamount, 2, ',', '') . " €");
$section->addField($field);

//$field = new UITextField("Vatamount", "vatamount", 'vatamount');
//$field->setUnit("€");
//$section->addField($field);

//$field = new UITextField("StatementrowID", "statementrowID", 'statementrowID');
//$section->addField($field);


$field = new UITextAreaField("Huomautus", "note", 'note');
$section->addField($field);

// TODO: Tämä on ehkä tarpeeton, myöhemmin tämä voitaisiin siirtää tositenumeron alle
$field = new UIFixedTextField("ReceiptID", $registry->purchase->receiptID, getUrl("accounting/receipts/showreceipt") . "&id=" .  $registry->purchase->receiptID);
$section->addField($field);

$section->setData($registry->purchase);
$section->show();







$sum = 0;
foreach($registry->purchaserows as $index => $row) {
	$sum = $sum + $row->grossamount;
}
//echo "<br>Sum - " . $sum;

$delta = $registry->purchase->grossamount - $sum;


//echo "<br>costpools count - " . count($registry->costpools);





$insertinvoicerow = new UISection("Ostolaskurivin lisäys");
$insertinvoicerow->setDialog(true);
$insertinvoicerow->setMode(UIComponent::MODE_INSERT);
$insertinvoicerow->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/purchases/insertpurchaserow&purchaseID=' . $registry->purchase->purchaseID);

$defaultinvoicerow = new Row();
if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$variable = "dimension" . $dimension->dimensionID;
		$field = new UISelectField($dimension->name, $variable, $variable, $registry->dimensionvalues[$dimension->dimensionID], "name");
		$insertinvoicerow->addField($field);
		$defaultinvoicerow->$variable = $registry->purchase->$variable;
	}
}
$insertinvoicerow->setData($defaultinvoicerow);

$activecostpools = array();
foreach($registry->costpools as $index => $costpool) {
	if ($costpool->disabled == 0) $activecostpools[$costpool->costpoolID] = $costpool;
}

$costpoolfield = new UISelectField("Kustannuspaikka","costpoolID","costpoolID", $activecostpools, "name");
$costpoolfield->setOnChange("costpoolchanged_" . $insertinvoicerow->getID() . "()");
$costpoolfield->setPredictable(true);
$insertinvoicerow->addField($costpoolfield);

$costpooltypefield = new UISelectField("Kohdetyyppi","costpooltype","costpooltype", $registry->costpooltypes);
//$costpooltypefield->setOnChange("expenditurechanged_" . $insertinvoicerow->getID() . "()");
//$costpoolfield->setPredictable(true);

$costpooltypefield->setDisabled(true);
$insertinvoicerow->addField($costpooltypefield);

$costpooltargetfield = new UISelectField("Kohde","targetID","targetID", array(), "name");
//$costpooltargetfield->setOnChange("expenditurechanged_" . $insertinvoicerow->getID() . "()");
$costpooltargetfield->setCustomCheckFunction("checkcostpoolvalue");
//$costpoolfield->setPredictable(true);
$costpooltargetfield->setDisabled(true);
$insertinvoicerow->addField($costpooltargetfield);

//$costpoolaccountfield = new UISelectField("Tarkenne","costpoolaccountID","costpoolaccountID", array(), "name");
//$insertinvoicerow->addField($costpoolaccountfield);

$vatfield = new UISelectField("ALV","vatID","vatID",$registry->vats, "name");
$vatfield->setOnChange("alvchanged_" . $insertinvoicerow->getID() . "()");
$insertinvoicerow->addField($vatfield);

$insertgrossfield = new UITextField("Brutto", "grossamount", 'grossamount');
$insertgrossfield->setOnBlur("grossamountchanged_" . $insertgrossfield->getID() . "()");
$insertinvoicerow->addField($insertgrossfield);

$insertnetfield = new UITextField("Netto", "netamount", 'netamount');
$insertnetfield->setOnBlur("netamountchanged_" . $insertnetfield->getID() . "()");
$insertinvoicerow->addField($insertnetfield);

$insertvatamountfield = new UITextField("ALV euroa", "vatamount", 'vatamount');
$insertinvoicerow->addField($insertvatamountfield);


echo "<script>";
echo "	function checkcostpoolvalue() {";
echo "		console.log('checkcostpoolvalue');";
echo "	 	var valuefieldID = '#".$costpooltargetfield->getEditFieldID()."';";
echo "		var costpooltypefieldID = '#" . $costpooltypefield->getEditFieldID() . "';";
echo "		var costpooltype = $(costpooltypefieldID).val();";
echo "		console.log('costpooltype - '+costpooltype);";
echo "		if (costpooltype > 0) {";
echo "			var targetvalue = $(valuefieldID).val();";
echo "			console.log('targetvalue - '+targetvalue);";
echo "			if (targetvalue > 0) return true;";
echo "			return false;";
echo "		}";
echo "		return true;";
echo "	}";
echo "	</script>";


echo "<script>";

//echo "	templist = array();";

echo "	function costpoolchanged_" . $insertinvoicerow->getID() . "() {";

echo "	 	var costpoolID = $('#" . $costpoolfield->getEditFieldID() . "').val();";
echo "		console.log('" . getUrl('accounting/purchases/getcostpoolexpendituresJSON') . "&costpoolID='+costpoolID);";
echo "		$.getJSON('" . getUrl('accounting/purchases/getcostpoolexpendituresJSON') . "&costpoolID='+costpoolID,'',function(data) {";
echo "			console.log('expenditurefieldID - " . $costpooltargetfield->getID() . "');";

echo "			console.log('ext data - '+data.vatID);";
echo "	 		$('#" . $vatfield->getEditFieldID() . "').val(data.vatID);";
echo "			updateinsertamounts(1);";

echo "	 		var valuefieldID = '#".$costpooltargetfield->getEditFieldID()."';";
echo "			var costpooltypefieldID = '#" . $costpooltypefield->getEditFieldID() . "';";
echo "			let costpoolitems = data.costpoolitems;";
echo "	 		$(valuefieldID).empty();";
echo "			console.log('len - '+costpoolitems.length);";
echo "			if (costpoolitems.length == 0) {";
echo "				console.log('no expenditures');";
					// Piilota koko expenditure kenttä, tai disabloi se
echo "				$(valuefieldID).attr('disabled','disabled');";
echo "				$(valuefieldID).addClass('uitextfield-disabled');";
echo "				$(valuefieldID).removeClass('uitextfield');";
echo "	 			$('#" . $costpooltypefield->getEditFieldID() . "').val(0);";
echo "			} else {";

echo "				$(costpooltypefieldID).removeAttr('disabled');";
echo "				$(costpooltypefieldID).addClass('uitextfield');";
echo "				$(costpooltypefieldID).removeClass('uitextfield-disabled');";

echo "				$(valuefieldID).removeAttr('disabled');";
echo "				$(valuefieldID).addClass('uitextfield');";
echo "				$(valuefieldID).removeClass('uitextfield-disabled');";
echo "				$(valuefieldID).append($('<option>', {value:0, text:''}));";
echo "				$.each(data.costpoolitems, function(index) {";
echo "					console.log('costpoolaccount - '+costpoolitems[index].name);";
echo "					$(valuefieldID).append($('<option>', {value:costpoolitems[index].itemID, text:costpoolitems[index].name}));";
//echo "					$(valuefieldID).append($('<option>', {value:data[index].expenditureID, text:data[index].name}));";
echo "				});";
echo "	 			$('#" . $costpooltargetfield->getEditFieldID() . "').val(0);";
echo "	 			$('#" . $costpooltypefield->getEditFieldID() . "').val(data.costpooltype);";
echo "			}";
echo "		});";
echo "		console.log('end');";
echo "	}";
echo "</script>";

echo "<script>";
echo "	function alvchanged_" . $insertinvoicerow->getID() . "() {";
echo "		updateinsertamounts(1);";
echo "	}";
echo "</script>";

echo "<script>";
echo "	function grossamountchanged_" . $insertgrossfield->getID() . "() {";
echo "		console.log('grossamount changed');";
echo "		updateinsertamounts(1);";
echo "	}";
echo "</script>";

echo "<script>";
echo "	function netamountchanged_" . $insertnetfield->getID() . "() {";
echo "		console.log('netamount changed');";
echo "		updateinsertamounts(2);";
echo "	}";
echo "</script>";

echo "<script>";
echo "	function updateinsertamounts(input) {";
echo "		var vatpercent = 0;";
echo "	 	var vatID = $('#" . $vatfield->getEditFieldID() . "').val();";
echo "		console.log('selected vatID - ' + vatID);";
foreach($registry->vats as $index => $vat) {
	echo "	if (vatID == " . $vat->vatID . ") {";
	echo "		vatpercent = " . $vat->percent . ";";
	echo "		console.log('vatpercent (" . $vat->vatID . ") found '+vatpercent);";
	echo "	}";
}
echo "	 	var grossstr = $('#" . $insertgrossfield->getEditFieldID() . "').val();";
echo "		if (input == 1) {";
echo "			console.log('vatpercent -' + vatpercent);";
echo "			console.log('gross - ' + grossstr);";
echo "			var grossamount = parseFloat(grossstr.replace(',','.'));";
echo "			console.log('gross - ' + grossamount);";
echo "			var netamount = Math.round(grossamount / (1 + (vatpercent/100))*100) / 100;";
echo "			var vatamount = grossamount - netamount;";
echo "			console.log('-- net - '+netamount);";
echo "			console.log('-- gross - '+grossamount);";
echo "	 		$('#" . $insertgrossfield->getEditFieldID() . "').val(grossamount.toFixed(2).replace('.',','));";
echo "	 		$('#" . $insertnetfield->getEditFieldID() . "').val(netamount.toFixed(2).replace('.',','));";
echo "	 		$('#" . $insertvatamountfield->getEditFieldID() . "').val(vatamount.toFixed(2).replace('.',','));";
echo "		}";
echo "	}";
echo "</script>";

$data = new Row();
$data->grossamount = number_format($delta, 2, ',', '');
$insertinvoicerow->setData($data);
$insertinvoicerow->show();



$editinvoicerow = new UISection("Ostolaskurivin muokkaus");
$editinvoicerow->setDialog(true);
$editinvoicerow->setMode(UIComponent::MODE_INSERT);
$editinvoicerow->setUpdateAction(UIComponent::ACTION_FORWARD, 'accounting/purchases/updatepurchaserow&purchaseID=' . $registry->purchase->purchaseID, "rowID");


if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$field = new UISelectField($dimension->name,"dimension" . $dimension->dimensionID,"dimension" . $dimension->dimensionID,$registry->dimensionvalues[$dimension->dimensionID], "name");
		$editinvoicerow->addField($field);
	}
}


$activecostpools = array();
foreach($registry->costpools as $index => $costpool) {
	if ($costpool->disabled == 0) {
		$activecostpools[$costpool->costpoolID] = $costpool;
	} else {
		$foundinrows = false;
		foreach($registry->purchaserows	as $rowID => $purchaserow) {
			if ($purchaserow->costpoolID == $costpool->costpoolID) {
				$foundinrows = true;
			}
		}
		if ($foundinrows == true) {
			$activecostpools[$costpool->costpoolID] = $costpool;
		}
	}
}


$editcostpoolfield = new UISelectField("Kustannuspaikka","costpoolID","costpoolID",$activecostpools, "name");
$editcostpoolfield->setOnChange("editcostpoolchanged_" . $editinvoicerow->getID() . "()");
//$editcostpoolfield->setPredictable(true);  // TODO: täällä on vika predictable fieldin set value ei toimi
$editinvoicerow->addField($editcostpoolfield);

$editcostpooltypefield = new UISelectField("Kohdetyyppi","costpooltype","costpooltype", $registry->costpooltypes);
$editcostpooltypefield->setDisabled(true);
$editinvoicerow->addField($editcostpooltypefield);

$editcostpooltargetfield = new UISelectField("Kohde","targetID","targetID", array(), "name");
//$editcostpooltargetfield->setOnChange("edittargetfieldchanged_" . $insertinvoicerow->getID() . "()");
$editcostpooltargetfield->setCustomCheckFunction("checkeditcostpoolvalue");
$editcostpooltargetfield->setDisabled(true);
$editinvoicerow->addField($editcostpooltargetfield);

$editvatfield = new UISelectField("ALV%","vatID","vatID",$registry->vats, "name");
$editvatfield->setOnChange("editalvchanged_" . $editinvoicerow->getID() . "()");
$editinvoicerow->addField($editvatfield);

$editgrossamountfield = new UITextField("Brutto", "grossamount", 'grossamount');
$editgrossamountfield->setOnBlur("editgrossamountchanged_" . $editinvoicerow->getID() . "()");
$editinvoicerow->addField($editgrossamountfield);

$editnetamountfield = new UITextField("Netto", "netamount", 'netamount');
$editnetamountfield->setOnBlur("editnetamountchanged_" . $editinvoicerow->getID() . "()");
$editinvoicerow->addField($editnetamountfield);

$editvatamountfield = new UITextField("ALV", "vatamount", 'vatamount');
$editinvoicerow->addField($editvatamountfield);

$editinvoicerow->show();



if ($registry->purchase->state == 1) {
	$editrowwarning = new UISection("Ostolaskurivin muokkaus", "700px");
	$editrowwarning->setDialog(true);
	$editrowwarning->addErrorMessage("Hyväksytyn ostolaskun riviä ei voi enää muokata.");
	$editrowwarning->addErrorMessage("Mikäli haluat muokata riviä, siirrä ostolasku takaisin avoimeksi.");
	$editrowwarning->show();
} else {
	$editrowwarning = new UISection("Ostolaskurivin muokkaus", "700px");
	$editrowwarning->setDialog(true);
	$editrowwarning->addErrorMessage("Ostolaskun riviä ei voida enää muokata.");
	$editrowwarning->show();
}



echo "<script>";
echo "	function editalvchanged_" . $editinvoicerow->getID() . "() {";
echo "		updateeditamounts(1);";
echo "	}";
echo "</script>";

echo "<script>";
echo "	function editgrossamountchanged_" . $editinvoicerow->getID() . "() {";
echo "		console.log('grossamount changed');";
echo "		updateeditamounts(1);";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function editnetamountchanged_" . $editinvoicerow->getID() . "() {";
echo "		console.log('netamount changed');";
echo "		updateeditamounts(2);";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function updateeditamounts(input) {";
echo "		var vatpercent = 0;";
echo "	 	var vatID = $('#" . $editvatfield->getEditFieldID() . "').val();";
echo "		console.log('selected vatID - ' + vatID);";
foreach($registry->vats as $index => $vat) {
	echo "	if (vatID == " . $vat->vatID . ") {";
	echo "		vatpercent = " . $vat->percent . ";";
	echo "		console.log('vatpercent (" . $vat->vatID . ") found '+vatpercent);";
	echo "	}";
}
echo "	 	var grossstr = $('#" . $editgrossamountfield->getEditFieldID() . "').val();";
echo "		if (input == 1) {";
echo "			console.log('vatpercent -' + vatpercent);";
echo "			console.log('gross - ' + grossstr);";
echo "			var grossamount = parseFloat(grossstr.replace(',','.'));";
echo "			console.log('gross - ' + grossamount);";
echo "			var netamount = Math.round(grossamount / (1 + (vatpercent/100))*100) / 100;";
echo "			var vatamount = grossamount - netamount;";
echo "			console.log('-- net - '+netamount);";
echo "			console.log('-- gross - '+grossamount);";
echo "	 		$('#" . $editgrossamountfield->getEditFieldID() . "').val(grossamount.toFixed(2).replace('.',','));";
echo "	 		$('#" . $editnetamountfield->getEditFieldID() . "').val(netamount.toFixed(2).replace('.',','));";
echo "	 		$('#" . $editvatamountfield->getEditFieldID() . "').val(vatamount.toFixed(2).replace('.',','));";
echo "		}";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function checkeditcostpoolvalue() {";
echo "		console.log('checkeditcostpoolvalue');";
echo "	 	var valuefieldID = '#".$editcostpooltargetfield->getEditFieldID()."';";
echo "		var costpooltypefieldID = '#" . $editcostpooltypefield->getEditFieldID() . "';";
echo "		var costpooltype = $(costpooltypefieldID).val();";
echo "		console.log('costpooltype - '+costpooltype);";
echo "		if (costpooltype > 0) {";
echo "			var targetvalue = $(valuefieldID).val();";
echo "			console.log('targetvalue - '+targetvalue);";
echo "			if (targetvalue > 0) return true;";
echo "			return false;";
echo "		}";
echo "		return true;";
echo "	}";
echo "	</script>";



echo "<script>";

//echo "	templist = array();";

echo "	function editcostpoolchanged_" . $editinvoicerow->getID() . "() {";
echo "	 	var costpoolID = $('#" . $editcostpoolfield->getEditFieldID() . "').val();";
echo "		console.log('" . getUrl('accounting/purchases/getcostpoolexpendituresJSON') . "&costpoolID='+costpoolID);";
echo "		$.getJSON('" . getUrl('accounting/purchases/getcostpoolexpendituresJSON') . "&costpoolID='+costpoolID,'',function(data) {";
echo "	 		var valuefieldID = '#".$editcostpooltargetfield->getEditFieldID()."';";
echo "			var costpooltypefieldID = '#" . $editcostpooltypefield->getEditFieldID() . "';";
echo "			let costpoolitems = data.costpoolitems;";
echo "	 		$(valuefieldID).empty();";
echo "			console.log('len - '+costpoolitems.length);";
echo "			if (costpoolitems.length == 0) {";
echo "				console.log('no expenditures');";
echo "				$(valuefieldID).attr('disabled','disabled');";
echo "				$(valuefieldID).addClass('uitextfield-disabled');";
echo "				$(valuefieldID).removeClass('uitextfield');";
echo "	 			$('#" . $editcostpooltypefield->getEditFieldID() . "').val(0);";
echo "			} else {";
echo "				$(valuefieldID).removeAttr('disabled');";
echo "				$(valuefieldID).addClass('uitextfield');";
echo "				$(valuefieldID).removeClass('uitextfield-disabled');";
echo "				$(valuefieldID).append($('<option>', {value:0, text:''}));";
echo "				$.each(data.costpoolitems, function(index) {";
echo "					console.log('costpoolaccount - '+costpoolitems[index].name);";
echo "					$(valuefieldID).append($('<option>', {value:costpoolitems[index].itemID, text:costpoolitems[index].name}));";
echo "				});";
echo "	 			$('#" . $editcostpooltargetfield->getEditFieldID() . "').val(0);";
echo "	 			$('#" . $editcostpooltypefield->getEditFieldID() . "').val(data.costpooltype);";
echo "			}";
echo "		});";
echo "		console.log('end');";
echo "	}";
echo "</script>";






$invoicerowstable = new UITableSection("Ostolaskurivit", '700px');
$invoicerowstable->setOpen(true);
$invoicerowstable->setFramesVisible(true);
$invoicerowstable->setShowSumRow(true);

$sum = 0;
foreach($registry->purchaserows as $index => $row) {
	$sum = $sum + $row->grossamount;
}

//echo "<br>Sum - " . $sum;
//echo "<br>Sum - " . $registry->purchase->grossamount;

$delta = $registry->purchase->grossamount - $sum;
//echo "<br>Delta - " . $delta;
if (($delta < -0.00001) || ($delta > 0.00001)) {

//if ($sum != $registry->purchase->grossamount) {
	
	//$delta = $registry->purchase->grossamount - $sum;
	//echo "<br>Delta - " . $delta;
	//if (($delta < -0.0001) || ($delta > 0.0001)) {
	if ($sum < $registry->purchase->grossamount) {
	
		//if ($delta < -0.0001) echo "<br>delta smaller - " . $delta;
		//if ($delta > 0.0001) echo "<br>delta smaller - " . $delta;
		$delta = $registry->purchase->grossamount - $sum;
		//echo "<br>grossamount - " . $registry->purchase->grossamount;
		//echo "<br>sum - " . $sum;
		
		$invoicerowstable->setErrorMessage("Tiliöimättä &nbsp;&nbsp;" . number_format($delta, 2, ',', '') . "€");
	} else {
		$invoicerowstable->setErrorMessage("Tiliöity liikaa &nbsp;&nbsp;" . number_format($registry->purchase->grossamount, 2, ',', '') . "€");
	}
}

$invoicerowstable->setDeleteAction(UIComponent::ACTION_FORWARD, 'accounting/purchases/removepurchaserow&purchaseID=' . $registry->purchase->purchaseID, 'rowID');

/*
if ($registry->purchase->state == 0) {
	$invoicerowstable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editinvoicerow->getID(), "rowID");
} else {
	$invoicerowstable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editrowwarning->getID(), "rowID");
}
*/
$invoicerowstable->setLineAction(UIComponent::ACTION_JAVASCRIPT, "openEditInvoiceRow", "rowID");




$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertinvoicerow->getID(), "Lisää ostolaskurivi");
$invoicerowstable->addButton($button);


$editrowidcolumn = new UISortColumn("#", "rowID", "rowID");
$invoicerowstable->addColumn($editrowidcolumn);


if (count($this->registry->dimensions) > 0) {

	foreach($this->registry->dimensions as $index => $dimension) {

		//$field = new UISelectField($dimension->abbreviation,"dimension" . $dimension->dimensionID,"dimension" . $dimension->dimensionID,$registry->dimensionvalues[$dimension->dimensionID], "abbreviation");
		$column = new UISelectColumn($dimension->abbreviation, "name", "dimension" . $dimension->dimensionID, $registry->dimensionvalues[$dimension->dimensionID]);
		$column->setUndefinedString("-");
		$invoicerowstable->addColumn($column);
	}
}

$column = new UISortColumn("Kustannuspaikka", "costpoolname", "costpoolname");
$invoicerowstable->addColumn($column);

//$column = new UISelectColumn("ALV", "short", "vatID", $registry->vats);
//$invoicerowstable->addColumn($column);

$editvatamountcolumn = new UISortColumn("ALV", "vatamount", "vatamount");
$editvatamountcolumn->setAlign(Column::ALIGN_RIGHT);
$editvatamountcolumn->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$invoicerowstable->addColumn($editvatamountcolumn);

$editnetamountcolumn = new UISortColumn("Netto", "netamount", "netamount");
$editnetamountcolumn->setAlign(Column::ALIGN_RIGHT);
$editnetamountcolumn->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$invoicerowstable->addColumn($editnetamountcolumn);

$editgrossamountcolumn = new UISortColumn("Brutto", "grossamount", "grossamount");
$editgrossamountcolumn->setAlign(Column::ALIGN_RIGHT);
$editgrossamountcolumn->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$invoicerowstable->addColumn($editgrossamountcolumn);

$costpoolColumn = new UIHiddenColumn("costpoolID", "costpoolID");
$invoicerowstable->addColumn($costpoolColumn);

$editcostpooltypecolumn= new UIHiddenColumn("costpooltype", "costpooltype");
$invoicerowstable->addColumn($editcostpooltypecolumn);

$edittargetcolumn = new UIHiddenColumn("targetID", "targetID");
$invoicerowstable->addColumn($edittargetcolumn);

$editvatidcolumn = new UIHiddenColumn("vatID", "vatID");
$invoicerowstable->addColumn($editvatidcolumn);


foreach ($registry->purchaserows as $index => $row) {
	if ($row->purchaseentryID > 0) $row->entryID = $row->purchaseentryID;
	if ($row->vatentryID > 0) $row->entryID = $row->vatentryID;
	if ($row->payablesentryID > 0) $row->entryID = $row->payablesentryID;
}

$invoicerowstable->setData($registry->purchaserows);

$invoicerowstable->show();



echo "<script>";
echo "	function openEditInvoiceRow(rowNumber) {";
echo "		console.log('openEditInvoiceRow pressed - '+rowNumber);";

echo "  	var rowID = $('#tablerow-" . $invoicerowstable->getID() . "-'+rowNumber+'-" .  $editrowidcolumn->getID() . "').val();";
echo "		console.log('rowID - '+rowID);";
echo "		$('#sectionidvalue-" . $editinvoicerow->getID() . "').val(rowID);";


echo "  	var costpoolID = $('#tablerow-" . $invoicerowstable->getID() . "-'+rowNumber+'-" .  $costpoolColumn->getID() . "').val();";
echo "		console.log('costpoolID - '+costpoolID);";
echo "	 	$('#" . $editcostpoolfield->getEditFieldID() . "').val(costpoolID);";

echo "  	var costpooltype = $('#tablerow-" . $invoicerowstable->getID() . "-'+rowNumber+'-" .  $editcostpooltypecolumn->getID() . "').val();";
echo "		console.log('costpooltype - '+costpooltype);";
echo "	 	$('#" . $editcostpooltypefield->getEditFieldID() . "').val(costpooltype);";

echo "  	var targetID = $('#tablerow-" . $invoicerowstable->getID() . "-'+rowNumber+'-" .  $edittargetcolumn->getID() . "').val();";
echo "		console.log('targetID - '+targetID);";
//echo "	 	$('#" . $editcostpooltypefield->getEditFieldID() . "').val(costpooltype);";

echo "  	var vatID = $('#tablerow-" . $invoicerowstable->getID() . "-'+rowNumber+'-" .  $editvatidcolumn->getID() . "').val();";
echo "		console.log('vatID - '+vatID);";
echo "	 	$('#" . $editvatfield->getEditFieldID() . "').val(vatID);";

echo "  	var grossvalue = $('#tablerow-" . $invoicerowstable->getID() . "-'+rowNumber+'-" .  $editgrossamountcolumn->getID() . "').val();";
echo "		console.log('grossvalue - '+grossvalue);";
echo "		let grossfloat = Number.parseFloat(grossvalue);";
echo "		var grossstr = grossfloat.toFixed(2);";
echo "		var grossstr2 = grossstr.replace('.',',');";
echo "		console.log('grossstr - '+grossstr2);";
echo "	 	$('#" . $editgrossamountfield->getEditFieldID() . "').val(grossstr2);";

echo "  	var netvalue = $('#tablerow-" . $invoicerowstable->getID() . "-'+rowNumber+'-" .  $editnetamountcolumn->getID() . "').val();";
echo "		let netfloat = Number.parseFloat(netvalue);";
echo "		var netstr = netfloat.toFixed(2);";
echo "		var netstr2 = netstr.replace('.',',');";
echo "		console.log('netstr2 - '+netstr2);";
echo "	 	$('#" . $editnetamountfield->getEditFieldID() . "').val(netstr2);";


echo "  	var vatvalue = $('#tablerow-" . $invoicerowstable->getID() . "-'+rowNumber+'-" .  $editvatamountcolumn->getID() . "').val();";
echo "		console.log('vatvalue -- '+vatvalue);";
echo "		let vatfloat = Number.parseFloat(vatvalue);";
echo "		var vatstr = vatfloat.toFixed(2);";
echo "		var vatstr2 = vatstr.replace('.',',');";
echo "		console.log('vatvalue - '+vatstr2);";
echo "	 	$('#" . $editvatamountfield->getEditFieldID() . "').val(vatstr2);";


echo "		$.getJSON('" . getUrl('accounting/purchases/getcostpoolexpendituresJSON') . "&costpoolID='+costpoolID,'',function(data) {";
echo "			console.log('expenditurefieldID - " . $costpooltargetfield->getID() . "');";

echo "	 		var valuefieldID = '#" . $editcostpooltargetfield->getEditFieldID() . "';";
//echo "			var costpooltypefieldID = '#" . $costpooltypefield->getEditFieldID() . "';";
echo "			let costpoolitems = data.costpoolitems;";
echo "	 		$(valuefieldID).empty();";
echo "			console.log('len - '+costpoolitems.length);";
echo "			if (costpoolitems.length == 0) {";
echo "				console.log('no expenditures');";
// Piilota koko expenditure kenttä, tai disabloi se
echo "				$(valuefieldID).attr('disabled','disabled');";
echo "				$(valuefieldID).addClass('uitextfield-disabled');";
echo "				$(valuefieldID).removeClass('uitextfield');";
echo "	 			$('#" . $editcostpooltargetfield->getEditFieldID() . "').val(0);";
echo "			} else {";

//echo "				$(costpooltypefieldID).removeAttr('disabled');";
//echo "				$(costpooltypefieldID).addClass('uitextfield');";
//echo "				$(costpooltypefieldID).removeClass('uitextfield-disabled');";

echo "				$(valuefieldID).removeAttr('disabled');";
echo "				$(valuefieldID).addClass('uitextfield');";
echo "				$(valuefieldID).removeClass('uitextfield-disabled');";
echo "				$(valuefieldID).append($('<option>', {value:0, text:''}));";
echo "				$.each(data.costpoolitems, function(index) {";
echo "					console.log('costpoolaccount - '+costpoolitems[index].name);";
echo "					$(valuefieldID).append($('<option>', {value:costpoolitems[index].itemID, text:costpoolitems[index].name}));";
//echo "					$(valuefieldID).append($('<option>', {value:data[index].expenditureID, text:data[index].name}));";
echo "				});";
echo "  			var targetID = $('#tablerow-" . $invoicerowstable->getID() . "-'+rowNumber+'-" .  $edittargetcolumn->getID() . "').val();";
echo "				console.log('targetID - '+targetID);";
echo "	 			$('#" . $editcostpooltargetfield->getEditFieldID() . "').val(targetID);";
//echo "	 			$('#" . $costpooltypefield->getEditFieldID() . "').val(data.costpooltype);";
echo "			}";
echo "		});";
echo "  	$('#sectiondialog-" . $editinvoicerow->getID() . "').dialog('open');";
echo "		console.log('end');";
echo "	}";
echo "</script>";




//if ($registry->purchase->state > 0) {
	


	$editentryrowdialog = new UISection("Viennin muokkaus");
	$editentryrowdialog->setDialog(true);
	$editentryrowdialog->setMode(UIComponent::MODE_INSERT);
	$editentryrowdialog->setUpdateAction(UIComponent::ACTION_FORWARD, 'accounting/purchases/updateentry&purchaseID=' . $registry->purchase->purchaseID . '&receiptID=' . $registry->purchase->receiptID, "rowID");
	
	$editentrydialogentryidfield = new UITextField("EntryID", "entryID", 'entryID');
	$editentryrowdialog->addField($editentrydialogentryidfield);
	
	$editentrydialogentrydatefield = new UIDateField("Päiväys", "entrydate", 'entrydate');
	$editentryrowdialog->addField($editentrydialogentrydatefield);
	
	$editentrydialogaccountselect = new UISelectField("Kirjanpitotili","accountID","accountID",$registry->accounts, "fullname");
	//$editentrydialogaccountselect->setPredictive(true);
	$editentryrowdialog->addField($editentrydialogaccountselect);
	
	$editentrydialogcostpoolselect = new UISelectField("Kustannuspaikka","costpoolID","costpoolID",$registry->costpools, "name");
	$editentrydialogcostpoolselect->setOnChange("editentrycostpoolchanged_" . $editentryrowdialog->getID() . "()");
	$editentryrowdialog->addField($editentrydialogcostpoolselect);
	
	$editentrydialeogcostpooltypeselect = new UISelectField("Kohdetyyppi","costpooltype","costpooltype", $registry->costpooltypes);
	$editentrydialeogcostpooltypeselect->setDisabled(true);
	$editentryrowdialog->addField($editentrydialeogcostpooltypeselect);
	
	$editentrydialegcostpooltargetselect = new UISelectField("Kohde","targetID","targetID", array(), "name");
	$editentrydialegcostpooltargetselect->setCustomCheckFunction("checkcostpoolvalue");
	$editentrydialegcostpooltargetselect->setDisabled(true);
	$editentryrowdialog->addField($editentrydialegcostpooltargetselect);
	
	$editentrydialolgvatcodeselect = new UISelectField("ALV-Koodi","vatcodeID","vatcodeID",$registry->vatcodes, "fullname");
	$editentryrowdialog->addField($editentrydialolgvatcodeselect);
	
	$editentrydialogdebetfield = new UITextField("Debet", "debet", 'debet');
	$editentryrowdialog->addField($editentrydialogdebetfield);
	
	$editentrydialogcreditfield = new UITextField("Credit", "credit", 'credit');
	$editentryrowdialog->addField($editentrydialogcreditfield);
	
	$editentryrowdialog->show();
	
	

	
	echo "<script>";
	echo "	function editentrycostpoolchanged_" . $editentryrowdialog->getID() . "() {";
	echo "	 	var costpoolID = $('#" . $editentrydialogcostpoolselect->getEditFieldID() . "').val();";
	echo "		console.log('" . getUrl('accounting/purchases/getcostpoolexpendituresJSON') . "&costpoolID='+costpoolID);";
	echo "		$.getJSON('" . getUrl('accounting/purchases/getcostpoolexpendituresJSON') . "&costpoolID='+costpoolID,'',function(data) {";
	echo "	 		var valuefieldID = '#".$editentrydialegcostpooltargetselect->getEditFieldID()."';";
	echo "			var costpooltypefieldID = '#" . $editentrydialeogcostpooltypeselect->getEditFieldID() . "';";
	echo "			let costpoolitems = data.costpoolitems;";
	echo "	 		$(valuefieldID).empty();";
	echo "			console.log('len - '+costpoolitems.length);";
	echo "			if (costpoolitems.length == 0) {";
	echo "				console.log('no expenditures');";
	echo "				$(valuefieldID).attr('disabled','disabled');";
	echo "				$(valuefieldID).addClass('uitextfield-disabled');";
	echo "				$(valuefieldID).removeClass('uitextfield');";
	echo "	 			$('#" . $editentrydialeogcostpooltypeselect->getEditFieldID() . "').val(0);";
	echo "			} else {";
	echo "				$(valuefieldID).removeAttr('disabled');";
	echo "				$(valuefieldID).addClass('uitextfield');";
	echo "				$(valuefieldID).removeClass('uitextfield-disabled');";
	echo "				$(valuefieldID).append($('<option>', {value:0, text:''}));";
	echo "				$.each(data.costpoolitems, function(index) {";
	echo "					console.log('costpoolaccount - '+costpoolitems[index].name);";
	echo "					$(valuefieldID).append($('<option>', {value:costpoolitems[index].itemID, text:costpoolitems[index].name}));";
	echo "				});";
	echo "	 			$('#" . $editentrydialegcostpooltargetselect->getEditFieldID() . "').val(0);";
	echo "	 			$('#" . $editentrydialeogcostpooltypeselect->getEditFieldID() . "').val(data.costpooltype);";
	echo "			}";
	echo "		});";
	echo "		console.log('end');";
	echo "	}";
	echo "</script>";
	
	
	
	
	
	
	$insertentrysection = new UISection("Viennin lisäys");
	$insertentrysection->setDialog(true);
	$insertentrysection->setMode(UIComponent::MODE_INSERT);
	$insertentrysection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/purchases/insertentry&purchaseID=' . $registry->purchase->purchaseID . '&receiptID=' . $registry->purchase->receiptID);
	
	$field = new UIDateField("Päiväys", "entrydate", 'entrydate');
	$insertentrysection->addField($field);
	
	$field = new UISelectField("Tili","accountID","accountID",$registry->accounts, "fullname");
	$field->setPredictive(true);
	$insertentrysection->addField($field);
	
	$field = new UISelectField("Vatcode","vatcodeID","vatcodeID",$registry->vatcodes, "vatcode");
	$insertentrysection->addField($field);
	
	$field = new UITextField("Amount", "amount", 'amount');
	$insertentrysection->addField($field);
	
	//$field = new UITextField("Debet", "debet", 'debet');
	//$insertentrysection->addField($field);
	
	//$field = new UITextField("Credit", "credit", 'credit');
	//$insertentrysection->addField($field);
	
	$empty = new Row();
	$empty->entrydate =$registry->purchase->purchasedate;
	$empty->amount =$registry->purchase->grossamount;
	
	$insertentrysection->setData($empty);
	$insertentrysection->show();
//}



$width = "1200px";

if ($registry->purchase->state == 0) {
	
	$editentrywarning = new UISection("Viennin muokkaus", $width);
	$editentrywarning->setDialog(true);
	$editentrywarning->addErrorMessage("Avoimen ostolaskun viennit on luotu automaattisesti ostotavan ja kustannuspaikan perustella. Avoimen ostolaskun vientejä ei voi muokata.");
	$editentrywarning->addErrorMessage("Muokataksesi vientejä siirrä ostolasku ensin hyväksytyksi.");
	$editentrywarning->show();
	
	$entriestable = new UITableSection("Viennit (alustavat)", $width);
	$entriestable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editentrywarning->getID(), "rowID");
} else {
	
	
	$entriestable = new UITableSection("Viennit2", $width);
	$entriestable->setLineAction(UIComponent::ACTION_JAVASCRIPT, "openEditEntryRow", "entryID");
	//$entriestable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editentryrowdialog->getID(), "rowID");
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertentrysection->getID(), "Lisää vienti");
	$entriestable->addButton($button);
	$entriestable->setDeleteAction(UIComponent::ACTION_FORWARD, 'accounting/purchases/removeentry&purchaseID=' . $registry->purchase->purchaseID, 'entryID');
}




$entriestable->setOpen(true);
$entriestable->setFramesVisible(true);
$entriestable->setShowSumRow(true);

$entriestableidcolumn = new UISortColumn("#", "entryID", "entryID");
$entriestable->addColumn($entriestableidcolumn);

if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		//$field = new UISelectField($dimension->abbreviation,"dimension" . $dimension->dimensionID,"dimension" . $dimension->dimensionID,$registry->dimensionvalues[$dimension->dimensionID], "abbreviation");
		$column = new UISelectColumn($dimension->abbreviation, "name", "dimension" . $dimension->dimensionID, $registry->dimensionvalues[$dimension->dimensionID]);
		$column->setUndefinedString("-");
		$entriestable->addColumn($column);
	}
}


$entriestableentrydatecolumn = new UISortColumn("Päiväys", "entrydate");
$entriestableentrydatecolumn->setFormatter(Column::COLUMNTYPE_DATE);
$entriestable->addColumn($entriestableentrydatecolumn);

$entriestableaccountidcolumn = new UISelectColumn("Tilinro", "number", "accountID", $registry->accounts);
$entriestable->addColumn($entriestableaccountidcolumn);

$column = new UISelectColumn("Tili", "name", "accountID", $registry->accounts, null, "240px");
$entriestable->addColumn($column);

$entriestablevatcodeidcolumn = new UISelectColumn("ALV", "vatcode", "vatcodeID", $registry->vatcodes);
$entriestable->addColumn($entriestablevatcodeidcolumn);

$entriestabledebetcolumn = new UISortColumn("Debet", "debet", "debet");
$entriestabledebetcolumn->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$entriestabledebetcolumn->setAlign(Column::ALIGN_RIGHT);
$entriestable->addColumn($entriestabledebetcolumn);

$entriestablecreditcolumn = new UISortColumn("Credit", "credit", "credit");
$entriestablecreditcolumn->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$entriestablecreditcolumn->setAlign(Column::ALIGN_RIGHT);
$entriestable->addColumn($entriestablecreditcolumn);

$entriestablecostpoolcolumn = new UISortColumn("CstP", "costpoolID", "costpoolID");
$entriestable->addColumn($entriestablecostpoolcolumn);

$entriestablecostpooltypecolumn = new UISortColumn("CPtype", "costpooltype", "costpooltype");
$entriestable->addColumn($entriestablecostpooltypecolumn);

$entriestableaccounttypecolumn = new UISortColumn("AtpID", "accounttypeID", "accounttypeID");
$entriestable->addColumn($entriestableaccounttypecolumn);

$entriestablepurchaseidcolumn = new UISortColumn("PrcID", "purchaseID", "purchaseID");
$entriestable->addColumn($entriestablepurchaseidcolumn);

$entriestableaccountidcolumn = new UISortColumn("AcID", "accountID", "accountID");
$entriestable->addColumn($entriestableaccountidcolumn);

$entriestablesupplieridcolumn = new UISortColumn("S#ID", "supplierID", "supplierID");
$entriestable->addColumn($entriestablesupplieridcolumn);

$entriestableassetidcolumn = new UISortColumn("A#ID", "assetID", "assetID");
$entriestable->addColumn($entriestableassetidcolumn);

$entriestableworkeridcolumn = new UISortColumn("W#ID", "workerID", "workerID");
$entriestable->addColumn($entriestableworkeridcolumn);

$entriestableclientidcolumn = new UISortColumn("C#ID", "clientID", "clientID");
$entriestable->addColumn($entriestableclientidcolumn);

$entriestableliabilityidcolumn = new UISortColumn("L#ID", "liabilityID", "liabilityID");
$entriestable->addColumn($entriestableliabilityidcolumn);

$entriestabletargetidcolumn = new UISortColumn("T#ID", "targetID", "targetID");
$entriestable->addColumn($entriestabletargetidcolumn);

//$column = new UIHiddenColumn("EnryID", "entryID", "entryID");
//$entriestable->addColumn($column);

$entriestable->setData($registry->entries);
$entriestable->show();



/*
$editentrydialogentryidfield
$editentrydialogentrydatefield
$editentrydialogaccountselect
$editentrydialogcostpoolselect
$editentrydialeogcostpooltypeselect
$editentrydialegcostpooltargetselect
$editentrydialolgvatcodeselect
$editentrydialogdebetfield
$editentrydialogcreditfield
*/

echo "<script>";
echo "	function openEditEntryRow(rowNumber) {";
echo "		console.log('openEditInvoiceRow pressed - '+rowNumber);";

echo "  	var entryID = $('#tablerow-" . $entriestable->getID() . "-'+rowNumber+'-" .  $entriestableidcolumn->getID() . "').val();";
echo "		console.log('entryID - '+entryID);";
echo "		$('#sectionidvalue-" . $editentryrowdialog->getID() . "').val(entryID);";
echo "	 	$('#" . $editentrydialogentryidfield->getEditFieldID() . "').val(entryID);";

echo "  	var entrydate = $('#tablerow-" . $entriestable->getID() . "-'+rowNumber+'-" .  $entriestableentrydatecolumn->getID() . "').val();";
echo "		console.log('entrydate - '+entrydate);";
echo "		var newdate = entrydate.substring(8,11)+'.'+entrydate.substring(5,7)+'.'+entrydate.substring(0,4);";
echo "	 	$('#" . $editentrydialogentrydatefield->getEditFieldID() . "').val(newdate);";

echo "  	var accountID = $('#tablerow-" . $entriestable->getID() . "-'+rowNumber+'-" .  $entriestableaccountidcolumn->getID() . "').val();";
echo "		console.log('accountID - '+accountID);";
echo "	 	$('#" . $editentrydialogaccountselect->getEditFieldID() . "').val(accountID);";

echo "  	var costpoolID = $('#tablerow-" . $entriestable->getID() . "-'+rowNumber+'-" .  $entriestablecostpoolcolumn->getID() . "').val();";
echo "		console.log('costpoolID - '+costpoolID);";
echo "	 	$('#" . $editentrydialogcostpoolselect->getEditFieldID() . "').val(costpoolID);";

echo "  	var costpooltype = $('#tablerow-" . $entriestable->getID() . "-'+rowNumber+'-" .  $entriestablecostpooltypecolumn->getID() . "').val();";
echo "		console.log('costpooltype - '+costpooltype);";
echo "	 	$('#" . $editentrydialeogcostpooltypeselect->getEditFieldID() . "').val(costpooltype);";

echo "  	var targetID = $('#tablerow-" . $entriestable->getID() . "-'+rowNumber+'-" .  $entriestabletargetidcolumn->getID() . "').val();";
echo "		console.log('targetID - '+targetID);";
//echo "	 	$('#" . $editcostpooltypefield->getEditFieldID() . "').val(costpooltype);";

echo "  	var vatcodeID = $('#tablerow-" . $entriestable->getID() . "-'+rowNumber+'-" .  $entriestablevatcodeidcolumn->getID() . "').val();";
echo "		console.log('vatcodeID - '+vatcodeID);";
echo "	 	$('#" . $editentrydialolgvatcodeselect->getEditFieldID() . "').val(vatcodeID);";

echo "  	var debetvalue = $('#tablerow-" . $entriestable->getID() . "-'+rowNumber+'-" .  $entriestabledebetcolumn->getID() . "').val();";
echo "		console.log('debetvalue - '+debetvalue);";
echo "		let debetfloat = Number.parseFloat(debetvalue);";
echo "		var debetstr = debetfloat.toFixed(2);";
echo "		var debetstr2 = debetstr.replace('.',',');";
echo "		console.log('debetstr2 - '+debetstr2);";
echo "	 	$('#" . $editentrydialogdebetfield->getEditFieldID() . "').val(debetstr2);";

echo "  	var creditvalue = $('#tablerow-" . $entriestable->getID() . "-'+rowNumber+'-" .  $entriestablecreditcolumn->getID() . "').val();";
echo "		console.log('creditvalue - '+creditvalue);";
echo "		let creditfloat = Number.parseFloat(creditvalue);";
echo "		var creditstr = creditfloat.toFixed(2);";
echo "		var creditstr2 = creditstr.replace('.',',');";
echo "		console.log('creditstr2 - '+creditstr2);";
echo "	 	$('#" . $editentrydialogcreditfield->getEditFieldID() . "').val(creditstr2);";

echo "		$.getJSON('" . getUrl('accounting/purchases/getcostpoolexpendituresJSON') . "&costpoolID='+costpoolID,'',function(data) {";
echo "			console.log('targetfieldID - " . $editentrydialogcostpoolselect->getID() . "');";

echo "	 		var valuefieldID = '#" . $editentrydialegcostpooltargetselect->getEditFieldID() . "';";
echo "			let costpoolitems = data.costpoolitems;";
echo "	 		$(valuefieldID).empty();";
//echo "			console.log('len - '+costpoolitems.length);";
echo "			if (costpoolitems.length == 0) {";
echo "				console.log('no costpoolitems');";
echo "				$(valuefieldID).attr('disabled','disabled');";
echo "				$(valuefieldID).addClass('uitextfield-disabled');";
echo "				$(valuefieldID).removeClass('uitextfield');";
echo "	 			$('#" . $editentrydialegcostpooltargetselect->getEditFieldID() . "').val(0);";
echo "			} else {";
echo "				$(valuefieldID).removeAttr('disabled');";
echo "				$(valuefieldID).addClass('uitextfield');";
echo "				$(valuefieldID).removeClass('uitextfield-disabled');";
echo "				$(valuefieldID).append($('<option>', {value:0, text:''}));";
echo "				$.each(data.costpoolitems, function(index) {";
//echo "					console.log('costpoolaccount - '+costpoolitems[index].name);";
echo "					$(valuefieldID).append($('<option>', {value:costpoolitems[index].itemID, text:costpoolitems[index].name}));";
echo "				});";
echo "  			var targetID = $('#tablerow-" . $entriestable->getID() . "-'+rowNumber+'-" .  $entriestabletargetidcolumn->getID() . "').val();";
//echo "				console.log('targetID - '+targetID);";
echo "	 			$('#" . $editentrydialegcostpooltargetselect->getEditFieldID() . "').val(targetID);";
echo "			}";
echo "		});";
echo "  	$('#sectiondialog-" . $editentryrowdialog->getID() . "').dialog('open');";
echo "		console.log('end');";
echo "	}";
echo "</script>";




//echo "<br>entrycount  -- " . count($registry->entries);



$copyinvoicesection = new UISection('Ostolaskun kopiointi','550px');
$copyinvoicesection->setDialog(true);
$copyinvoicesection->setMode(UIComponent::MODE_INSERT);
$copyinvoicesection->setSaveAction(UIComponent::ACTION_FORWARD, "accounting/purchases/copypurchase&purchaseID=" . $registry->purchase->purchaseID);

//$field = new UISelectField("Myyjä","supplierID","supplierID",$registry->suppliers, "name");
//$copyinvoicesection->addField($field);

//$field = new UISelectField("Maksutapa","paymentmethodID","paymentmethodID", $registry->paymentmethods, "name");
//$copyinvoicesection->addField($field);

$field = new UIDateField("Ostopäivä", "purchasedate", 'purchasedate');
$copyinvoicesection->addField($field);

$field = new UIDateField("Eräpäivä", "duedate", 'duedate');
$copyinvoicesection->addField($field);

$row = new Row();
$row->supplierID = $registry->purchase->supplierID;
$row->paymentmethodID = $registry->purchase->paymentmethodID;
$row->purchasedate = $registry->purchase->purchasedate;
$row->duedate = $registry->purchase->duedate;

$copyinvoicesection->setData($row);
$copyinvoicesection->show();




/*
$paymentstable = new UITableSection("Maksutiedot", '700px');

//$entriestable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editentryrowdialog->getID(), "rowID");
//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertentrysection->getID(), "Lisää vienti");
//$entriestable->addButton($button);
//}


$paymentstable->setDeleteAction(UIComponent::ACTION_FORWARD, 'accounting/purchases/removepayment&purchaseID=' . $registry->purchase->purchaseID, 'paymentID');

$paymentstable->setOpen(true);
$paymentstable->setFramesVisible(true);
$paymentstable->setTableHeaderVisible(false);

//$column = new UISortColumn("#", "paymentID", "paymentID");
//$entriestable->addColumn($column);

$open = 0;
$paid = 0;

foreach($registry->payments as $index => $payment) {
	
	//echo "<br>PaymentID: " . $payment->paymentID;
	$payment->amount = number_format($payment->grossamount, 2, ',', '') . "€";
	$payment->duedatestr = sqlDateToStr($payment->duedate);
	
	if ($payment->paymentstatus == Collections::PAYMENTSTATUS_OPEN) {
		$open++;
		
		//echo "<br>Paymenttype: " . $payment->paymenttype;
		$paymenttype = $registry->paymenttypes[$payment->paymenttype];
		$payment->duedatestr = $paymenttype;
		
		if ($payment->paymenttype == Collections::PAYMENTTYPE_BANKACCOUNT) {
			$payment->duedatestr = $payment->duedatestr . ", eräpäivä " . sqlDateToStr($payment->duedate);
		}
	}		

	if ($payment->paymentstatus == Collections::PAYMENTSTATUS_PAID) {
		
		$paidtypes = Collections::getPaymentPaidTypes();
		$payment->duedatestr = $paidtypes[$payment->paymenttype];
		if ($payment->paymenttype == Collections::PAYMENTTYPE_BANKACCOUNT) {
			$payment->duedatestr = $payment->duedatestr . " " . sqlDateToStr($payment->paymentdate);
		}
	}
	
	
	if ($payment->paymentstatus == Collections::PAYMENTSTATUS_PAID) $paid++;
	if ($payment->paymentstatus == Collections::PAYMENTSTATUS_CONFIRMED) $paid++;
}

//echo "<br>Paid - " . $paid;

//if ($open > 0) {
	$column = new UISortColumn("Eräpäivä", "duedatestr");
	//$column->setFormatter(Column::COLUMNTYPE_DATE);
	$paymentstable->addColumn($column);
//}



$column = new UISortColumn("Luontipäivä", "amount");
//$column->setFormatter(Column::COLUMNTYPE_FLOAT);
$paymentstable->addColumn($column);



//$column = new UISelectColumn("Tila", null, "paymentstatus", $registry->paymentstatuses);
//$paymentstable->addColumn($column);


/*
if ($paid > 0) {
	//echo "<br>xxxPaid - " . $paid;
	$column = new UISortColumn("Maksupäivä", "paymentdate");
	$column->setFormatter(Column::COLUMNTYPE_DATE);
	$paymentstable->addColumn($column);
}
* /

$column = new UIHiddenColumn("PaymentID", "paymentID");
$paymentstable->addColumn($column);

$paymentstable->setData($registry->payments);
$paymentstable->show();

*/



$managementSection = new UISection("Hallinta", "700px");
$managementSection->editable(false);
$managementSection->setDebug(true);
$managementSection->setOpen(false);



if ($registry->purchase->state == 4) {
	
	if ($registry->purchase->paymenttype == Collections::PAYMENTTYPE_CASH) {
		$field = new UIFixedTextField("Tila", "Maksettu käteiskassasta");
		$managementSection->addField($field);
	}
	if ($registry->purchase->paymenttype == Collections::PAYMENTTYPE_DEPTHS) {
		$field = new UIFixedTextField("Tila", "Siirretty velkoihin");
		$managementSection->addField($field);
	}
	if ($registry->purchase->paymenttype == Collections::PAYMENTTYPE_BANKACCOUNT) {
		$field = new UIFixedTextField("Tila", "Maksettu pankkitililtä, kohdistettu tiliotteelta");
		$managementSection->addField($field);
	}
	if ($registry->purchase->paymenttype == Collections::PAYMENTTYPE_SALARY) {
		$field = new UIFixedTextField("Tila", "Maksettu palkan yhteydessä");
		$managementSection->addField($field);
	}
	if ($registry->purchase->paymenttype == Collections::PAYMENTTYPE_UNKNOWN) {
		$field = new UIFixedTextField("Tila", "Merkitty maksetuksi");
		$managementSection->addField($field);
	}
	
	
	// Tämän pitäisi näkyä ehkä vain adminille
	$field = new UIFixedTextField("Payment ReceiptID", $registry->purchase->paymentreceiptID, getUrl("accounting/receipts/showreceipt") . "&id=" .  $registry->purchase->paymentreceiptID);
	$managementSection->addField($field);
	
	$field = new UIDateField("Maksupäivä","paymentdate","paymentdate");
	$managementSection->addField($field);
	
} else {
	$field = new UISelectField("Tila","state","state", $registry->purchasestates);
	$managementSection->addField($field);	
}

$button = new UIButton(UIComponent::ACTION_FORWARD, "accounting/purchases/removepurchase&purchaseID=" . $registry->purchase->purchaseID, "Poista ostolasku");
$managementSection->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $copyinvoicesection->getID(), "Kopioi lasku");
$managementSection->addButton($button);

if ($registry->purchase->state == 0) {
	$button = new UIButton(UIComponent::ACTION_FORWARD, "accounting/purchases/acceptpurchase&purchaseID=" . $registry->purchase->purchaseID, "Siirrä hyväksytyksi");
	$managementSection->addButton($button);
}


if ($registry->purchase->state == 1) {
	$button = new UIButton(UIComponent::ACTION_FORWARD, "accounting/purchases/returntoopen&purchaseID=" . $registry->purchase->purchaseID, "Palauta avoimeksi");
	$managementSection->addButton($button);
}


if ($registry->purchase->state == 1) {
	// tämä tulee oikaestaan kohdistuksesta vasta
	$button = new UIButton(UIComponent::ACTION_FORWARD, "accounting/purchases/markaspayed&purchaseID=" . $registry->purchase->purchaseID, "Merkitse maksetuksi");
	$managementSection->addButton($button);
}




if (($registry->purchase->state == 3) || ($registry->purchase->state == 4)) {
	$button = new UIButton(UIComponent::ACTION_FORWARD, "accounting/purchases/returntoopen&purchaseID=" . $registry->purchase->purchaseID, "Palauta avoimeksi");
	$managementSection->addButton($button);
}





// onko enää muita tiloja laskulla, se jää möllöttämään maksettu tilaan. Mitään erillistä arkistointia ei mielestäni tarvita


$managementSection->setData($registry->purchase);
$managementSection->show();



?>