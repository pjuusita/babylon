<?php



echo "<select id=periodselectfield class='top-select' style='width:150px;margin-right:5px;margin-bottom:15px;'>";
foreach($this->registry->periods as $index => $period) {
	if ($this->registry->periodID ==  $period->periodID) {
		echo "<option selected='selected' value='" . $index . "'>" . $period->fullname . "</option>";
	} else {
		echo "<option value='" . $index . "'>" . $period->fullname . "</option>";
	}
}
echo "</select>";

echo "	<script>";
echo "		$('#periodselectfield').on('change', function() {";
echo "			window.location='".getUrl('payroll/payroll/showpayroll')."&periodID='+this.value;";
echo "		});";
echo "	</script>";





$insertpaychecksection = new UISection("Palkkalaskelman lisäys");
$insertpaychecksection->setDialog(true);
$insertpaychecksection->setMode(UIComponent::MODE_INSERT);
$insertpaychecksection->setSaveAction(UIComponent::ACTION_FORWARD, 'payroll/payroll/insertpaycheck');

$workerfield = new UISelectField("Työntekijät","workerID","workerID",$registry->workers, 'fullname');
$workerfield->setOnChange("workerchanged()");
$insertpaychecksection->addField($workerfield);

// TODO: jos työntekijällä on useampi työsuhde, niin valintaboxi, pitää varmaan kutsua jsonila onko
// useampaa työsuhdetta, oletuksena voimassaoleva
//$agreementfield = new UISelectField("Työehtosopimus","labouragreementID","labouragreementID",$registry->labouragreements, 'name');
//$insertpaychecksection->addField($agreementfield);

$periodfield = new UISelectField("Palkkakausi","payrollperiodID","payrollperiodID",$registry->payrollperiods, 'name');
$insertpaychecksection->addField($periodfield);

//$field = new UIDateField("Palkkakausi alku", "startdate", 'startdate');
//$insertpaychecksection->addField($field);

//$field = new UIDateField("Palkkakausi loppu", "enddate", 'enddate');
//$insertpaychecksection->addField($field);

//$field = new UIDateField("Maksupäivä", "paymentdate", 'paymentdate');
//$insertpaychecksection->addField($field);

//$field = new UIDateField("Kirjanpitopäivä", "bookkeepingdate", 'bookkeepingdate'); // Tätä ei tarvita, mikäli bookkeeping osio ei ole käytössä
//$insertpaychecksection->addField($field);

$row = new Row();
$row->payrollperiodID = $registry->payrollperiodID;
$insertpaychecksection->setData($row);

$insertpaychecksection->show();




$insertpaychecksection = new UISection('Palkkalaskelman lisäys','605px');
$insertpaychecksection->setDialog(true);
$_SESSION['global_sectionID'] = $insertpaychecksection->getID();

$insertpaychecksection->setMode(UIComponent::MODE_INSERT);
//$insertpaychecksection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/payroll/payrollxxx');

$insertpaychecksection->setCustomContent('linkingDiv');
$insertpaychecksection->show();



function linkingDiv() {

	global $registry;
	$sectionID = $_SESSION['global_sectionID'];
	
	
	echo "	<table style='width:100%'>";
	echo "		<tr>";
	echo "			<td class=field-text style='width:150px;'>Työntekijä</td>";
	echo "			<td style='width:250px;'>";
	echo "				<select id=workerselectfield class=field-select style='width:200px;'>";
	echo "					<option value='0' selected></option>";
	foreach ($registry->workers as $index => $worker) {
		echo "				<option value=" . $worker->workerID . ">" . $worker->fullname . "</option>";
	}
	//echo "				<option disabled class='separator'></option>";
	//echo "				<hr>";
	//echo "				<option disabled>———————————</option>";
	// TODO: uuden työntekijän lisäys tähän pitäisi toteuttaa uutena ikkunana
	//echo "				<option value='-1'>Lisää työntekijä...</option>";
	echo "				</select>";
	echo " 			</td>";
	echo "			<td id='messagefield-zz-1 style='width:100px;'></td>";
	echo "		</tr>";
	
	// TODO: työsuhde pudotusvalikko pitäisi ilmestyä, jos työntekijä on kiinnitetty useampaan tessiin
	//		 ehkä useampi avoin työsuhdekkin voisi olla riittävä
	/*
	echo "		<tr>";
	echo "			<td class=field-text style='width:150px;'>Työsuhde</td>";
	echo "			<td style='width:250px;'>";
	echo "				<select id=searchwordlanguage class=field-select style='width:200px;'>";
	echo "					<option value='0' selected></option>";
	foreach ($registry->workers as $index => $worker) {
		echo "				<option value=" . $worker->workerID . ">" . $worker->fullname . "</option>";
	}
	//echo "				<option disabled class='separator'></option>";
	//echo "				<hr>";
	echo "				<option disabled>———————————</option>";
	echo "				<option value='-1'>Lisää työntekijä...</option>";
	echo "				</select>";
	echo " 			</td>";
	echo "			<td id='messagefield-zz-1 style='width:100px;'></td>";
	echo "		</tr>";
	*/
	
	echo "		<tr>";
	echo "			<td class=field-text style='width:150px;'>Palkkakausi</td>";
	echo "			<td style='width:250px;'>";
	echo "				<select id=payrollperiodselect class=field-select style='width:200px;'>";
	echo "					<option value='0' selected></option>";

	// Payrollperiods pitäisi oikeastaan ladata tessin perusteella...
	if (count($registry->payrollperiods) == 0) {
		echo "				<option disabled>Tilikaudella ei palkkakausia</option>";
		echo "				<option disabled>——————————————</option>";
		echo "				<option value='-2' selected>Muokattu palkkakausi</option>";
		echo "				<option value='-1'>Lisää palkkakausi...</option>";
	} else {
		foreach ($registry->payrollperiods as $index => $payrollperiod) {
			echo "				<option value=" . $payrollperiod->payrollperiodID . ">" . $payrollperiod->name . "</option>";
		}
		echo "				<option disabled>—————————————</option>";
		echo "				<option value='-2'>Muokattu palkkakausi</option>";
		echo "				<option value='-1'>Lisää palkkakausi...</option>";
	}
	echo "				</select>";
	echo " 			</td>";
	echo "			<td id='messagefield-zz-1 style='width:100px;'></td>";
	echo "		</tr>";
	
	if (count($registry->payrollperiods) == 0) {
		$fieldactive = " disabled='disabled' ";
	} else {
		$fieldactive = "";
	}
	
	echo "		<tr>";
	echo "			<td class=field-text style='width:150px;'>Aikajakso</td>";
	echo "			<td style='width:250px;'>";
	
	echo "			<input class=uitextfield id=startdatefield type='text'";
	echo " 				style='width:93px;margin-right:3px;' value='" . sqlDateToStr($registry->startdate) . "' readonly>";
	echo "-";
	echo "			<input class=uitextfield id=enddatefield type='text'";
	echo " 				style='width:93px;' value='" . sqlDateToStr($registry->enddate) . "' readonly>";
	
	echo "	<script>";
	echo "		$('#startdatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
	echo "	</script>";
	
	echo "	<script>";
	echo "		$('#enddatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
	echo "	</script>";
	echo " 			</td>";
	echo "			<td id='messagefield-zz-1 style='width:100px;'></td>";
	echo "		</tr>";
	
	echo "		<tr>";
	echo "			<td class=field-text style='width:150px;'>Maksupäivä</td>";
	echo "			<td style='width:250px;'>";
	
	echo "			<input class=uitextfield id=paymentdatefield type='text'";
	echo " 				style='width:93px;margin-right:3px;' value='" . sqlDateToStr($registry->enddate) . "' readonly>";
	echo "	<script>";
	echo "		$('#paymentdatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
	echo "	</script>";
	echo " 			</td>";
	echo "			<td id='messagefield-zz-1 style='width:100px;'></td>";
	echo "		</tr>";

	echo "		<tr>";
	echo "			<td class=field-text style='width:150px;'>Kirjauspäivä</td>";
	echo "			<td style='width:250px;'>";
	
	echo "			<input class=uitextfield id=bookkeepingdatefield type='text'";
	echo " 				style='width:93px;margin-right:3px;' value='" . sqlDateToStr($registry->enddate) . "' readonly>";
	echo "	<script>";
	echo "		$('#bookkeepingdatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
	echo "	</script>";
	echo " 			</td>";
	echo "			<td id='messagefield-zz-1 style='width:100px;'></td>";
	echo "		</tr>";
	
	
	echo "		<tr>";
	echo "			<td style='padding-right:5px;htight:10px;' colspan=3>";
	echo " 			</td>";
	echo "		</tr>";
	
	echo "		<tr>";
	echo "			<td style='padding-right:5px;text-align:right;' colspan=3>";
	echo "					<button  class=section-button  onclick='closeinsertpaycheckbuttonpressed()'>Peruuta</button>";
	echo "					<button  class=section-button  onclick='insertpaycheckpressed()'>Tallenna</button>";
	echo "			</td>";
	echo "		</tr>";
	echo "	</table>";

	
	echo "<script>";
	echo "		function closeinsertpaycheckbuttonpressed() {";
	echo "  		$('#sectiondialog-" . $sectionID . "').dialog('close');";
	echo "		};";
	echo "	</script>";
	
	
	echo "	<script>";
	echo "		function insertpaycheckpressed() {";
	echo "			console.log('insertpaycheckpressed');";
	
	echo "			var workerID = $('#workerselectfield').val();";
	echo "			console.log(' -- workerID - '+workerID);";
	
	echo "			if (workerID == 0) {";
	echo "				alert('työntekijä pitää valita');";
	echo "				return;";
	echo "			}";
	
	echo "			var periodID = $('#payrollperiodselect').val();";
	echo "			console.log(' -- periodID - '+periodID);";
	
	echo "			var startdate = $('#startdatefield').val();";
	echo "			console.log(' -- startdate - '+startdate);";
	
	echo "			var enddate = $('#enddatefield').val();";
	echo "			console.log(' -- enddate - '+enddate);";
	
	
	echo "			var paymentdate = $('#paymentdatefield').val();";
	echo "			console.log(' -- payment - '+paymentdate);";
	
	echo "			var bookkeepingdate = $('#bookkeepingdatefield').val();";
	echo "			console.log(' -- bookkeeping - '+bookkeepingdate);";
	
	echo "			var url = '" . getUrl('payroll/payroll/insertpaycheck') . "&workerID='+workerID+'&periodID='+periodID+'&startdate='+startdate+'&enddate='+enddate+'&paymentdate='+paymentdate+'&bookkeepingdate='+bookkeepingdate;";
	echo "			console.log('url - '+url);";
	
	echo "			window.location = url;";
	//echo "			alert('insertpaycheckpressed button pressed');";
	echo "		}";
	echo "	</script>";
	
	
}





echo "<script>";
echo "	function workerchanged() {";

echo "	 	var workerfieldID     = '#".$workerfield->getEditFieldID()."';";
//echo "	 	var agreementfieldID  = '#".$agreementfield->getEditFieldID()."';";
echo "	 	var periodfieldID     = '#".$periodfield->getEditFieldID()."';";

echo "	 	var workerID = $(workerfieldID).val();";
echo "		console.log('workerID - '+workerID);";
echo "		console.log('url - " . getUrl('payroll/payroll/getworkerpaycheckdataJSON') . "&json=1&workerID='+workerID);";

echo "		$.getJSON('" . getUrl('payroll/payroll/getworkerpaycheckdataJSON') . "&json=1&workerID='+workerID,'',function(data) {";
echo "			console.log('loaded - ' + data.contracts.length);";
echo "			if (data.contracts.length == 0) {";
echo "				alert('Valitulla työntekijällä ei ole aktiivista työsuhdetta');";
echo "			}";
//echo "			console.log('contracts - ' + data.contracs.length);";
echo "			$.each(data.contracts, function(index) {";
echo "				console.log('show - '+index + ' - ' + data.contracts[index].labouragreement);";
echo "			});";
echo "		});";


//echo "		alert('jejeeea - ' + number + ' - ' + code );";
//echo "		if (number == '') {";
//echo "	 		$(numberfieldID).val(code+'0');";
//echo "		}";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function labouragreementchanged(labouragreementID) {";
echo "		$.getJSON('" . getUrl('payroll/payroll/getpayrollperiodsJSON') . "&json=1&labouragreementID='+labouragreementID,'',function(data) {";
echo "			console.log('loaded2');";
//echo "			console.log('contracts - ' + data.contracs.length);";
echo "			$.each(data.contracts, function(index) {";
echo "				console.log('show - '+index + ' - ' + data.contracts[index].labouragreement);";
echo "			});";
echo "		});";
echo "	}";
echo "</script>";





$tabsection = new UITabSection("","900px");

$tabIndex = $tabsection->addTab("" . $registry->period->name, "payroll/payroll/showpayroll&selectionID=0");
foreach($this->registry->selection as $index => $selection) {
	$tabIndex = $tabsection->addTab($selection->name, "payroll/payroll/showpayroll&selectionID=" . $selection->selectionID);
	//echo "<br>" . $selection->selectionID . " - " . $this->registry->selectionID;
	if ($selection->selectionID == $this->registry->selectionID){
		$tabsection->setActiveIndex($tabIndex);
	}
}


//$table = new UITableSection("Ostolaskut","800px");
$table = new UITableSection("Palkkalaskelmat","850px");
$table->setOpen(true);
$table->setFramesVisible(false);
$table->showTitle(false);
$table->setShowSumRow(true);



$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertpaychecksection->getID() ,'Uusi palkkalaskelma');
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"accounting/purchases/showpurchase","purchaseID");


if (count($registry->paychecks) > 0) {
	$table->setShowSumRow(true);
}


$table->setLineAction(UIComponent::ACTION_FORWARD,"payroll/payroll/showpaycheck","paycheckID");

$column = new UISortColumn("#", "paycheckID");
$table->addColumn($column);

$column = new UISortColumn("Päiväys", "startdate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);



/*
if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$column = new UISelectColumn($dimension->abbreviation, "name", "dimension" . $dimension->dimensionID, $registry->dimensionvalues[$dimension->dimensionID]);
		$column->setUndefinedString("-");
		$table->addColumn($column);
	}
}
*/



$column = new UISelectColumn("Työntekijä", "fullname", "workerID", $registry->workers);
$table->addColumn($column);

$column = new UISortColumn("Palkkakausi", "timespan");
$table->addColumn($column);

/*
$column = new UISortColumn("Kulut", "totalcost");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);
*/

$column = new UISortColumn("Maksupäivä", "paymentdate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISortColumn("Brutto", "grossamount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UISortColumn("Netto", "netamount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

//$column = new UISortColumn("Tila", "state");
//$table->addColumn($column);

$column = new UILinkColumn("Tosite", "file", "file","payroll/payroll/downloadpaycheck");
$table->addColumn($column);


$column = new UISortColumn("Tila", "statestr");
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);


$table->setData($registry->paychecks);

$tabsection->setContent($table);
$tabsection->show();

echo "<br><br><br><br><br>";



echo "<table style='width:700px;'>";
echo "	<tr>";
echo "		<td style='width:100%;text-align:right;'>";

/*
echo "<select id=periodselectfield class='field-select' style='width:120px;margin-right:5px;'>";
foreach($this->registry->periods as $index => $period) {
	if ($this->registry->periodID ==  $period->periodID) {
		echo "<option selected='selected' value='" . $index . "'>" . $period->fullname . "</option>";
	} else {
		echo "<option value='" . $index . "'>" . $period->fullname . "</option>";
	}
}
echo "</select>";


echo "	<script>";
echo "		$('#periodselectfield').on('change', function() {";
echo "			window.location='".getUrl('payroll/payroll/showpayroll')."&periodID='+this.value;";
echo "		});";
echo "	</script>";
*/


/*
echo "<select id=workerselectfield class='field-select' style='width:120px;margin-right:5px;height:27px;'>";
echo "<option value='0'></option>";
foreach($this->registry->workers as $index => $worker) {
	if ($this->registry->supplierID ==  $worker->workerID) {
		echo "<option  selected='selected' value='" . $worker->workerID . "'>" . $worker->fullname . "</option>";
	} else {
		echo "<option value='" . $worker->workerID . "'>" . $worker->fullname . "</option>";
	}
}
echo "</select>";

echo "	<script>";
echo "		$('#workerselectfield').on('change', function() {";
echo "			window.location='".getUrl('payroll/payroll/showpayroll')."&supplierID='+this.value;";
echo "		});";
echo "	</script>";




echo "<select id=yearselectfield class='field-select' style='width:70px;margin-right:5px;height:27px;'>";
echo "<option value='0'></option>";
foreach($this->registry->years as $index => $value) {
	if ($this->registry->year ==  $index) {
		echo "<option  selected='selected' value='" . $index . "'>" . $value . "</option>";
	} else {
		echo "<option value='" . $index . "'>" . $value . "</option>";
	}
}
echo "</select>";



echo "	<script>";
echo "		$('#yearselectfield').on('change', function() {";
echo "			window.location='".getUrl('payroll/payroll/showpayroll')."&year='+this.value;";
echo "		});";
echo "	</script>";


echo "<select id=monthselectfield class='field-select' style='width:90px;margin-right:5px;height:27px;'>";
echo "<option value='0'></option>";
foreach($this->registry->months as $index => $value) {
	if ($this->registry->month ==  $index) {
		echo "<option  selected='selected' value='" . $index . "'>" . $value . "</option>";
	} else {
		echo "<option value='" . $index . "'>" . $value . "</option>";
	}
}
echo "</select>";



echo "	<script>";
echo "		$('#selectionselectfield').on('change', function() {";
echo "			window.location='".getUrl('payroll/payroll/showpayroll')."&selectionID='+this.value;";
echo "		});";
echo "	</script>";



echo "			<input class=uitextfield id=startdatefield type='text'";
echo " 				style='width:90px;margin-right:3px;' value='" . sqlDateToStr($this->registry->startdate) . "' readonly>";
echo "-";
echo "			<input class=uitextfield id=enddatefield type='text'";
echo " 				style='width:90px;' value='" . sqlDateToStr($this->registry->enddate) . "' readonly>";

echo "	<script>";
echo "		$('#startdatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
echo "	</script>";

echo "	<script>";
echo "		$('#enddatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
echo "	</script>";

echo "		</td>";
echo "	</tr>";
echo "</table>";

*/


/*
$table = new UITableSection("Palkkalaskelmat","700px");
$table->setOpen(true);
$table->setFramesVisible(false);

if (count($registry->paychecks) > 0) {
	$table->setShowSumRow(true);
}

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertpaychecksection->getID() ,'Uusi palkkalaskelma');
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"payroll/payroll/showpaycheck","paycheckID");

$column = new UISortColumn("#", "paycheckID");
$table->addColumn($column);

$column = new UISortColumn("Päiväys", "startdate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);


/*
if (count($this->registry->dimensions) > 0) {
	foreach($this->registry->dimensions as $index => $dimension) {
		$column = new UISelectColumn($dimension->abbreviation, "name", "dimension" . $dimension->dimensionID, $registry->dimensionvalues[$dimension->dimensionID]);
		$column->setUndefinedString("-");
		$table->addColumn($column);
	}
}
* /



$column = new UISelectColumn("Työntekijä", "fullname", "workerID", $registry->workers);
$table->addColumn($column);

//$column = new UISelectColumn("Kausi", "name", "payrollperiodID", $registry->payrollperiods);
//$table->addColumn($column);

$column = new UISortColumn("Palkkakausi", "timespan");
$table->addColumn($column);

$column = new UISortColumn("Kulut", "totalcost");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UISortColumn("Maksupäivä", "paymentdate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($column);

$column = new UISortColumn("Brutto", "grossamount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UISortColumn("Netto", "netamount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$table->addColumn($column);

$column = new UISortColumn("Tila", "state");
$table->addColumn($column);

$table->setData($registry->paychecks);
$table->show();

*/

