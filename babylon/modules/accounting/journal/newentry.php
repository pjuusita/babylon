<?php

//phpinfo();
//echo "<br>cwd - " . getcwd();
//echo "<br>sitepath - " . SITE_PATH;
//echo "<br><br>";

include_once SITE_PATH . "modules/hr/timesheets/timesheet.class.php";
include_once SITE_PATH . "modules/hr/timesheets/timesheetcolumn.class.php";
include_once SITE_PATH . "modules/hr/timesheets/timesheetbutton.class.php";


$perustiedotsection = new UISection("Uusi kirjaus");
$perustiedotsection->setData($registry->entry);
$perustiedotsection->setOpen(true);

// UISection pitää korvata UIInsertSectionilla
//if ($registry->editopen) $perustiedotsection->setEdit(true);
$perustiedotsection->setUpdateAction(UIComponent::ACTION_FORWARD,'accounting/journal/updatejournalentry', 'entryID');

$typeselect = new UISelectField("Tositelaji", 'receiptsetID', 'ReceiptsetID', $this->registry->receiptsets);
$perustiedotsection->addField($typeselect);
$typeselect->setOnChange("typechanged()");

$receiptnumber	= new UITextField("Tositenumero","receiptnumber","Receiptnumber");
$receiptnumber->setEditActive(false);

$entrydate		= new UIDateField("Laskun paivays","entrydate","Entrydate");

$supplierField 	= new UISelectField("Maksun saaja","supplierID", "SupplierID", $this->registry->suppliers);
$supplierField->setPredictive(true);

$attachment	 	= new UIFileSelectField("Tosite","attachment","Attachment", "liitetiedosto");

$perustiedotsection->addField($receiptnumber);
$perustiedotsection->addField($entrydate);
$perustiedotsection->addField($supplierField);
$perustiedotsection->addField($attachment);
$perustiedotsection->show();

echo "<script>";
echo "	function typechanged() {";
echo "	 	var typefieldID 		 	 = '#".$typeselect->getEditFieldID()."';";
echo "	 	var receiptfieldID 		 	 = '#".$receiptnumber->getEditFieldID()."';";
echo "	 	var typeID = $(typefieldID).val();";
//echo "		alert('jejeeea - '+typeID);";
echo "		$.getJSON('".getUrl('accounting/journal/getfreenumber')."',{receiptset:typeID},function(reply) { ";
//echo "			alert('freenumber - '+reply[0].number);";
echo "			$(receiptfieldID).val(reply[0].number);";
echo "		});";

// TODO aktivoi number fieldi

echo "		return false;";
echo "	}";
echo "</script>";




$loadedrows	  = $this->registry->accountentries;

//echo "<br>Loadedrows - "  . count($loadedrows);

$isDisabled				= false;
$calculationAlgorithm	= "calculateEntrySums";
$databasePrimaryKeyName	= "entryID";

$splitAction			= "hr/timesheets/insertaccountentry";
$removeAction			= "hr/timesheets/removeaccountentryrow";

$journalentry 	  			= new TimeSheet($loadedrows,$isDisabled,$calculationAlgorithm,$databasePrimaryKeyName);

$journalentryselect			= new TimeSheetChangeDropdownContentColumn("Uusi kirjaus",$this->registry->accountselection,"accountID","name","accountID","accounting/journal/updateaccountselect");
$journalentry->setPredictive(true);
//$journalentryselect->setValidationFunction("selectedValueNotZero");

$accountentrydate			= new TimeSheetDatepickerColumn("Paivays",'entrydate',"isValidNumber",'accounting/journal/updateaccountentrydate');

$debet 						= new TimeSheetInputColumn("Debet",'debet',"isValidNumber",'accounting/journal/updateaccountentrydebet');
$credit 					= new TimeSheetInputColumn("Credit",'credit',"isValidNumber",'accounting/journal/updateaccountentrycredit');
$splitColumn				= new TimeSheetSplitColumn("+","Debet",'accounting/journal/insertentryrow&journalid=' . $this->registry->journalentryID . '');
$removeColumn				= new TimeSheetRemoveColumn("-","Debet","RemoveEntryRow",'accounting/journal/removeentryrow');


//$journalentry->addColumn($accountentrydate);
$journalentry->addColumn($journalentryselect);
$journalentry->addColumn($debet);
$journalentry->addColumn($credit);
$journalentry->addColumn($splitColumn);
$journalentry->addColumn($removeColumn);
$journalentry->setShowSheetTotal(true);

$sendButton					= new TimeSheetButton("Lisaa kirjaus","insert","InsertJournalEntry");
//TODO: päivitetty
//$button = new UIButton(UIComponent::ACTION_FORWARD, '/admin/database/showinsertcolumn&tableid=5', 'Lisää');
$journalentry->addButton($button);

//$button = new UIButton(UIComponent::ACTION_FORWARD, '/admin/database/showinsertcolumn&tableid=5', 'Lisää');

$journalentry->show();

echo "<script>";
echo "	function RemoveEntryRow(htmlRowElement) {";
echo "		if (dataRows.length > 2) return true;";
echo "		return false;";
echo "	}";
echo "</script>";

echo "<script>";
echo "	function InsertJournalEntry() {";
echo "			var lastrow = dataRows[dataRows.length-1];";
echo "			var debetsum = 0;";
echo "			var creditsum = 0;";
echo "			for(var dataIndex in lastrow) {";
echo "				var dataobject = lastrow[dataIndex];";
echo "				if (dataobject.name == 'Debet') debetsum = parseFloat(dataobject.getValue());";
echo "				if (dataobject.name == 'Credit') creditsum = parseFloat(dataobject.getValue())";
echo "			}";
//echo "			alert('debet - '+debetsum);";
echo "			if (debetsum == creditsum) {";
echo "				window.location = 'accounting/journal/acceptjournalentry&journalid=4';";
echo "			} else {";
echo "				alert('Debet ja Credit pitaa olla yhtasuuret');";
echo "			}";
echo "	}";
echo "</script>";


echo "<script>";
echo "		function calculateEntrySums(dataRows) {";
echo "			var debetsum = 0;";
echo "			var creditsum = 0;";
echo "			for(var rowIndex in dataRows) {";
echo "				var dataRow = dataRows[rowIndex];";
//echo "				var rowType = getRowType(dataRow);";
//echo "				alert('rowtype - '+rowType);";
echo "				for(var dataIndex in dataRow) {";
echo "					var dataobject = dataRow[dataIndex];";
echo "					if ((dataobject.name == 'Debet') && (dataobject.getClass() != 'TimeSpanColumn')) {";
//echo "						alert('debetti - '+dataobject.getValue());";
echo "						var value = dataobject.getValue();";
echo "						if (isValueValidNumber(value)) debetsum = debetsum + parseFloat(dataobject.getValue());";
echo "						";  // else error color
echo "					}";
echo "					if ((dataobject.name == 'Credit') && (dataobject.getClass() != 'TimeSpanColumn')) {";
echo "						var value = dataobject.getValue();";
echo "						if (isValueValidNumber(value)) creditsum = creditsum + parseFloat(dataobject.getValue());";
//echo "						creditsum = debetsum +  parseFloat(dataobject.getValue());";
echo "					}";
echo "				}";
echo "			}";
echo "			var lastrow = dataRows[dataRows.length-1];";
echo "			for(var dataIndex in lastrow) {";
echo "				var dataobject = lastrow[dataIndex];";
echo "				if (dataobject.name == 'Debet') dataobject.setValue(debetsum);";
echo "				if (dataobject.name == 'Credit') dataobject.setValue(creditsum);";
echo "			}";
echo "		}";
echo "</script>";

/*
echo "<a href='".getUrl('accounting/journal/showjournal')."'>Palaa tietokantalistaan</a>";

$insertSection = new UIInsertSection("Uusi Kirjaus");
$insertSection->setDebug(true);
$insertSection->setOpen(true);
$insertSection->setInsertAction('accounting/journal/insertentry', true);
$insertSection->setSuccessAction('accounting/journal/showjournal');  // TODO: Funktiota muutettu

echo "<br>referencetable name muutettu referencetableID";
exit();
			
$referenceselect = new UISelectField("Veloitustili", "referencetableID", 'ReferencetableID', $this->registry->accountselection);
$insertSection->addField($referenceselect);

$referenceselect = new UISelectField("Hyvitystili", "referencetableID", 'ReferencetableID', $this->registry->accountselection);
$insertSection->addField($referenceselect);

$insertSection->show();
*/


/*
echo "<div style='width:700px;background-color:#F8F8F8;border:0px ;'>";

echo "<form id='addform' action='".getUrl('taloushallinto/kirjanpito/addtodbtapahtuma')."' method='get'>";
echo "<input type='hidden' name='rt' value='taloushallinto/kirjanpito/addtodbtapahtuma'>";
echo "<fieldset>";
echo "<legend>Uusi tapahtuma</legend>";
echo "<table>";
echo "	<tr>";
echo "		<td style='width:100px;'>Paivamaara:</td>";
echo "		<td style='width:300px;'><input type='text' id='eventdate' onChange='dateselected()' name='eventdate' value=''></td>";
echo "		<td id='dateerror' style='color:red;padding-left:10px'></td>";
echo "	</tr>";

echo "<script>";
echo "	$('#eventdate').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
echo "</script>";
echo "<script>";
echo "	function dateselected() {";
echo "		$('#dateerror').text('');";
echo "	}";
echo "</script>";

echo "	<tr>";
echo "		<td style='width:100px'>Lahdetili:</td>";
echo "		<td style='width:300px'>";
echo "			<select style='width:300px' id='sourceaccount' onChange='sourceselected()' name='sourceaccount'>";
echo " 			<option value='0'></option>";
foreach ($this->registry->tilikartta as $tilikarttaID => $tilikartta) {
	palautatilit($tilikartta,0,'');
}
echo "			</select>";
echo "			<td id='sourceerror' style='color:red;padding-left:10px'></td>";
echo "		</td>";
echo "	</tr>";

echo "<script>";
echo "	function sourceselected() {";
echo "		$('#sourceerror').text('');";
echo "	}";
echo "</script>";

echo "	<tr>";
echo "		<td style='width:100px'>Kohdetili:</td>";
echo "		<td style='width:300px'>";
echo "			<select style='width:300px' id='targetaccount' onChange='targetselected()' name='targetaccount'>";
echo " 			<option value='0'></option>";
foreach ($this->registry->tilikartta as $tilikarttaID => $tilikartta) {
	palautatilit($tilikartta,0,'');
}
echo "			</select>";
echo "			<td id='targeterror' style='color:red;padding-left:10px'></td>";
echo "		</td>";
echo "	</tr>";

echo "<script>";
echo "	function targetselected() {";
echo "		$('#targeterror').text('');";
echo "	}";
echo "</script>";


echo "<script>";
echo "$('#sourceaccount').chosen({});";
echo "</script>";
echo "<script>";
echo "$('#targetaccount').chosen({});";
echo "</script>";

function palautatilit($tilikartta,$selectedtilikartta,$sisennys) {
	if ($selectedtilikartta == $tilikartta->getID()) {
		echo " 			<option selected value='".$tilikartta->getID()."'>".$sisennys.$tilikartta->name."</option>";
	} elseif ($tilikartta->getChildCount() == 0) {
		echo " 			<option value='".$tilikartta->getID()."'>".$sisennys.$tilikartta->name."</option>";
	} 	else {
		echo " 			<option value='".$tilikartta->getID()."'>".$sisennys.$tilikartta->name."</option>";
	}
	if ($tilikartta->getChildCount() > 0) {
		foreach ($tilikartta->getChilds() as $childtilikarttaID => $childtilikartta) {
			palautatilit($childtilikartta,$selectedtilikartta,$sisennys.'- ');
		}
	}

}

echo "	<tr>";
echo "		<td style='width:100px;'>Maara:</td>";
echo "		<td><input type='text' style='text-align:right;width:100px;padding-right:3px' id='amount' name='amount' value=''><td id='amounterror' style='color:red;padding-left:10px'></td></td>";
echo "	</tr>";

echo "<script>";
echo "$('#amount').blur(function(){";
echo "	var amount=$('#amount').val();";
echo "	if (/^[0-9]+[.|,]?[0-9]*$/.test(amount)) {";
echo "		$('#amounterror').text('');";
echo "	} else {";
echo "		$('#amounterror').text('Syata numero');";
echo "	}";
//echo "alert('sdf');";
echo "});";
echo "</script>";

echo "	<tr>";
echo "		<td colspan=2><input onClick='submitbutton()' type='button' value='tallenna'></td>";
echo "	</tr>";

echo "<script>";
echo "function submitbutton() {";
//echo "		alert('submitbutton');";
//echo "		return false;";
echo "	var success=true;";
echo "	if ($('#eventdate').val() == 0) {";
echo "		$('#dateerror').text('valitse paivamaara');";
echo "		success=false;";
echo "	}";
echo "	if ($('#sourceaccount').val() == 0) {";
echo "		$('#sourceerror').text('valitse lahdetili');";
echo "		success=false;";
echo "	}";
echo "	if ($('#targetaccount').val() == 0) {";
echo "		$('#targeterror').text('valitse kohdetili');";
echo "		success=false;";
echo "	}";
echo "	var amount=$('#amount').val();";
echo "	if (!/^-?[0-9]+[.|,]?[0-9]*$/.test(amount)) {";
echo "		$('#amounterror').text('Syata numero');";
echo "		success=false;";
echo "	}";
echo "	if (success) {";
echo "		$('#addform').submit();";
echo "	} else {";
echo "		return false;";
echo "	}";
echo "}";
echo "</script>";

echo "</table>";
echo "</fieldset>";
echo "</form>";

echo "</div>";
*/


?>