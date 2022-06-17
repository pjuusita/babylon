<?php


echo "<table style='width:700px;'>";
echo "	<tr>";
echo "		<td style='width:100%;text-align:right;'>";
//$field = new UISelectField("","rowID","name", $this->registry->selection, "name");
//$field = new UISelectField("Käyttäjä","userID","UserID", $registry->users, "username");
//$field->show($this->registry->selectedindex);

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
echo "			window.location='".getUrl('accounting/receipts/showreceipts')."&periodID='+this.value;";
echo "		});";
echo "	</script>";


echo "<select id=receiptserieselect class='field-select' style='width:120px;margin-right:5px;'>";
echo "<option value='0'></option>";
foreach($this->registry->receiptsets as $index => $receiptset) {
	if ($this->registry->receiptsetID ==  $receiptset->receiptsetID) {
		echo "<option  selected='selected' value='" . $index . "'>" . $receiptset->name . "</option>";
	} else {
		echo "<option value='" . $index . "'>" . $receiptset->name . "</option>";
	}
}
echo "</select>";


echo "	<script>";
echo "		$('#receiptserieselect').on('change', function() {";
echo "			window.location='".getUrl('accounting/receipts/showreceipts')."&receiptsetID='+this.value;";
echo "		});";
echo "	</script>";



echo "<select class='field-select' style='width:120px;margin-right:5px;'>";
echo "<option value='0'></option>";
foreach($this->registry->receipts as $index => $value) {
	if ($this->registry->selectionID ==  $value->rowID) {
		echo "<option  selected='selected' value='" . $value->rowID . "'>" . $value->receiptnumber . " - " . $value->receiptdate . "</option>";
	} else {
		echo "<option value='" . $value->rowID . "'>" . $value->name . "</option>";
	}
}
echo "</select>";



echo "			<input class=uitextfield id=startdatefield type='text'";
//echo "				onkeyup='editfieldchanged_" . $this->getID() . "()' onfocusin='editfielddeactivated_" . $this->getID() . "()'";
//echo " 				onfocusout='editfielddeactivated_" .$this->getID() . "()'";
echo " 				style='width:83px;margin-right:3px;' value='" . sqlDateToStr($this->registry->startdate) . "' readonly>";
echo "-";
echo "			<input class=uitextfield id=enddatefield type='text'";
//echo "				onkeyup='editfieldchanged_" . $this->getID() . "()' onfocusin='editfielddeactivated_" . $this->getID() . "()'";
//echo " 				onfocusout='editfielddeactivated_" .$this->getID() . "()'";
echo " 				style='width:83px;' value='" . sqlDateToStr($this->registry->enddate) . "' readonly>";

echo "	<script>";
echo "		$('#startdatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
echo "	</script>";

echo "	<script>";
echo "		$('#enddatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
echo "	</script>";

echo "		</td>";
echo "	</tr>";
echo "</table>";



$insertentry = new UISection("Viennin lisäys");
$insertentry->setDialog(true);
$insertentry->setMode(UIComponent::MODE_INSERT);
$insertentry->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/entries/insertentry&receiptID=' . $registry->receipt->receiptID);

$field = new UISelectField("Tili","accountID","accountID",$registry->accounts, "fullname");
$field->setPredictive(true);
$insertentry->addField($field);

$field = new UIDateField("Vientipäivä", "entrydate", 'entrydate');
$insertentry->addField($field);

$field = new UISelectField("Maksutapa","paymentmethodID","paymentmethodID",$registry->paymentmethods, "name");
$insertentry->addField($field);

$field = new UISelectField("ALV","accountID","vatID",$registry->vats, "name");
$insertentry->addField($field);

$field = new UITextField("Brutto", "amount", 'amount');
$insertentry->addField($field);

$empty = new Row();
$empty->entrydate = $registry->lastentrydate;
//$empty->amount = $registry->receipt->grossamount;
$empty->accountID = 14;

$insertentry->setData($empty);

$insertentry->show();



$entriestable = new UITableSection("Viennit","700px");
$entriestable->setOpen(true);
$entriestable->setFramesVisible(false);

//$entriestable->setDeleteAction(UIComponent::ACTION_FORWARD, 'accounting/receipts/removeentry&receiptID=' . $registry->receipt->receiptID, 'entryID');

//$table->setLineAction(UIComponent::ACTION_FORWARD,"accounting/accountingyear/showaccountingyear","periodID");
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertentry->getID(), "Lisää vienti");
$entriestable->addButton($button);

//$startcolumn = new UISortColumn("Alkaa", "startdate", "");
//$startcolumn->setFormatter(Column::COLUMNTYPE_DATE);
//$table->addColumn($startcolumn);


$column = new UISortColumn("Päiväys", "entrydate");
$column->setFormatter(Column::COLUMNTYPE_DATE);
$entriestable->addColumn($column);


$column = new UISelectColumn("Tili", "name", "accountID", $registry->accounts);
$entriestable->addColumn($column);

$column = new UISortColumn("Määrä", "amount", "amount");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$entriestable->addColumn($column);


$column = new UISortColumn("Saldo", "total", "total");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$entriestable->addColumn($column);

//$column = new UISortColumn("Debet", "debet", "debet");
//$column->setAlign(Column::ALIGN_RIGHT);
//$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
//$entriestable->addColumn($column);

//$column = new UISortColumn("Credit", "credit", "credit");
//$column->setAlign(Column::ALIGN_RIGHT);
//$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
//$entriestable->addColumn($column);

$entriestable->setData($registry->entries);
$entriestable->show();



?>