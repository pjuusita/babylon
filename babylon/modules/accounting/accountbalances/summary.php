<?php




echo "<br>Accounts count - " . count($this->registry->accounts);


echo "<table style='width:700px;'>";
echo "	<tr>";
echo "		<td style='width:100%;text-align:right;'>";
//$field = new UISelectField("","rowID","name", $this->registry->selection, "name");
//$field = new UISelectField("Käyttäjä","userID","UserID", $registry->users, "username");
//$field->show($this->registry->selectedindex);

echo "<select id=periodselectfield  class='top-select' style='width:321px;margin-right:5px;'>";
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
echo "			window.location='".getUrl('accounting/accountbalances/showaccountbalance')."&periodID='+this.value;";
echo "		});";
echo "	</script>";


echo "		</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td style='width:100%;text-align:right;padding-top:3px;'>";

echo "<div style='width:322px;float:right;padding-right:5px;'>";
echo "<select id=accountselectfield  class='top-select'  style='width:321px;margin-right:5px;'>";
echo "<option value='0'>Kaikki</option>";
foreach($this->registry->accounts as $index => $value) {
	if ($this->registry->selectedaccountID ==  $value->accountID) {
		echo "<option  selected='selected' value='" . $value->accountID . "'>" . $value->fullname . "</option>";
	} else {
		echo "<option value='" . $value->accountID . "'>" . $value->fullname . "</option>";
	}
}
echo "</select>";
echo "</div>";

echo "	<script>";
echo "		$(document).ready(function() {";
echo "			$('#accountselectfield').chosen();";
echo "			$('#accountselectfield').css('width','321px');";
echo "		});";
echo "	</script>";


echo "	<script>";
echo "		$('#accountselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/accountbalances/showaccountbalance')."&selectedaccountID='+this.value;";
echo "		});";
echo "	</script>";


/*
 echo "<select class='field-select' style='width:120px;margin-right:5px;'>";
 echo "<option value='0'></option>";
 foreach($this->registry->receiptsets as $index => $receiptset) {
 if ($this->registry->receiptsetID ==  $receiptset->receiptsetID) {
 echo "<option  selected='selected' value='" . $index . "'>" . $receiptset->name . "</option>";
 } else {
 echo "<option value='" . $index . "'>" . $receiptset->name . "</option>";
 }
 }
 echo "</select>";
 */


echo "		</td>";
echo "	</tr>";
echo "	<tr>";
echo "		<td style='width:100%;text-align:right;padding-top:3px;'>";

echo "<select id=selectionselect  class='top-select' style='width:120px;margin-right:5px;'>";
echo "<option value='0'></option>";
foreach($this->registry->selection as $index => $value) {
	if ($this->registry->selectionID ==  $value->rowID) {
		echo "<option  selected='selected' value='" . $value->rowID . "'>" . $value->name . "</option>";
	} else {
		echo "<option value='" . $value->rowID . "'>" . $value->name . "</option>";
	}
}
echo "</select>";

echo "	<script>";
echo "		$('#selectionselect').on('change', function() {";
echo "			window.location='".getUrl('accounting/accountbalances/showaccountbalance')."&selectionID='+this.value;";
echo "		});";
echo "	</script>";



echo "			<input  class='top-select'  id=startdatefield type='text'";
//echo "				onkeyup='editfieldchanged_" . $this->getID() . "()' onfocusin='editfielddeactivated_" . $this->getID() . "()'";
//echo " 				onfocusout='editfielddeactivated_" .$this->getID() . "()'";
echo " 				style='width:90px;margin-right:3px;' value='" . sqlDateToStr($this->registry->startdate) . "' readonly>";
echo "-";
echo "			<input  class='top-select'  id=enddatefield type='text'";
//echo "				onkeyup='editfieldchanged_" . $this->getID() . "()' onfocusin='editfielddeactivated_" . $this->getID() . "()'";
//echo " 				onfocusout='editfielddeactivated_" .$this->getID() . "()'";
echo " 				style='width:90px;margin-right:5px;' value='" . sqlDateToStr($this->registry->enddate) . "' readonly>";

echo "	<script>";
echo "		$('#startdatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
echo "	</script>";

echo "	<script>";
echo "		$('#enddatefield').datepicker({dateFormat: 'dd.mm.yy', firstDay: 1});";
echo "	</script>";

echo "		</td>";
echo "	</tr>";
echo "</table>";


/*

echo "<table style='width:700px;'>";
echo "	<tr>";
echo "		<td style='width:100%;text-align:right;'>";
echo "			<select id=periodselectfield class='field-select' style='width:120px;margin-right:5px;'>";
foreach($this->registry->periods as $index => $period) {
	if ($this->registry->periodID ==  $period->periodID) {
		echo "		<option selected='selected' value='" . $index . "'>" . $period->fullname . "</option>";
	} else {
		echo "		<option value='" . $index . "'>" . $period->fullname . "</option>";
	}
}
echo "			</select>";

echo "	<script>";
echo "		$('#periodselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/incomestatement/showincomestatement')."&periodID='+this.value;";
echo "		});";
echo "	</script>";


echo "			<select id=selectionselectfield class='field-select' style='width:120px;margin-right:5px;'>";
echo "				<option value='0'></option>";
foreach($this->registry->selection as $index => $value) {
	if ($this->registry->selectionID ==  $value->rowID) {
		echo "		<option  selected='selected' value='" . $value->rowID . "'>" . $value->name . "</option>";
	} else {
		echo "		<option value='" . $value->rowID . "'>" . $value->name . "</option>";
	}
}
echo "			</select>";

echo "	<script>";
echo "		$('#selectionselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/incomestatement/showincomestatement')."&selectionID='+this.value;";
echo "		});";
echo "	</script>";


echo "			<input class=uitextfield id=startdatefield type='text'";
echo " 				style='width:83px;margin-right:3px;' value='" . sqlDateToStr($this->registry->startdate) . "' readonly>";
echo "-";
echo "			<input class=uitextfield id=enddatefield type='text'";
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



echo "	<script>";
echo "		$('#accountselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/accountbalances/showaccountbalances')."&periodID='+this.value;";
echo "		});";
echo "	</script>";

*/




$incometable = new UITableSection("Tulot","700px");
$incometable->setOpen(true);
$incometable->setFramesVisible(false);
$incometable->setLineAction(UIComponent::ACTION_FORWARD,"accounting/receipts/showreceipt","receiptID");

$field = new UISortColumn("Tilinumero", "number");
$incometable->addColumn($field);
	
$field = new UISortColumn("Tilinnimi", "name");
$incometable->addColumn($field);

$column = new UISortColumn("Saldo", "total");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$incometable->addColumn($column);
	
$incometable->setData($this->registry->tulotilit);
$incometable->show();




$expencestable = new UITableSection("Menot","700px");
$expencestable->setOpen(true);
$expencestable->setFramesVisible(false);
$expencestable->setLineAction(UIComponent::ACTION_FORWARD,"accounting/receipts/showreceipt","receiptID");

$field = new UISortColumn("Tilinumero", "number");
$expencestable->addColumn($field);
	
$field = new UISortColumn("Tilinnimi", "name");
$expencestable->addColumn($field);

$column = new UISortColumn("Saldo", "total");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$expencestable->addColumn($column);
	
$expencestable->setData($this->registry->menotilit);
$expencestable->show();



$expencestable = new UITableSection("Omaisuus","700px");
$expencestable->setOpen(true);
$expencestable->setFramesVisible(false);
$expencestable->setLineAction(UIComponent::ACTION_FORWARD,"accounting/receipts/showreceipt","receiptID");

$field = new UISortColumn("Tilinumero", "number");
$expencestable->addColumn($field);

$field = new UISortColumn("Tilinnimi", "name");
$expencestable->addColumn($field);

$column = new UISortColumn("Saldo", "total");
$column->setAlign(Column::ALIGN_RIGHT);
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$expencestable->addColumn($column);

$expencestable->setData($this->registry->omaisuustilit);
$expencestable->show();


$expencestable = new UITableSection("Velat","700px");
$expencestable->setOpen(true);
$expencestable->setFramesVisible(false);
$expencestable->setLineAction(UIComponent::ACTION_FORWARD,"accounting/receipts/showreceipt","receiptID");

$field = new UISortColumn("Tilinumero", "number");
$expencestable->addColumn($field);

$field = new UISortColumn("Tilinnimi", "name");
$expencestable->addColumn($field);

$column = new UISortColumn("Saldo", "total");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$expencestable->addColumn($column);

$expencestable->setData($this->registry->velkatilit);
$expencestable->show();


$expencestable = new UITableSection("Muut tilit","700px");
$expencestable->setOpen(true);
$expencestable->setFramesVisible(false);
$expencestable->setLineAction(UIComponent::ACTION_FORWARD,"accounting/receipts/showreceipt","receiptID");

$field = new UISortColumn("Tilinumero", "number");
$expencestable->addColumn($field);

$field = new UISortColumn("Tilinnimi", "name");
$expencestable->addColumn($field);

$column = new UISortColumn("Saldo", "total");
$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
$column->setAlign(Column::ALIGN_RIGHT);
$expencestable->addColumn($column);

$expencestable->setData($this->registry->muuttilit);
$expencestable->show();

?>