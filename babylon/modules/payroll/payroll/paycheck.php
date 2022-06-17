<?php

echo "<a href='".getUrl('payroll/payroll/showpayroll')."'>Palaa palkkalistalle</a><br>";
echo "<br>";
//echo "<h1>" . $registry->company->name . "</h1>";



if ($this->registry->errors != null) {
	foreach($this->registry->errors as $index => $errormessage) {
		echo "<table cellpadding='0' cellpadding='0'  style='width:600px;margin-bottom:5px;'>";
		echo "	<tr>";
		echo "		<td style='width:100%;'>";
		//echo "			<div class=errordiv id='sectionerrordiv-" . $this->getID() . "' style='display:none'></div>";
		echo "			<div class=errormessagediv>";
		echo "" . $errormessage;
		echo "			</div>";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
	}
}


$section = new UISection("Palkkalaskelma");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'payroll/payroll/updatepaycheck', 'paycheckID');

/*
if (count($this->registry->dimensions) > 0) {

	foreach($this->registry->dimensions as $index => $dimension) {
		$field = new UISelectField($dimension->name,"dimension" . $dimension->dimensionID,"dimension" . $dimension->dimensionID,$registry->dimensionvalues[$dimension->dimensionID], "name");
		$section->addField($field);
	}
}
*/

$button = new UIButton(UIComponent::ACTION_NEWWINDOW, "payroll/payroll/paycheckpdf&paycheckID=" . $registry->paycheck->paycheckID, "Esikatselu");
$section->addButton($button);

$button = new UIButton(UIComponent::ACTION_FORWARD, 'payroll/payroll/updatepaychecktotals&paycheckID=' . $registry->paycheck->paycheckID, "Päivitä");
$section->addButton($button);

$field = new UISelectField("Työnantaja","companyID","companyID",$registry->companies, 'name');
$section->addField($field);

$field = new UIFixedTextField("Henkilö",$this->registry->person->lastname . "  ". $this->registry->person->firstname);
$section->addField($field);

//$field = new UISelectField("Työntekijä","workerID","workerID",$registry->workers, 'fullname');
//$section->addField($field);

//$this->registry->person


$field = new UISelectField("Työsuhde","workcontractID","workcontractID",$registry->workcontracts, 'name');
$section->addField($field);


//$field = new UISelectField("Työsopimus","pensioninsurancetypeID","pensioninsurancetypeIDID",$registry->pensioninsurancetypes);
//$section->addField($field);

//echo "<br>pension - " . $registry->paycheck->pensioninsurancetypeID;
if (!isset($registry->pensioninsurancetypes[$registry->paycheck->pensioninsurancetypeID])) {
	$pensioninsurance = "-";
} else {
	$pensioninsurance = $registry->pensioninsurancetypes[$registry->paycheck->pensioninsurancetypeID];
}

$field = new UIFixedTextField("Vakuutus",$pensioninsurance);
$section->addField($field);


//$field = new UISelectField("Työehtosopimus","labouragreementID","labouragreementID",$registry->labouragreements, 'abbreviation');
//$section->addField($field);

$field = new UISelectField("Palkkakausi","payrollperiodID","payrollperiodID",$registry->payrollperiods, 'name');
$section->addField($field);

$field = new UIDateField("Palkkakausi alku", "startdate", 'startdate');
$section->addField($field);

$field = new UIDateField("Palkkakausi loppu", "enddate", 'enddate');
$section->addField($field);

$field = new UIDateField("Maksupäivä", "paymentdate", 'paymentdate');
$section->addField($field);

$field = new UIDateField("Kirjanpitopäivä", "bookkeepingdate", 'bookkeepingdate'); // Tätä ei tarvita, mikäli bookkeeping osio ei ole käytössä
$section->addField($field);

if ($registry->paycheck->receiptID > 0) {
	$field = new UIFileField("Tosite","paycheckID","payroll/payroll/uploadpaycheck", "files", "payroll/payroll/downloadpaycheck");
	$field->setRemoveAction("payroll/payroll/removepaycheckattachment&paycheckID=" . $registry->paycheck->paycheckID);
	$section->addField($field);
}

if ($registry->taxcard != null) {
	$taxcardstring = number_format($registry->taxcard->percent1,2,",","") . "% / " . number_format($registry->taxcard->percent2,2,",","") . "%, " . number_format($registry->taxcard->taxlimit,2,",","") . "€";
	$field = new UIFixedTextField("Verokortti", $taxcardstring);
	$section->addField($field);
}

$field = new UIFixedTextField("ReceiptID", $registry->paycheck->receiptID);
$section->addField($field);

$field = new UIFixedTextField("Sivukulut", $registry->paycheck->sidecost);
$section->addField($field);

$field = new UIFixedTextField("Kokonaiskulut", $registry->paycheck->totalcost);
$section->addField($field);

$field = new UIFixedTextField("Buttopalkka", $registry->paycheck->grossamount);
$section->addField($field);

$field = new UIFixedTextField("<b>Maksetaan</b>", "<b>" . $registry->paycheck->netamount . "</b>");
$section->addField($field);

$field = new UISelectField("Tila","state","state",$registry->paycheckstates);
$section->addField($field);

/*
if ($registry->paycheck->state == 0) {
	$field = new UIFixedTextField("Tila", 'Avoin1');
	$section->addField($field);
} else
	if ($registry->paycheck->state == 1) {
		$field = new UIFixedTextField("Tila", 'Hyväksytty2');
		$section->addField($field);
	} else {
		$field = new UIFixedTextField("Tila", 'Tuntematon3');
		$section->addField($field);
	}
*/	

	
$section->setData($registry->paycheck);
$section->show();






$insertpaycheckrow = new UISection("Palkkarivin lisäys");
$insertpaycheckrow->setDialog(true);
$insertpaycheckrow->setMode(UIComponent::MODE_INSERT);
$insertpaycheckrow->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payroll/insertpaycheckrow&paycheckID=' . $registry->paycheck->paycheckID . '&workerID=' . $registry->paycheck->workerID);


/*
$defaultinvoicerow = new Row();
if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$variable = "dimension" . $dimension->dimensionID;
		$field = new UISelectField($dimension->name, $variable, $variable, $registry->dimensionvalues[$dimension->dimensionID], "name");
		$insertpaycheckrow->addField($field);
		$defaultinvoicerow->$variable = $registry->paycheck->$variable;
	}
}
$insertpaycheckrow->setData($defaultinvoicerow);
*/

$salarytypefield = new UISelectField("Palkkalaji","salarytypeID","salarytypeID",$registry->salarytypes, "name");
$insertpaycheckrow->addField($salarytypefield);

$insertamountfield = new UITextField("Määrä", "amount", 'amount');
$insertamountfield->setOnBlur("amount_" . $insertamountfield->getID() . "()");
$insertpaycheckrow->addField($insertamountfield);

$insertpricefield = new UITextField("Palkka", "unitprice", 'unitprice');
$insertpricefield->setOnBlur("unitprice_" . $insertpricefield->getID() . "()");
$insertpaycheckrow->addField($insertpricefield);

$inserttotalfield = new UITextField("Summa", "total", 'total');
$insertpaycheckrow->addField($inserttotalfield);



echo "<script>";
echo "	function amount_" . $insertamountfield->getID() . "() {";
echo "		console.log('amount changed');";
echo "		updateinsertamounts();";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function unitprice_" . $insertpricefield->getID() . "() {";
echo "		console.log('unitprice changed');";
echo "		updateinsertamounts();";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function updateinsertamounts() {";
echo "		var vatpercent = 0;";
echo "	 	var amount= $('#" . $insertamountfield->getEditFieldID() . "').val();";
echo "	 	var unitprice = $('#" . $insertpricefield->getEditFieldID() . "').val();";
echo "		console.log('amount -'+amount);";
echo "		console.log('unitprice - '+unitprice);";
echo "		var total = unitprice * amount;";
echo "		console.log('-- total - '+total);";
echo "		$('#" . $inserttotalfield->getEditFieldID() . "').val(total.toFixed(2));";
echo "	}";
echo "</script>";


$insertpaycheckrow->show();




$editpaycheckrow = new UISection("Palkkarivin muokkaus");
$editpaycheckrow->setDialog(true);
$editpaycheckrow->setMode(UIComponent::MODE_INSERT);
$editpaycheckrow->setUpdateAction(UIComponent::ACTION_FORWARD, 'payroll/payroll/updatepaycheckrow&paycheckID=' . $registry->paycheck->paycheckID, "rowID");


/*
if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$variable = "dimension" . $dimension->dimensionID;
		$field = new UISelectField($dimension->name, $variable, $variable, $registry->dimensionvalues[$dimension->dimensionID], "name");
		$editpaycheckrow->addField($field);
	}
}
*/

//$field = new UIDateField("Maksupäivä", "paymentdate", 'paymentdate');
//$editpaycheckrow->addField($field);

$salarytypefield = new UISelectField("Palkkalaji","salarytypeID","salarytypeID",$registry->salarytypes, "name");
$editpaycheckrow->addField($salarytypefield);

$insertamountfield = new UITextField("Määrä", "amount", 'amount');
$insertamountfield->setOnBlur("amount_" . $insertamountfield->getID() . "()");
$editpaycheckrow->addField($insertamountfield);

$insertpricefield = new UITextField("Palkka", "unitprice", 'unitprice');
$insertpricefield->setOnBlur("unitprice_" . $insertpricefield->getID() . "()");
$editpaycheckrow->addField($insertpricefield);

$inserttotalfield = new UITextField("Summa", "total", 'total');
$editpaycheckrow->addField($inserttotalfield);

$editpaycheckrow->show();




echo "<script>";
echo "	function amount_" . $insertamountfield->getID() . "() {";
echo "		console.log('amount changed');";
echo "		updateeditamounts();";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function unitprice_" . $insertpricefield->getID() . "() {";
echo "		console.log('unitprice changed');";
echo "		updateeditamounts();";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function updateeditamounts() {";
echo "		var vatpercent = 0;";
echo "	 	var amount= $('#" . $insertamountfield->getEditFieldID() . "').val();";
echo "	 	var unitprice = $('#" . $insertpricefield->getEditFieldID() . "').val();";
echo "		console.log('amount -'+amount);";
echo "		console.log('unitprice - '+unitprice);";
echo "		var total = unitprice * amount;";
echo "		console.log('-- total - '+total);";
echo "		$('#" . $inserttotalfield->getEditFieldID() . "').val(total.toFixed(2));";
echo "	}";
echo "</script>";




$paycheckrowstable = new UITableSection("Palkkarivit", '600px');
$paycheckrowstable->setOpen(true);
$paycheckrowstable->setFramesVisible(true);
$paycheckrowstable->setShowSumRow(true);

$paycheckrowstable->setDeleteAction(UIComponent::ACTION_FORWARD, 'payroll/payroll/removepaycheckrow&paycheckID=' . $registry->paycheck->paycheckID, 'rowID');

$paycheckrowstable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editpaycheckrow->getID(), "rowID");

//$button = new UIButton(UIComponent::ACTION_FORWARD, 'payroll/payroll/updatedeductions&paycheckID=' . $registry->paycheck->paycheckID, "Päivitä");
//$paycheckrowstable->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertpaycheckrow->getID(), "Lisää palkkarivi");
$paycheckrowstable->addButton($button);

$column = new UISortColumn("#", "rowID", "rowID");
$paycheckrowstable->addColumn($column);



/*
if (count($this->registry->dimensions) > 0) {

	foreach($this->registry->dimensions as $index => $dimension) {

		//$field = new UISelectField($dimension->abbreviation,"dimension" . $dimension->dimensionID,"dimension" . $dimension->dimensionID,$registry->dimensionvalues[$dimension->dimensionID], "abbreviation");
		$column = new UISelectColumn($dimension->abbreviation, "name", "dimension" . $dimension->dimensionID, $registry->dimensionvalues[$dimension->dimensionID]);
		$column->setUndefinedString("-");
		$paycheckrowstable->addColumn($column);
	}
}
*/


$column = new UISelectColumn("Palkkalaji", "name", "salarytypeID", $registry->salarytypes);
$paycheckrowstable->addColumn($column);

$column = new UISortColumn("Määrä", "amount", "amount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$paycheckrowstable->addColumn($column);

$column = new UISortColumn("Palkka", "unitprice", "unitprice");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$paycheckrowstable->addColumn($column);

$column = new UISortColumn("Summa", "total", "total");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$paycheckrowstable->addColumn($column);

$salaryrows = array();
foreach($registry->paycheckrows as $index => $row) {
	if (($row->salarycategoryID == 1) || ($row->salarycategoryID == 2)) {
		$salaryrows[$row->rowID] = $row;
	}
}

$paycheckrowstable->setData($salaryrows);

$paycheckrowstable->show();




/*

$insertdeductionrow = new UISection("Vähennyksen lisäys");
$insertdeductionrow->setDialog(true);
$insertdeductionrow->setMode(UIComponent::MODE_INSERT);
$insertdeductionrow->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payroll/insertpaycheckrow&paycheckID=' . $registry->paycheck->paycheckID . '&workerID=' . $registry->paycheck->workerID);

$deductionsalarytypes = array();
foreach($registry->salarytypes as $index => $salarytype) {
	if ($salarytype->salarycategoryID == Collections::SALARYCATEGORY_DEDUCTION) $deductionsalarytypes[$salarytype->salarytypeID] = $salarytype;
}

$salarytypefield = new UISelectField("Palkkalaji","salarytypeID","salarytypeID",$deductionsalarytypes, "name");
$insertdeductionrow->addField($salarytypefield);

$insertamountfield = new UITextField("Määrä", "amount", 'amount');
$insertamountfield->setOnBlur("deductionamount_" . $insertamountfield->getID() . "()");
$insertdeductionrow->addField($insertamountfield);

$insertpricefield = new UITextField("Palkka", "unitprice", 'unitprice');
$insertpricefield->setOnBlur("deductionunitprice_" . $insertpricefield->getID() . "()");
$insertdeductionrow->addField($insertpricefield);

$inserttotalfield = new UITextField("Summa", "total", 'total');
$insertdeductionrow->addField($inserttotalfield);

$insertdeductionrow->show();




echo "<script>";
echo "	function deductionamount_" . $insertamountfield->getID() . "() {";
echo "		console.log('amount changed');";
echo "		updatedeductionamounts();";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function deductionunitprice_" . $insertpricefield->getID() . "() {";
echo "		console.log('unitprice changed');";
echo "		updatedeductionamounts();";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function updatedeductionamounts() {";
echo "		var vatpercent = 0;";
echo "	 	var amount= $('#" . $insertamountfield->getEditFieldID() . "').val();";
echo "	 	var unitprice = $('#" . $insertpricefield->getEditFieldID() . "').val();";
echo "		console.log('amount -'+amount);";
echo "		console.log('unitprice - '+unitprice);";
echo "		var total = unitprice * amount;";
echo "		console.log('-- total - '+total);";
echo "		$('#" . $inserttotalfield->getEditFieldID() . "').val(total.toFixed(2));";
echo "	}";
echo "</script>";

*/


$editdeductionrow = new UISection("Vähennyksen muokkaus");
$editdeductionrow->setDialog(true);
$editdeductionrow->setMode(UIComponent::MODE_INSERT);
$editdeductionrow->setUpdateAction(UIComponent::ACTION_FORWARD, 'payroll/payroll/updatedeductionrow&paycheckID=' . $registry->paycheck->paycheckID, "rowID");

$insertamountfield = new UITextField("Määrä", "amount", 'amount');
$editdeductionrow->addField($insertamountfield);

$inserttotalfield = new UITextField("Summa", "total", 'total');
$editdeductionrow->addField($inserttotalfield);

$editdeductionrow->show();



$paycheckrowstable = new UITableSection("Vähennykset", '600px');
$paycheckrowstable->setOpen(true);
$paycheckrowstable->setFramesVisible(true);
$paycheckrowstable->setShowSumRow(true);


//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertdeductionrow->getID(), "Lisää vähennys");
//$paycheckrowstable->addButton($button);


//$paycheckrowstable->setDeleteAction(UIComponent::ACTION_FORWARD, 'payroll/payroll/removepaycheckrow&paycheckID=' . $registry->paycheck->paycheckID, 'rowID');

$paycheckrowstable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdeductionrow->getID(), "rowID");

$column = new UISortColumn("#", "rowID", "rowID");
$paycheckrowstable->addColumn($column);


$column = new UISelectColumn("Vähennys", "name", "deductionID", $registry->deductions);
$paycheckrowstable->addColumn($column);

/*
$column = new UISortColumn("Määrä", "amount", "amount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$paycheckrowstable->addColumn($column);
*/


$column = new UISortColumn("Prosentti", "unitprice", "unitprice");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$paycheckrowstable->addColumn($column);

$column = new UISortColumn("Summa", "total", "total");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$paycheckrowstable->addColumn($column);


$deductionrows = array();
foreach($registry->paycheckrows as $index => $row) {
	if (($row->salarycategoryID == Collections::SALARYCATEGORY_DEDUCTION)) {
		$deductionrows[] = $row;
	}
	//echo "<br>Row - " . $row->salarycategoryID;
}

//echo "<br>Vähennykset count - " . count($deductionrows);
//echo "<br>paycheckrows count - " . count($registry->paycheckrows);

$paycheckrowstable->setData($deductionrows);

$paycheckrowstable->show();



/*
$column = new UISelectColumn("Vähennys", "name", "deductionID", $registry->deductions);
$paycheckrowstable->addColumn($column);
*/


/*
 $column = new UISortColumn("Määrä", "amount", "amount");
 $column->setAlign(Column::ALIGN_RIGHT);
 $column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
 $paycheckrowstable->addColumn($column);
*/

/*
$column = new UISortColumn("Prosentti", "unitprice", "unitprice");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$paycheckrowstable->addColumn($column);

$column = new UISortColumn("Summa", "total", "total");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$paycheckrowstable->addColumn($column);

$deductionrows = array();
foreach($registry->paycheckrows as $index => $row) {
	if (($row->salarycategoryID == Collections::SALARYCATEGORY_DEDUCTION)) {
		$deductionrows[] = $row;
	}
	//echo "<br>Row - " . $row->salarycategoryID;
}

//echo "<br>Vähennykset count - " . count($deductionrows);
//echo "<br>paycheckrows count - " . count($registry->paycheckrows);

$paycheckrowstable->setData($deductionrows);

$paycheckrowstable->show();
*/




$expenserows = array();
foreach($registry->paycheckrows as $index => $row) {
	if (($row->salarycategoryID == Collections::SALARYCATEGORY_EXPENSE)) {
		$expenserows[] = $row;
	}
}

$paycheckrowstable = new UITableSection("Työnantajan sivukulut", '600px');
$paycheckrowstable->setOpen(true);
$paycheckrowstable->setFramesVisible(true);
$paycheckrowstable->setShowSumRow(true);

//if ((count($expenserows) == 0) && ($registry->paycheck->state > 0)) {
if ($registry->paycheck->state > 0) {
	$button = new UIButton(UIComponent::ACTION_FORWARD, 'payroll/payroll/updateexpenserows&paycheckID=' . $registry->paycheck->paycheckID, "Päivitä");
	$paycheckrowstable->addButton($button);
}
//$paycheckrowstable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editpaycheckrow->getID(), "rowID");

$column = new UISortColumn("#", "rowID", "rowID");
$paycheckrowstable->addColumn($column);

$column = new UISelectColumn("Sivukulu", "name", "deductionID", $registry->deductions);
$paycheckrowstable->addColumn($column);

$column = new UISortColumn("Prosentti", "unitprice", "unitprice");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$paycheckrowstable->addColumn($column);

$column = new UISortColumn("Summa", "total", "total");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$paycheckrowstable->addColumn($column);

$paycheckrowstable->setData($expenserows);
$paycheckrowstable->show();





if ($registry->paycheck->state > 0) {
	


	$editentryrow = new UISection("Viennin muokkaus");
	$editentryrow->setDialog(true);
	$editentryrow->setMode(UIComponent::MODE_INSERT);
	$editentryrow->setUpdateAction(UIComponent::ACTION_FORWARD, 'payroll/payroll/updateentry&paycheckID=' . $registry->paycheck->paycheckID . '&receiptID=' . $registry->receipt->receiptID, "rowID");
	
	$field = new UITextField("EntryID", "entryID", 'entryID');
	$editentryrow->addField($field);
	
	$field = new UIDateField("Päiväys", "entrydate", 'entrydate');
	$editentryrow->addField($field);
	
	$field = new UISelectField("Kirjanpitotili","accountID","accountID",$registry->accounts, "fullname");
	$editentryrow->addField($field);
	
	//$alvfield = new UISelectField("ALV-Koodi","vatcodeID","vatcodeID",$registry->vatcodes, "name");
	//$editentryrow->addField($alvfield);
	
	$insertgrossfield = new UITextField("Debet", "debet", 'debet');
	$editentryrow->addField($insertgrossfield);
	
	$insertnetfield = new UITextField("Credit", "credit", 'credit');
	$editentryrow->addField($insertnetfield);
	
	$editentryrow->show();
	
	
	
	
	$insertentrysection = new UISection("Viennin lisäys");
	$insertentrysection->setDialog(true);
	$insertentrysection->setMode(UIComponent::MODE_INSERT);
	$insertentrysection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payroll/insertentry&paycheckID=' . $registry->paycheck->paycheckID . '&receiptID=' . $registry->receipt->receiptID);
	
	//$field = new UIDateField("Päiväys", "entrydate", 'entrydate');
	//$insertentrysection->addField($field);
	
	$field = new UISelectField("Tili","accountID","accountID",$registry->accounts, "fullname");
	$field->setPredictive(true);
	$insertentrysection->addField($field);
	
	//$field = new UISelectField("Vatcode","vatcodeID","vatcodeID",$registry->vatcodes, "vatcode");
	//$insertentrysection->addField($field);
	
	$field = new UITextField("Amount", "amount", 'amount');
	$insertentrysection->addField($field);
	
	//$field = new UITextField("Debet", "debet", 'debet');
	//$insertentrysection->addField($field);
	
	//$field = new UITextField("Credit", "credit", 'credit');
	//$insertentrysection->addField($field);
	
	$empty = new Row();
	$empty->entrydate =$registry->receipt->receiptdate;
	$empty->amount =$registry->receipt->grossamount;
	
	$insertentrysection->setData($empty);
	$insertentrysection->show();
}



if ($registry->paycheck->state == 0) {
	$entriestable = new UITableSection("Viennit (alustavat)", '600px');
} else {
	$entriestable = new UITableSection("Viennit", '600px');
	$entriestable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editentryrow->getID(), "rowID");
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertentrysection->getID(), "Lisää vienti");
	$entriestable->addButton($button);
}
$entriestable->setOpen(true);
$entriestable->setFramesVisible(true);
$entriestable->setShowSumRow(true);


$column = new UISortColumn("Päiväys", "entrydate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$entriestable->addColumn($column);

/*
if (count($this->registry->dimensions) > 0) {

	foreach($this->registry->dimensions as $index => $dimension) {

		//$field = new UISelectField($dimension->abbreviation,"dimension" . $dimension->dimensionID,"dimension" . $dimension->dimensionID,$registry->dimensionvalues[$dimension->dimensionID], "abbreviation");
		$column = new UISelectColumn($dimension->abbreviation, "name", "dimension" . $dimension->dimensionID, $registry->dimensionvalues[$dimension->dimensionID]);
		$column->setUndefinedString("-");
		$entriestable->addColumn($column);
	}
}
*/

$column = new UISelectColumn("Tilinro", "number", "accountID", $registry->accounts);
$entriestable->addColumn($column);

$column = new UISelectColumn("Tili", "name", "accountID", $registry->accounts);
$entriestable->addColumn($column);

//$column = new UISelectColumn("ALV", "vatcode", "vatcodeID", $registry->vatcodes);
//$entriestable->addColumn($column);

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



if ($registry->paycheck->state > 0) {

	$entriestable = new UITableSection("Viennit (automatic)", '600px');
	$entriestable->setOpen(true);
	$entriestable->setFramesVisible(true);
	$entriestable->setShowSumRow(true);
	$entriestable->setLineBackground("color");
	
	$column = new UISortColumn("Päiväys", "entrydate");
	$column->setFormatter(Column::COLUMNTYPE_DATE);
	$entriestable->addColumn($column);

	$column = new UISelectColumn("Tilinro", "number", "accountID", $registry->accounts);
	$entriestable->addColumn($column);
	
	$column = new UISelectColumn("Tili", "name", "accountID", $registry->accounts);
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
	
	$entriestable->setData($registry->autoentries);
	$entriestable->show();
	
}

/*
$copyinvoicesection = new UISection('Ostolaskun kopiointi','550px');
$copyinvoicesection->setDialog(true);
$copyinvoicesection->setMode(UIComponent::MODE_INSERT);
$copyinvoicesection->setSaveAction(UIComponent::ACTION_FORWARD, "payroll/payroll/copypaycheck&paycheckID=" . $registry->purchase->paycheckID);

$field = new UISelectField("Maksun saaja","supplierID","supplierID",$registry->suppliers, "name");
$copyinvoicesection->addField($field);

$field = new UISelectField("Maksutapa","paymentmethodID","paymentmethodID", $registry->paymentmethods, "name");
$copyinvoicesection->addField($field);

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
*/


$managementSection = new UISection("Hallinta");
$managementSection->editable(false);
$managementSection->setDebug(true);
$managementSection->setOpen(false);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $copyinvoicesection->getID(), "Kopioi lasku");
//$managementSection->addButton($button);

if ($registry->paycheck->state == 0) {
	
	$button = new UIButton(UIComponent::ACTION_FORWARD, "payroll/payroll/removepaycheck&paycheckID=" . $registry->paycheck->paycheckID, "Poista palkkalaskelma");
	$managementSection->addButton($button);
	
	$button = new UIButton(UIComponent::ACTION_FORWARD, "payroll/payroll/acceptpaycheck&paycheckID=" . $registry->paycheck->paycheckID, "Hyväksy palkkalaskelma");
	$managementSection->addButton($button);
}


if ($registry->paycheck->state == 1) {
	$button = new UIButton(UIComponent::ACTION_FORWARD, "payroll/payroll/returntoopen&paycheckID=" . $registry->paycheck->paycheckID, "Palauta avoimeksi");
	$managementSection->addButton($button);
	
	$button = new UIButton(UIComponent::ACTION_FORWARD, "payroll/payroll/markaspaid&paycheckID=" . $registry->paycheck->paycheckID, "Merkitse maksetuksi");
	$managementSection->addButton($button);
}


/*
if ($registry->invoice->state == 1) {
	$button = new UIButton(UIComponent::ACTION_FORWARD, "sales/invoices/sendinvoice", "Merkitse lähetetyksi");
	$managementSection->addButton($button);

	$button = new UIButton(UIComponent::ACTION_FORWARD, "sales/invoices/openinvoice&invoiceID=" . $registry->invoice->invoiceID, "Palauta avoimeksi");
	$managementSection->addButton($button);
}

if ($registry->invoice->state == 2) {
	// tämä tulee oikaestaan kohdistuksesta vasta
	$button = new UIButton(UIComponent::ACTION_FORWARD, "sales/invoices/payedinvoice", "Merkitse maksetuksi");
	$managementSection->addButton($button);
}
*/

// onko enää muita tiloja laskulla, se jää möllöttämään maksettu tilaan. Mitään erillistä arkistointia ei mielestäni tarvita

$managementSection->show();



?>