<?php


echo "<table style='width:700px;'>";
echo "	<tr>";
echo "		<td style='width:100%;text-align:right;'>";
//$field = new UISelectField("","rowID","name", $this->registry->selection, "name");
//$field = new UISelectField("Käyttäjä","userID","UserID", $registry->users, "username");
//$field->show($this->registry->selectedindex);

$selectedbankaccount = null;
echo "<select id=bankaccountselectfield class='field-select' style='width:120px;margin-right:5px;'>";
foreach($this->registry->bankaccounts as $index => $bankaccount) {
	if ($this->registry->bankaccountID ==  $bankaccount->bankaccountID) {
		echo "<option selected='selected' value='" . $index . "'>" . $bankaccount->name . "</option>";
		$selectedbankaccount = $bankaccount;
	} else {
		echo "<option value='" . $index . "'>" . $bankaccount->name . "</option>";
	}
}
echo "</select>";


echo "	<script>";
echo "		$('#bankaccountselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/bankstatements/showbankstatements')."&bankaccountID='+this.value;";
echo "		});";
echo "	</script>";


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
echo "			window.location='".getUrl('accounting/bankstatements/showbankstatements')."&periodID='+this.value;";
echo "		});";
echo "	</script>";

/*
echo "<select id=statementselectfield class='field-select' style='width:90px;margin-right:5px;'>";
echo "<option value='0'></option>";
foreach($this->registry->statements as $index => $value) {
	if ($this->registry->statementID ==  $value->bankstatementID) {
		echo "<option  selected='selected' value='" . $value->bankstatementID . "'>" . $value->name . "</option>";
	} else {
		echo "<option value='" . $value->bankstatementID . "'>" . $value->name . "</option>";
	}
}
echo "</select>";


echo "	<script>";
echo "		$('#statementselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/bankstatements/showbankstatements')."&statementID='+this.value;";
echo "		});";
echo "	</script>";

*/

/*
echo "			<input class=uitextfield id=startdatefield type='text'";
//echo "				onkeyup='editfieldchanged_" . $this->getID() . "()' onfocusin='editfielddeactivated_" . $this->getID() . "()'";
//echo " 				onfocusout='editfielddeactivated_" .$this->getID() . "()'";
echo " 				style='width:83px;margin-right:3px;' value='" . sqlDateToStr($this->registry->startdate) . "' readonly>";
echo "-";
echo "			<input class=uitextfield id=enddatefield type='text'";
//echo "				onkeyup='editfieldchanged_" . $this->getID() . "()' onfocusin='editfielddeactivated_" . $this->getID() . "()'";
//echo " 				onfocusout='editfielddeactivated_" .$this->getID() . "()'";
echo " 				style='width:83px;' value='" . sqlDateToStr($this->registry->enddate) . "' readonly>";
*/

/*
echo "	<script>";
echo "		$('#startdatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
echo "	</script>";

echo "	<script>";
echo "		$('#enddatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
echo "	</script>";

echo "		</td>";
echo "	</tr>";
*/

echo "</table>";




$insertstatement = new UISection("Tiliotteen lisäys");
$insertstatement->setDialog(true);
$insertstatement->setMode(UIComponent::MODE_INSERT);
$insertstatement->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bankstatements/insertbankstatement&periodID=' . $registry->periodID);


$field = new UISelectField("Pankkitili","bankaccountID","bankaccountID",$registry->bankaccounts, "name");
$insertstatement->addField($field);

$field = new UIDateField("Alkupäivä", "startdate", 'startdate');
$insertstatement->addField($field);

$field = new UIDateField("Loppupäivä", "enddate", 'enddate');
$insertstatement->addField($field);

$field = new UITextField("Alkusaldo", "startamount", 'startamount');
$insertstatement->addField($field);

$insertstatement->show();








$statementstable = new UITableSection("Tiliotteet","700px");
$statementstable->setOpen(true);
$statementstable->setFramesVisible(false);

$statementstable->setLineAction(UIComponent::ACTION_FORWARD,"accounting/bankstatements/showbankstatement","bankstatementID");
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertstatement->getID(), "Lisää tiliote");
$statementstable->addButton($button);

$column = new UISortColumn("#", "bankstatementID");
$statementstable->addColumn($column);

$column = new UISortColumn("Alkupäivä", "startdate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$statementstable->addColumn($column);

$column = new UISortColumn("Loppupäivä", "enddate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$statementstable->addColumn($column);

$column = new UISortColumn("Alkusaldo", "startamount", "startamount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$statementstable->addColumn($column);

$statementstable->setData($registry->statements);
$statementstable->show();



?>