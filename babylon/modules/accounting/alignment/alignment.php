<?php


$width = "700px";




// voitaneen poistaa
/*
$editstatementrow = new UISection("Tilioterivin muokkaus");
$editstatementrow->setDialog(true);
$editstatementrow->setMode(UIComponent::MODE_EDIT);
$editstatementrow->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bankstatements/updatebankstatementrow&statementID=' . $registry->statementID, "rowID");
$button = new UIButton(UIComponent::ACTION_JAVASCRIPT, "executedeductreceivables", "Vähennä myyntisaamisia");
$editstatementrow->addButton($button);
$editstatementrow->show();
*/

// Tätä editstatmentrowid-dialogia tarvitaan, koska sen avulla saadaan kutsuttua setValue
$editstatementrow = new UISection("Tilioterivin muokkaus");
$editstatementrow->setDialog(true);
$editstatementrow->setMode(UIComponent::MODE_EDIT);
$editstatementrow->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bankstatements/updatebankstatementrow&statementID=' . $registry->statementID, "rowID");
$button = new UIButton(UIComponent::ACTION_JAVASCRIPT, "executedeductreceivables", "Vähennä myyntisaamisia");
$editstatementrow->addButton($button);
$editstatementrow->show();




echo "<table style='width:1400px;'>";
echo "	<tr>";
echo "		<td style='width:700px;text-align:right;vertical-align:top;'>";


// -------------------------------------------------------------------------------------
//   Tilioterivit - Header
// -------------------------------------------------------------------------------------

echo "<table style='width:700px;'>";
echo "	<tr>";
echo "		<td style='width:100%;text-align:right;'>";

$selectedbankaccount = null;
echo "<select id=alignmenttypeselectfield   class='top-select'   style='width:220px;margin-right:5px;'>";
foreach($this->registry->alignmenttypes as $index => $aligmenttype) {
	if ($this->registry->aligmenttypeID ==  $index) {
		echo "<option selected='selected' value='" . $index . "'>" . $aligmenttype . "</option>";
	} else {
		echo "<option value='" . $index . "'>" . $aligmenttype. "</option>";
	}
}
echo "</select>";


echo "	<script>";
echo "		$('#alignmenttypeselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/alignment/alignment')."&aligmenttypeID='+this.value;";
echo "		});";
echo "	</script>";

echo "		</td>";
echo "	</tr>";


echo "	<tr>";
echo "		<td style='width:100%;text-align:right;'>";

echo "			<select id=targetperiodselectfield  class='top-select'  style='width:220px;margin-right:5px;'>";
foreach($this->registry->periods as $index => $period) {
	if ($this->registry->targetperiodID ==  $period->periodID) {
		echo "		<option selected='selected' value='" . $index . "'>" . $period->fullname . "</option>";
	} else {
		echo "		<option value='" . $index . "'>" . $period->fullname . "</option>";
	}
}
echo "			</select>";

echo "			<script>";
echo "				$('#targetperiodselectfield').on('change', function() {";
echo "					window.location='".getUrl('accounting/alignment/alignment')."&targetperiodID='+this.value;";
echo "				});";
echo "			</script>";

echo "		</td>";
echo "	</tr>";



echo "	<tr>";
echo "		<td style='width:100%;text-align:right;'>";

echo "			<select id=selectionselectfield  class='top-select'  style='width:220px;margin-right:5px;'>";
if ($this->registry->selectionID ==  0) {
	echo "		<option selected='selected' value='0'>Kaikki</option>";
} else {
	echo "		<option selected='selected' value='0'>Kaikki</option>";
}
foreach($this->registry->selections as $index => $selection) {
	if ($this->registry->selectionID ==  $selection->selectionID) {
		echo "		<option selected='selected' value='" . $selection->selectionID . "'>" . $selection->name . "</option>";
	} else {
		echo "		<option value='" . $selection->selectionID . "'>" . $selection->name . "</option>";
	}
}
echo "			</select>";

echo "			<script>";
echo "				$('#selectionselectfield').on('change', function() {";
echo "					window.location='".getUrl('accounting/alignment/alignment')."&selectionID='+this.value;";
echo "				});";
echo "			</script>";

echo "		</td>";
echo "	</tr>";

echo "</table>";




if ($this->registry->aligmenttypeID == 1) {

	$table = new UITableSection("Ostolaskut3",$width);
	$table->setOpen(true);
	$table->setFramesVisible(true);
	//$table->setShowSumRow(true);
	$table->setMode(UIComponent::MODE_LINESELECT);

	$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editstatementrow->getID(),"purchaseID");

	$button = new UIButton(UIComponent::ACTION_JAVASCRIPT, "unitepuchaselines", "Kohdista");
	$table->addButton($button);

	$idrowcolumn = new UISortColumn("#", "purchaseID");
	$table->addColumn($idrowcolumn);

	$column = new UISortColumn("Laskupäivä", "purchasedate");
	$column->setFormatter(Column::COLUMNTYPE_DATE);
	$table->addColumn($column);

	$column = new UISelectColumn("Toimittaja", "name", "supplierID", $registry->suppliers,"", "200px");
	$table->addColumn($column);

	$column = new UISortColumn("Laskupäivä", "purchasedate");
	$column->setFormatter(Column::COLUMNTYPE_DATE);
	$table->addColumn($column);

	$column = new UISortColumn("Eräpäivä", "duedate");
	$column->setFormatter(Column::COLUMNTYPE_DATE);
	$table->addColumn($column);

	$targetamountcolumn = new UISortColumn("Brutto", "grossamount");
	$targetamountcolumn->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	$targetamountcolumn->setAlign(Column::ALIGN_RIGHT);
	$table->addColumn($targetamountcolumn);

	$table->setData($registry->invoices);
	$table->show();

} elseif ($this->registry->aligmenttypeID == 2) {


	$table = new UITableSection("Myyntilaskut",$width);
	$table->setOpen(true);
	$table->setFramesVisible(true);
	$table->setMode(UIComponent::MODE_LINESELECT);

	$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editstatementrow->getID(),"invoiceID");
	
	$button = new UIButton(UIComponent::ACTION_JAVASCRIPT, "unitesalesinvoicelines", "Kohdista");
	$table->addButton($button);

	$idrowcolumn = new UISortColumn("#", "invoiceID");
	$table->addColumn($idrowcolumn);

	$column = new UISortColumn("Laskupäivä", "invoicedate");
	$column->setFormatter(Column::COLUMNTYPE_DATE);
	$table->addColumn($column);

	$column = new UISelectColumn("Client", "name", "clientcompanyID", $registry->companies,"", "200px");
	$table->addColumn($column);

	$column = new UISortColumn("Viitenumero", "referencenumber");
	$table->addColumn($column);

	$column = new UISortColumn("Eräpäivä", "duedate");
	$column->setFormatter(Column::COLUMNTYPE_DATE);
	$table->addColumn($column);

	$targetamountcolumn = new UISortColumn("Brutto", "grossamount");
	$targetamountcolumn->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	$targetamountcolumn->setAlign(Column::ALIGN_RIGHT);
	$table->addColumn($targetamountcolumn);

	$table->setData($registry->invoices);
	$table->show();

} elseif ($this->registry->aligmenttypeID == 3) {
	
	
	$table = new UITableSection("Palkkamaksut",$width);
	$table->setOpen(true);
	$table->setFramesVisible(true);
	$table->setMode(UIComponent::MODE_LINESELECT);

	$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editstatementrow->getID(),"paycheckID");
	
	$button = new UIButton(UIComponent::ACTION_JAVASCRIPT, "unitepayrollpayment", "Kohdista");
	$table->addButton($button);

	$idrowcolumn = new UISortColumn("#", "paycheckID");
	$table->addColumn($idrowcolumn);
	
	$column = new UISortColumn("Maksupäivä", "paymentdate");
	$column->setFormatter(Column::COLUMNTYPE_DATE);
	$table->addColumn($column);

	$column = new UISelectColumn("Työntekijä", "fullname", "workerID", $registry->workers,"", "200px");
	$table->addColumn($column);
	
	$targetamountcolumn = new UISortColumn("Maksetaan", "netamount");
	$targetamountcolumn->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	$targetamountcolumn->setAlign(Column::ALIGN_RIGHT);
	$table->addColumn($targetamountcolumn);
	
	$table->setData($registry->paychecks);
	$table->show();
	
} else {
	echo "<br> Not implemented";
}
echo "		</td>";


// Oikeanpuoleinen palsta...
echo "		<td style='width:700px;text-align:right;vertical-align:top;'>";



echo "<table style='width:700px;'>";

echo "	<tr>";
echo "		<td style='width:100%;text-align:right;'>";
$selectedbankaccount = null;
echo "			<select id=bankaccountselectfield   class='top-select'   style='width:220px;margin-right:5px;'>";
foreach($this->registry->bankaccounts as $index => $bankaccount) {
	if ($this->registry->bankaccountID ==  $bankaccount->bankaccountID) {
		echo "		<option selected='selected' value='" . $index . "'>" . $bankaccount->name . "</option>";
		$selectedbankaccount = $bankaccount;
	} else {
		echo "		<option value='" . $index . "'>" . $bankaccount->name . "</option>";
	}
}
echo "			</select>";
echo "			<script>";
echo "				$('#bankaccountselectfield').on('change', function() {";
echo "					window.location='".getUrl('accounting/alignment/alignment')."&bankaccountID='+this.value;";
echo "				});";
echo "			</script>";
echo "		</td>";
echo "	</tr>";

echo "	<tr>";
echo "		<td style='width:100%;text-align:right;'>";
echo "			<select id=periodselectfield  class='top-select'  style='width:220px;margin-right:5px;'>";
foreach($this->registry->periods as $index => $period) {
	if ($this->registry->periodID ==  $period->periodID) {
		echo "		<option selected='selected' value='" . $index . "'>" . $period->fullname . "</option>";
	} else {
		echo "		<option value='" . $index . "'>" . $period->fullname . "</option>";
	}
}
echo "			</select>";
echo "			<script>";
echo "				$('#periodselectfield').on('change', function() {";
echo "					window.location='".getUrl('accounting/alignment/alignment')."&periodID='+this.value;";
echo "				});";
echo "			</script>";
echo "		</td>";
echo "	</tr>";

echo "	<tr>";
echo "		<td style='width:100%;text-align:right;'>";
echo "			<select id=statementselectfield  class='top-select'  style='width:220px;margin-right:5px;'>";
if ($this->registry->statementID ==  0) {
	echo "		<option selected='selected' value='0'>Kaikki</option>";
} else {
	echo "		<option selected='selected' value='0'>Kaikki</option>";
}
foreach($this->registry->statements as $index => $statement) {
	if ($this->registry->statementID ==  $statement->bankstatementID) {
		echo "		<option selected='selected' value='" . $index . "'>" . $statement->name . "</option>";
	} else {
		echo "		<option value='" . $index . "'>" . $statement->name . "</option>";
	}
}
echo "			</select>";
echo "			<script>";
echo "				$('#statementselectfield').on('change', function() {";
echo "					window.location='".getUrl('accounting/alignment/alignment')."&statementID='+this.value;";
echo "				});";
echo "			</script>";
echo "		</td>";
echo "	</tr>";
echo "</table>";
// Tilioterivit Header - end




$deductpayablesdialog = new UISection("Ostovelkojen vähennys");
$deductpayablesdialog->setDialog(true);
$deductpayablesdialog->setMode(UIComponent::MODE_EDIT);
//$insertreceipt->setCustomSetFunction('editDialogSetFunction');
$deductpayablesdialog->setSaveAction(UIComponent::ACTION_FORWARD, "accounting/alignment/deductpayables");

$field = new UISelectField("Maksun saaja","supplierID","supplierID",$registry->suppliers, "name");
$field->setPredictive(true);
$deductpayablesdialog->addField($field);

//$field = new UISelectField("Tili","accountID","accountID",$registry->accounts, "fullname");
//$field->setPredictive(true);
//$insertpurchasereceiptdialog->addField($field);

//$field = new UISelectField("Ostotapa","paymentmethodID","paymentmethodID",$registry->paymentmethods, "name");
//$deductpayablesdialog->addField($field);

$deductpayablesdialogstatementrowfield = new UITextField("RowID", "rowID", 'statementrowID');
$deductpayablesdialog->addField($deductpayablesdialogstatementrowfield);

$deductpayablesdialog->show();




$insertpurchasereceiptdialog = new UISection("Uuden ostolaskun lisäys");
$insertpurchasereceiptdialog->setDialog(true);
$insertpurchasereceiptdialog->setMode(UIComponent::MODE_EDIT);
//$insertreceipt->setCustomSetFunction('editDialogSetFunction');
$insertpurchasereceiptdialog->setSaveAction(UIComponent::ACTION_FORWARD, "accounting/alignment/insertnewpurchasefrombankstatement");

$field = new UISelectField("Maksun saaja","supplierID","supplierID",$registry->suppliers, "name");
$field->setPredictive(true);
$insertpurchasereceiptdialog->addField($field);

//$field = new UISelectField("Tili","accountID","accountID",$registry->accounts, "fullname");
//$field->setPredictive(true);
//$insertpurchasereceiptdialog->addField($field);

$field = new UISelectField("Ostotapa","paymentmethodID","paymentmethodID",$registry->paymentmethods, "name");
$insertpurchasereceiptdialog->addField($field);

$purchasestatementrowfield = new UITextField("RowID", "rowID", 'statementrowID');
$insertpurchasereceiptdialog->addField($purchasestatementrowfield);

$insertpurchasereceiptdialog->show();



$insertreceipt = new UISection("Tositteen lisäys");
$insertreceipt->setDialog(true);
$insertreceipt->setMode(UIComponent::MODE_EDIT);
//$insertreceipt->setCustomSetFunction('editDialogSetFunction');
$insertreceipt->setSaveAction(UIComponent::ACTION_FORWARD, "accounting/alignment/insertbankstatementreceipt");

$field = new UISelectField("Tili","accountID","accountID",$registry->accounts, "fullname");
$field->setPredictive(true);
$insertreceipt->addField($field);

$statementrowfield = new UITextField("RowID", "rowID", 'statementrowID');
$insertreceipt->addField($statementrowfield);

$amountrowfield = new UITextField("Amount", "amount", 'amount');
$insertreceipt->addField($amountrowfield);

$insertreceipt->show();




$insertpayablereceipt = new UISection("Tositteen lisäys2");
$insertpayablereceipt->setDialog(true);
$insertpayablereceipt->setMode(UIComponent::MODE_EDIT);
//$insertreceipt->setCustomSetFunction('editDialogSetFunction');
$insertpayablereceipt->setSaveAction(UIComponent::ACTION_FORWARD, "accounting/alignment/insertbankstatementpayablereceipt");

$field = new UISelectField("Saaja","supplierID","supplierID",$registry->suppliers, "name");
$field->setPredictive(true);
$insertpayablereceipt->addField($field);

//$field = new UISelectField("Velkatili","accountID","accountID",$registry->accounts, "fullname");
//$field->setPredictive(true);
//$insertpayablereceipt->addField($field);

$payablereceiptrowfield = new UITextField("RowID", "rowID", 'statementrowID');
$insertpayablereceipt->addField($payablereceiptrowfield);

$payableamountrowfield = new UITextField("Amount", "amount", 'amount');
$insertpayablereceipt->addField($payableamountrowfield);

$insertpayablereceipt->show();





function editDialogSetFunction() {

	global $registry;
	global $editdialog;


}

echo "	<script>";
echo "		var deductreceivablesrowID = 0;";
echo "		function setValue_" . $editstatementrow->getID(). "(fieldname, value) {";
echo "			console.log('setting value - ' + fieldname + '='+value);";
echo "			if (fieldname == 'rowID') {";
echo "				deductreceivablesrowID = value;";
echo "			}";
echo "		}";
echo "	</script>";


echo "	<script>";
echo "		function executedeductreceivables() {";
echo "			console.log('deductreceivablesrowID - ' + deductreceivablesrowID);";
echo "			var url = '" . getUrl('accounting/alignment/deductreceivables') . "&id='+deductreceivablesrowID;";
echo "			console.log(url);";
echo "			window.location = url;";
echo "		}";
echo "	</script>";


$statementrowstable = new UITableSection("Tilioterivit",$width);
$statementrowstable->setOpen(true);
$statementrowstable->setFramesVisible(true);
//$statementrowstable->setShowSumRow(true);
$statementrowstable->setMode(UIComponent::MODE_LINESELECT);

// TODO: en tohdi tätä vielä poistaa, voi olla, että lineaction pitää olla settä MODE_LINESELECT toimii
// Tätä editstatmentrowid-dialogia tarvitaan, koska sen avulla saadaan kutsuttua setValue
$statementrowstable->setLineAction(UIComponent::ACTION_OPENDIALOG, $editstatementrow->getID(),"rowID");

$button = new UIButton(UIComponent::ACTION_JAVASCRIPT, "deductreceivables", "Vähennä myyntisaamisia");
$statementrowstable->addButton($button);

$button = new UIButton(UIComponent::ACTION_JAVASCRIPT, "deductpayablesdialog", "Vähennä ostovelkoja", "rowID");
$statementrowstable->addButton($button);

$button = new UIButton(UIComponent::ACTION_JAVASCRIPT, "opennewpurchasereceiptdialog", "Uusi Ostolasku", "rowID");
$statementrowstable->addButton($button);

$button = new UIButton(UIComponent::ACTION_JAVASCRIPT, "opennewbankreceiptdialog", "Uusi pankkitilitosite", "rowID");
$statementrowstable->addButton($button);

$column = new UISortColumn("#", "rowID");
$statementrowstable->addColumn($column);

$column = new UISortColumn("Päiväys", "entrydate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$statementrowstable->addColumn($column);

$column = new UISortColumn("Saaja/maksaja", "rowname", null, "190px");
$statementrowstable->addColumn($column);

$column = new UISpaceColumn("10px");
$statementrowstable->addColumn($column);

$column = new UISortColumn("Viite/teksti", "reference", null, "190px");
$statementrowstable->addColumn($column);

$sourceamountcolumn = new UISortColumn("Määrä", "amount", "amount");
$sourceamountcolumn->setAlign(Column::ALIGN_RIGHT);
$sourceamountcolumn->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$statementrowstable->addColumn($sourceamountcolumn);

$statementrowstable->setData($registry->bankstatementrows);
$statementrowstable->show();




echo "		</td>";
echo "	</tr>";
echo "</table>";


echo "	<script>";

echo "		oldtargetID = -1;";
echo "		selectedrowid" . $statementrowstable->getID() . " = -1;";

echo "		function purchaselineclicked() {";
echo "			var targetID = selectedrowid" . $table->getID() . ";";
echo "			console.log('purchaselineclicked - '+targetID);";
echo "			return;";
echo "			if (oldtargetID == targetID) {";
echo "				console.log('double');";
echo "				var url = '" . getUrl('accounting/purchases/showpurchase') . "&id='+targetID;";
echo "				console.log(url);";
//echo "				window.open(url);";
echo "			}";
echo "			oldtargetID = targetID;";
echo "		}";
echo "	</script>";

echo "	<script>";
echo "		function unitepuchaselines() {";
echo "			var sourceID = selectedrowid" . $statementrowstable->getID() . ";";
echo "			var targetID = selectedrowid" . $table->getID() . ";";
echo "			console.log('unitepuchaselines - ' + sourceID + ' - ' + targetID);";

echo "			var sourceRowNumber = selectedrownumber" . $statementrowstable->getID() . ";";
echo "			var targetRowNumber = selectedrownumber" . $table->getID() . ";";
echo "			console.log('rownumbers - ' + sourceRowNumber + ' - ' + targetRowNumber);";

echo "			var sourceValueItem = '#tablerow-" . $statementrowstable->getID() . "-'+sourceRowNumber+'-" .  $sourceamountcolumn->getID() . "';";
echo "			var targetValueItem = '#tablerow-" . $table->getID() . "-'+targetRowNumber+'-" .  $targetamountcolumn->getID() . "';";

echo "			var otherrowID = $('#" . $idrowcolumn->getID() . "').val();";
echo "			console.log('orherrowid - '+otherrowID);";

echo "			var sourceValue = $(sourceValueItem).val();";
echo "			var targetValue = $(targetValueItem).val();;";
echo "			console.log('values - ' + sourceValue + ' - ' + targetValue);";

echo "			if (sourceValue < 0) {";
echo "				console.log('source smaller');";
echo "				var sourceValueAbs = Math.abs(sourceValue);";
echo "				if (sourceValueAbs == targetValue) {";
echo "					console.log(' +++ values  match');";

echo "					var url = '" . getUrl('accounting/alignment/linkpurchasetostatementrow') . "&statementrowID='+sourceID+'&purchaseID='+targetID;";
echo "					console.log(url);";
echo "					window.location = url;";

echo "				} else {";
echo "					console.log(' --- values not match');";
echo "				}";
echo "			}";
echo "			if (targetValue < 0) {";
echo "				console.log('target smaller');";
echo "				var targetValueAbs = Math.abs(targetValue);";
echo "				if (targetValueAbs == sourceValue) {";
echo "					console.log(' +++ values match');";
echo "					console.log(' tämä on ilmeisesti hyvityslasku, koska targetvalue on pienemi kuin source');";
echo "					var url = '" . getUrl('accounting/alignment/linkpurchasetostatementrow') . "&statementrowID='+sourceID+'&purchaseID='+targetID;";
echo "					console.log(url);";
echo "					window.location = url;";
echo "				} else {";
echo "					console.log(' --- values not match');";
echo "				}";
echo "			}";


//echo "			var url = '" . getUrl('accounting/alignment/deductreceivables') . "&id='+deductreceivablesrowID;";
//echo "			console.log(url);";
//echo "			window.location = url;";

echo "		}";
echo "	</script>";



echo "	<script>";

echo "		function deductreceivables() {";
echo "			var sourceID = selectedrowid" . $statementrowstable->getID() . ";";
//echo "			var targetID = selectedrowid" . $table->getID() . ";";
echo "			console.log('deductreceivables - sourceID: ' + sourceID );";
//echo "			console.log('deductreceivables - targetID: ' + targetID);";

echo "			var url = '" . getUrl('accounting/alignment/deductreceivables') . "&id='+sourceID;";
echo "			console.log(url);";
//echo "			window.location = url;";
echo "		}";
echo "	</script>";



echo "	<script>";
echo "		function opennewbankreceiptdialog() {";

echo "			console.log('banktable - " . $statementrowstable->getID() . "');";
echo "			console.log('banktablecolumn - " . $sourceamountcolumn->getID() . "');";
echo "			console.log('rownumber - ' + selectedrownumber" . $statementrowstable->getID() . ");";
echo "			var sourceID = selectedrowid" . $statementrowstable->getID() . ";";
echo "			var valuestr = '#tablerow-" . $statementrowstable->getID() . "-'+selectedrownumber" . $statementrowstable->getID() . "+'-" . $sourceamountcolumn->getID() . "';"; 
echo "			console.log('valuestr - ' + valuestr);";
echo "			console.log('value - ' + $(valuestr).val());";
echo "			console.log('sourceID - ' + sourceID);";

echo "			var value = $(valuestr).val();";

echo "			if (sourceID < 1) {";
echo "  			alert('Riviä ei ole valittu');";
echo "				return;";
echo "			}";

echo "			if (value < 0) {";
echo "				console.log(' -- negative');";
echo "				" . $statementrowfield->setValueJSFunction() . "(sourceID);";
//echo "				" . $payablereceiptrowfield->setValueJSFunction() . "(sourceID);";
echo "				" . $amountrowfield->setValueJSFunction() . "($(valuestr).val());";
//echo "				" . $payableamountrowfield->setValueJSFunction() . "($(valuestr).val());";
//echo "  			$('#sectiondialog-" . $insertpayablereceipt->getID() . "').dialog('open');";
echo "  			$('#sectiondialog-" . $insertreceipt->getID() . "').dialog('open');";


echo "			} else {";
echo "				console.log(' -- positive');";
echo "				" . $statementrowfield->setValueJSFunction() . "(sourceID);";
echo "				" . $amountrowfield->setValueJSFunction() . "($(valuestr).val());";
//echo "				" . $amountrowfield->setValueJSFunction() . "(sourceID);";
//echo "  			$('#sectiondialog-" . $insertreceipt->getID() . "').dialog('open');";
echo "  			$('#sectiondialog-" . $insertreceipt->getID() . "').dialog('open');";
echo "			}";
echo "		}";
echo "	</script>";





echo "	<script>";
echo "		function deductpayablesdialog() {";

echo "			var sourceID = selectedrowid" . $statementrowstable->getID() . ";";
echo "			console.log('sourceID - ' + sourceID);";
echo "			if (sourceID > 0) {";
echo "				" . $deductpayablesdialogstatementrowfield->setValueJSFunction() . "(sourceID);";
echo "  			$('#sectiondialog-" . $deductpayablesdialog->getID() . "').dialog('open');";
echo "			} else {";
echo "  			alert('Riviä ei ole valittu');";
echo "			}";
echo "		}";
echo "	</script>";





echo "	<script>";
echo "		function opennewpurchasereceiptdialog() {";

echo "			var sourceID = selectedrowid" . $statementrowstable->getID() . ";";
echo "			console.log('sourceID - ' + sourceID);";
echo "			if (sourceID > 0) {";
echo "				" . $purchasestatementrowfield->setValueJSFunction() . "(sourceID);";
echo "  			$('#sectiondialog-" . $insertpurchasereceiptdialog->getID() . "').dialog('open');";
echo "			} else {";
echo "  			alert('Riviä ei ole valittu');";
echo "			}";
echo "		}";
echo "	</script>";



echo "	<script>";
echo "		function setValue_" . $insertreceipt->getID(). "(fieldname, value) {";
echo "			console.log('setting value - ' + fieldname + '='+value);";
echo "			if (fieldname == 'rowID') {";
//echo "				deductreceivablesrowID = value;";
echo "				console.log(' ---------- rowID ');";
//echo "	 			" . $statementrowfield->setValueJSFunction()."(333);";
echo "			}";
echo "		}";
echo "	</script>";



echo "	<script>";
echo "		oldtargetID = -1;";
echo "		function salesinvoicelineclicked() {";
echo "			console.log('salesinvoicelineclicked');";
echo "			return;";
echo "			var targetID = selectedrowid" . $table->getID() . ";";
echo "			if (oldtargetID == targetID) {";
echo "				console.log('double');";
echo "				console.log('purchaselineclicked - '+targetID);";
echo "				var url = '" . getUrl('sales/invoices/showinvoice') . "&id='+targetID;";
echo "				console.log(url);";
//echo "				window.open(url);";
echo "			}";
echo "			oldtargetID = targetID;";
echo "		}";
echo "	</script>";



echo "	<script>";
echo "		function unitepayrollpayment() {";
echo "			var sourceID = selectedrowid" . $statementrowstable->getID() . ";";
echo "			var targetID = selectedrowid" . $table->getID() . ";";
echo "			console.log('unite sales - ' + sourceID + ' - ' + targetID);";


echo "			var sourceRowNumber = selectedrownumber" . $statementrowstable->getID() . ";";
echo "			var targetRowNumber = selectedrownumber" . $table->getID() . ";";
echo "			console.log('rownumbers - ' + sourceRowNumber + ' - ' + targetRowNumber);";

echo "			var sourceValueItem = '#tablerow-" . $statementrowstable->getID() . "-'+sourceRowNumber+'-" .  $sourceamountcolumn->getID() . "';";
echo "			var targetValueItem = '#tablerow-" . $table->getID() . "-'+targetRowNumber+'-" .  $targetamountcolumn->getID() . "';";

echo "			var sourceValue = $(sourceValueItem).val();";
echo "			var targetValue = $(targetValueItem).val();;";
echo "			console.log('values - ' + sourceValue + ' - ' + targetValue);";

echo "			var floatSourceValue = parseFloat(sourceValue);";
echo "			var floatTargetValue = parseFloat(targetValue);";
echo "			console.log('floats - ' + floatSourceValue + ' - ' + floatTargetValue);";

echo "			if (sourceValue < 0) {";
echo "				console.log('source smaller');";
echo "				var sourceValueAbs = Math.abs(sourceValue);";
echo "				if (sourceValueAbs == targetValue) {";
echo "					console.log(' +++ values  match');";
echo "					var url = '" . getUrl('accounting/alignment/linkpayrollpaymenttostatementrow') . "&statementrowID='+sourceID+'&paycheckID='+targetID;";
echo "					console.log(url);";
echo "					window.location = url;";
echo "				} else {";
echo "					console.log(' --- values not match');";
echo "				}";
echo "			}";
echo "			if (targetValue < 0) {";
echo "				console.log('target smaller - palkkamaksu ei voi olla tulorivi');";
echo "			}";
echo "		}";
echo "	</script>";



echo "	<script>";
echo "		function unitesalesinvoicelines() {";
echo "			var sourceID = selectedrowid" . $statementrowstable->getID() . ";";
echo "			var targetID = selectedrowid" . $table->getID() . ";";
echo "			console.log('unite sales - ' + sourceID + ' - ' + targetID);";


echo "			var sourceRowNumber = selectedrownumber" . $statementrowstable->getID() . ";";
echo "			var targetRowNumber = selectedrownumber" . $table->getID() . ";";
echo "			console.log('rownumbers - ' + sourceRowNumber + ' - ' + targetRowNumber);";

echo "			var sourceValueItem = '#tablerow-" . $statementrowstable->getID() . "-'+sourceRowNumber+'-" .  $sourceamountcolumn->getID() . "';";
echo "			var targetValueItem = '#tablerow-" . $table->getID() . "-'+targetRowNumber+'-" .  $targetamountcolumn->getID() . "';";

echo "			var sourceValue = $(sourceValueItem).val();";
echo "			var targetValue = $(targetValueItem).val();;";
echo "			console.log('values - ' + sourceValue + ' - ' + targetValue);";

echo "			var floatSourceValue = parseFloat(sourceValue);";
echo "			var floatTargetValue = parseFloat(targetValue);";
echo "			console.log('floats - ' + floatSourceValue + ' - ' + floatTargetValue);";

echo "			if (floatSourceValue == floatTargetValue) {";
echo "				console.log(' +++ values  match');";
echo "				var url = '" . getUrl('accounting/alignment/linksalesinvoicetostatementrow') . "&statementrowID='+sourceID+'&invoiceID='+targetID;";
echo "				console.log(url);";
echo "				window.location = url;";
echo "			} else {";
echo "				console.log(' --- values not match');";
echo "			}";
echo "		}";
echo "	</script>";

