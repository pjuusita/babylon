<?php

echo "<a href='".getUrl('accounting/receipts/showreceipts')."'>Palaa tositelistalle</a><br>";
echo "<br>";
//echo "<h1>" . $registry->company->name . "</h1>";

$section = new UISection("Tosite", '800px');
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'accounting/receipts/updatereceipt', 'receiptID');

//$section->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/receipts/updatereceipt');


if (count($this->registry->dimensions) > 0) {

	foreach($this->registry->dimensions as $index => $dimension) {
		$field = new UISelectField($dimension->name,"dimension" . $dimension->dimensionID,"dimension" . $dimension->dimensionID,$registry->dimensionvalues[$dimension->dimensionID], "name");
		$section->addField($field);
	}
}




$field = new UISelectField("Tositesarja", "receiptsetID", 'receiptsetID', $registry->receiptsets, 'name');
$section->addField($field);

$field = new UITextField("Tositenumero", "receiptnumber", 'receiptnumber');
$section->addField($field);

//$field = new UISelectField("Kustannuspaikka", "costpoolID", 'costpoolID', $registry->costpools, 'name');
//$section->addField($field);

//$field = new UISelectField("Maksun saaja", "receiverID", 'receiverID', $registry->receivers, 'name');
//$section->addField($field);

// Selite voitaisiin napata kyseisen laskuttajan olemassaolevista selityksistä
// Tästä saattaa olla jokin esimerkki jossain vastaava toteutus
$nimifield = new UITextField("Selite", "explanation", 'explanation');				// Tämä voisi olla ennakoivaa tekstinsyöttöä pudotusvalikko, jos ei löydy lisätään uusi
$section->addField($nimifield);


$field = new UIDateField("Tositepäivä", "receiptdate", 'receiptdate');
$section->addField($field);

//$field = new UIDateField("Maksupäivä", "paymentdate", 'paymentdate');
//$section->addField($field);


$field = new UITextField("Debet", "debet", 'debet');
$section->addField($field);

$field = new UITextField("Credit", "credit", 'credit');
$section->addField($field);

if ($registry->receipt->purchaseID > 0) {
	// pitäisi muodostaa linkiksi
	//$field = new UITextField("Ostolasku", "purchaseID", 'purchaseID');
	//$section->addField($field);
	
	$field = new UIFixedTextField("Ostolasku", $registry->receipt->purchaseID, getUrl("accounting/purchases/showpurchase") . "&id=" .  $registry->receipt->purchaseID);
	$section->addField($field);
	
}

if ($registry->receipt->paycheckID > 0) {
	// pitäisi muodostaa linkiksi
	//$field = new UITextField("Ostolasku", "purchaseID", 'purchaseID');
	//$section->addField($field);

	$field = new UIFixedTextField("Palkkalaskelma", $registry->receipt->paycheckID, getUrl("payroll/payroll/showpaycheck") . "&id=" .  $registry->receipt->paycheckID);
	$section->addField($field);
	
}



$field = new UISelectField("Kohdistus", "receipttype", 'receipttype', $registry->receipttypes);
$section->addField($field);

if ($registry->receipt->receipttype == Collections::RECEIPTTYPE_NONE) {
	
}

if ($registry->receipt->receipttype == Collections::RECEIPTTYPE_PAYABLE) {
	$field = new UISelectField("Toimittaja", "supplierID", 'supplierID', $registry->suppliers, 'name');
	$field->setPredictable(true);
	$section->addField($field);
}

if ($registry->receipt->receipttype == Collections::RECEIPTTYPE_RECEIVABLE) {
	$field = new UISelectField("Asiakas", "clientID", 'clientID', $registry->clients, 'name');
	$section->addField($field);
}

if ($registry->receipt->receipttype == Collections::RECEIPTTYPE_PAYROLL) {
	// Tämä listahan sisältää varsinaisten työntekijöiden lisäksi myös johtajat, omistajat, hallituksen jäsenet jne.
	// personID olisi siis parempi... ehkä mukana myös esim. pelkkiä työnhakijoita
	$field = new UISelectField("Työntekijä", "workerID", 'workerID', $registry->workers, 'name');
	$section->addField($field);	
}

if ($registry->receipt->receipttype == Collections::RECEIPTTYPE_OTHER) {

}
$section->setData($registry->receipt);
$section->show();





$insertsimpleentry = new UISection("Viennin lisäys");
$insertsimpleentry->setDialog(true);
$insertsimpleentry->setMode(UIComponent::MODE_INSERT);
$insertsimpleentry->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/receipts/insertentry&receiptID=' . $registry->receipt->receiptID);

if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$variable = "dimension" . $dimension->dimensionID;
		$field = new UISelectField($dimension->name, $variable, $variable, $registry->dimensionvalues[$dimension->dimensionID], "name");
		$insertsimpleentry->addField($field);
	}
}

$field = new UISelectField("Tili","accountID","accountID",$registry->accounts, "fullname");
$field->setPredictive(true);
$insertsimpleentry->addField($field);

//$field = new UIDateField("Vientipäivä", "entrydate", 'entrydate');
//$insertsimpleentry->addField($field);

//$field = new UISelectField("ALV-koodi","vatcodeID","vatcodeID",$registry->vatcodes, "name");
//$field->acceptempty(false);
//$insertsimpleentry->addField($field);

$field = new UITextField("Debet", "debet", 'debet');
$insertsimpleentry->addField($field);

$field = new UITextField("Credit", "credit", 'credit');
$insertsimpleentry->addField($field);

//$field = new UITextField("Määrä", "amount", 'amount');
//$insertsimpleentry->addField($field);



$empty = new Row();
$entrysum = 0;
foreach($registry->entries as $index => $entry) {
	$entrysum = $entrysum + $entry->amount;
}
if ($entrysum > 0) {
	$empty->credit = $entrysum;
} 
if ($entrysum < 0) {
	$empty->debet = -1 * $entrysum;
}
$empty->vatcodeID = 0;


if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$variable = "dimension" . $dimension->dimensionID;
		$empty->$variable = $registry->receipt->$variable;
	}
}

$insertsimpleentry->setData($empty);
$insertsimpleentry->show();





/*
$editentry = new UISection("Viennin muokkaus");
$editentry->setDialog(true);
$editentry->setMode(UIComponent::MODE_INSERT);
$editentry->setUpdateAction(UIComponent::ACTION_FORWARD, 'accounting/receipts/updateentry&receiptID=' . $registry->receipt->receiptID, "entryID");

if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$variable = "dimension" . $dimension->dimensionID;
		$field = new UISelectField($dimension->name, $variable, $variable, $registry->dimensionvalues[$dimension->dimensionID], "name");
		$editentry->addField($field);
	}
}

$field = new UISelectField("Tili","accountID","accountID",$registry->accounts, "fullname");
$field->setPredictive(true);
$editentry->addField($field);

//$field = new UIDateField("Vientipäivä", "entrydate", 'entrydate');
//$editentry->addField($field);

$field = new UISelectField("ALV-koodi","vatcodeID","vatcodeID",$registry->vatcodes, "name");
$field->acceptempty(false);
$editentry->addField($field);

$field = new UITextField("Debet", "debet", 'debet');
$editentry->addField($field);

$field = new UITextField("Credit", "credit", 'credit');
$editentry->addField($field);

$editentry->show();







$entriestable = new UITableSection("Viennit", '800px');
$entriestable->setOpen(true);
$entriestable->setFramesVisible(true);
$entriestable->setShowSumRow(true);

$entriestable->setDeleteAction(UIComponent::ACTION_FORWARD, 'accounting/receipts/removeentry&receiptID=' . $registry->receipt->receiptID, 'entryID');

$entriestable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editentry->getID(), "entryID");



$column = new UISortColumn("#", "entryID", "entryID");
$entriestable->addColumn($column);

if (count($this->registry->dimensions) > 0) {

	foreach($this->registry->dimensions as $index => $dimension) {

		//$field = new UISelectField($dimension->abbreviation,"dimension" . $dimension->dimensionID,"dimension" . $dimension->dimensionID,$registry->dimensionvalues[$dimension->dimensionID], "abbreviation");
		$column = new UISelectColumn($dimension->abbreviation, "name", "dimension" . $dimension->dimensionID, $registry->dimensionvalues[$dimension->dimensionID]);
		$column->setUndefinedString("-");
		$entriestable->addColumn($column);
	}
}


//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $paymententry->getID(), "Lisää kustannus");
//$entriestable->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsimpleentry->getID(), "Lisää vienti");
$entriestable->addButton($button);

//$column = new UISortColumn("Päiväys", "entrydate");
//$column->setFormatter(Column::COLUMNTYPE_DATE);
//$entriestable->addColumn($column);

$column = new UISelectColumn("Tilinro", "number", "accountID", $registry->accounts);
$entriestable->addColumn($column);

$column = new UISelectColumn("Tili", "name", "accountID", $registry->accounts);
$entriestable->addColumn($column);

$column = new UISelectColumn("ALV", "vatcode", "vatcodeID", $registry->vatcodes);
$entriestable->addColumn($column);

$column = new UISortColumn("Debet", "debet", "debet");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$entriestable->addColumn($column);

$column = new UISortColumn("Credit", "credit", "credit");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$entriestable->addColumn($column);

$column = new UIHiddenColumn("AccountID", "accountID", "accountID");
$entriestable->addColumn($column);

$column = new UIHiddenColumn("EnryID", "entryID", "entryID");
$entriestable->addColumn($column);

$entriestable->setData($registry->entries);
$entriestable->show();
*/





$editentryrowdialog = new UISection("Viennin muokkaus");
$editentryrowdialog->setDialog(true);
$editentryrowdialog->setMode(UIComponent::MODE_INSERT);
$editentryrowdialog->setUpdateAction(UIComponent::ACTION_FORWARD, 'accounting/receipts/updateentry&receiptID=' . $registry->receipt->receiptID, "entryID");

$editentrydialogentryidfield = new UITextField("EntryID", "entryID", 'entryID');
$editentryrowdialog->addField($editentrydialogentryidfield);

$editentrydialogentrydatefield = new UIDateField("Päiväys", "entrydate", 'entrydate');
$editentryrowdialog->addField($editentrydialogentrydatefield);

$editentrydialogaccountselect = new UISelectField("Kirjanpitotili","accountID","accountID",$registry->accounts, "fullname");
//$editentrydialogaccountselect->setPredictive(true);
$editentryrowdialog->addField($editentrydialogaccountselect);


$editentrydialogcostpoolselect = new UISelectField("Kustannuspaikka","costpoolID","costpoolID",$registry->costpools, "name");
//$editentrydialogcostpoolselect->setOnChange("editentrycostpoolchanged_" . $editentryrowdialog->getID() . "()");
//$editentrydialogcostpoolselect->setDisabled(true);
$editentryrowdialog->addField($editentrydialogcostpoolselect);

$editentrydialeogcostpooltypeselect = new UISelectField("Kohdetyyppi","costpooltype","costpooltype", $registry->costpooltypes);
$editentrydialeogcostpooltypeselect->setOnChange("editentrycostpooltypechanged_" . $editentryrowdialog->getID() . "()");
//$editentrydialeogcostpooltypeselect->setDisabled(true);
$editentryrowdialog->addField($editentrydialeogcostpooltypeselect);

$editentrydialegcostpooltargetselect = new UISelectField("Kohde","targetID","targetID", array(), "name");
//$editentrydialegcostpooltargetselect->setCustomCheckFunction("checkcostpoolvalue");
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
echo "	function editentrycostpooltypechanged_" . $editentryrowdialog->getID() . "() {";
echo "		console.log('costpoolchanged...');";
echo "	 	var costpooltype = $('#" . $editentrydialeogcostpooltypeselect->getEditFieldID() . "').val();";
echo "		console.log('" . getUrl('accounting/purchases/getcostpooltypeitemsJSON') . "&costpooltype='+costpooltype);";
echo "		$.getJSON('" . getUrl('accounting/purchases/getcostpooltypeitemsJSON') . "&costpooltype='+costpooltype,'',function(data) {";
echo "	 		var valuefieldID = '#".$editentrydialegcostpooltargetselect->getEditFieldID()."';";
echo "			let costpoolitems = data.costpoolitems;";
echo "	 		$(valuefieldID).empty();";
echo "			console.log('len - '+costpoolitems.length);";
echo "			if (costpoolitems.length == 0) {";
echo "				console.log('no expenditures');";
echo "				$(valuefieldID).attr('disabled','disabled');";
echo "				$(valuefieldID).addClass('uitextfield-disabled');";
echo "				$(valuefieldID).removeClass('uitextfield');";
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
echo "			}";
echo "		});";
echo "		console.log('end');";
echo "	}";
echo "</script>";






$insertentrysection = new UISection("Viennin lisäys");
$insertentrysection->setDialog(true);
$insertentrysection->setMode(UIComponent::MODE_INSERT);
$insertentrysection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/receipts/insertentry&receiptID=' . $registry->receipt->receiptID);

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
$empty->entrydate = $registry->receipt->receiptdate;
//$empty->amount =$registry->receipt->grossamount;
$insertentrysection->setData($empty);

$insertentrysection->show();





$width = "1200px";

$entriestable = new UITableSection("Viennit2", $width);
$entriestable->setLineAction(UIComponent::ACTION_JAVASCRIPT, "openEditEntryRow", "entryID");
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertentrysection->getID(), "Lisää vienti");
$entriestable->addButton($button);
$entriestable->setDeleteAction(UIComponent::ACTION_FORWARD, 'accounting/receipts/removeentry&receiptID=' . $registry->receipt->receiptID, 'entryID');




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

$entriestable->setData($registry->entries);
$entriestable->show();





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

echo "		if (costpoolID == 0) {";

echo "			console.log('costpoolnolla');";
echo "	 		var valuefieldID = '#" . $editentrydialegcostpooltargetselect->getEditFieldID() . "';";
echo "			$(valuefieldID).attr('disabled','disabled');";
echo "			$(valuefieldID).addClass('uitextfield-disabled');";
echo "			$(valuefieldID).removeClass('uitextfield');";
echo "	 		$('#" . $editentrydialegcostpooltargetselect->getEditFieldID() . "').val(0);";

echo "	 		var costpooltypefieldID = '#" . $editentrydialeogcostpooltypeselect->getEditFieldID() . "';";
echo "			$(costpooltypefieldID).removeAttr('disabled');";
echo "			$(costpooltypefieldID).addClass('uitextfield');";
echo "			$(costpooltypefieldID).removeClass('uitextfield-disabled');";
echo "	 		$('#" . $editentrydialegcostpooltargetselect->getEditFieldID() . "').val(0);";


echo "		}";


echo "		console.log('" . getUrl('accounting/purchases/getcostpooltypeitemsJSON') . "&costpooltype='+costpooltype);";
echo "		$.getJSON('" . getUrl('accounting/purchases/getcostpooltypeitemsJSON') . "&costpooltype='+costpooltype,'',function(data) {";

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
echo "	 			$('#" . $editentrydialegcostpooltargetselect->getEditFieldID() . "').val(targetID);";
echo "			}";
echo "		});";

echo "  	$('#sectiondialog-" . $editentryrowdialog->getID() . "').dialog('open');";


/*
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
*/

echo "	}";
echo "</script>";






// ------------------------------------------------------------------------



$copydialog = new UISection("Tositteen kopiointi");
$copydialog->setDialog(true);
$copydialog->setMode(UIComponent::MODE_INSERT);
$copydialog->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/receipts/copyreceipt&receiptID=' . $registry->receipt->receiptID);

$field = new UIDateField("Uusi tositepäivä", "newreceiptdate", 'newreceiptdate');
$copydialog->addField($field);

$empty = new Row();
$empty->newreceiptdate =$registry->receipt->receiptdate;
$copydialog->setData($empty);
$copydialog->show();






$managementSection = new UISection("Hallinta", '800px');
$managementSection->editable(false);
$managementSection->setDebug(true);
$managementSection->setOpen(false);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $copydialog->getID(), "Kopioi tosite");
$managementSection->addButton($button);

$button = new UIButton(UIComponent::ACTION_FORWARD, "accounting/receipts/removereceipt&id=".$registry->receipt->receiptID, "Poista tosite");
$managementSection->addButton($button);

$button = new UIButton(UIComponent::ACTION_FORWARD, "accounting/receipts/removereceipt&id=".$registry->receipt->receiptID, "Tarkistettu");
$managementSection->addButton($button);

$managementSection->show();



?>