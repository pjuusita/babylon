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
echo "			window.location='".getUrl('accounting/accountbalances/showaccountbalances')."&periodID='+this.value;";
echo "		});";
echo "	</script>";


echo "<br>Accounts count - " . count($this->registry->accounts);


echo "<select Id=accountselectfield class='field-select' style='width:120px;margin-right:5px;'>";
echo "<option value='0'>Kaikki</option>";
foreach($this->registry->accounts as $index => $value) {
	if ($this->registry->currentaccountID ==  $value->accountID) {
		echo "<option  selected='selected' value='" . $value->accountID . "'>" . $value->fullname . "</option>";
	} else {
		echo "<option value='" . $value->accountID . "'>" . $value->fullname . "</option>";
	}
}
echo "</select>";


echo "	<script>";
echo "		$('#accountselectfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/accountbalances/showaccountbalances')."&periodID='+this.value;";
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

echo "<select class='selectionfield' style='width:120px;margin-right:5px;'>";
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
echo "		$('#selectionfield').on('change', function() {";
echo "			window.location='".getUrl('accounting/accountbalances/showaccountbalances')."&selection='+this.value;";
echo "		});";
echo "	</script>";




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


$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->currentaccountID, $this->registry->accounts, "accounting/accountbalances/showaccountbalances", "Kaikki","accountID", "name");
$filterbox->setEmptySelect(false);


/*
echo "<table style='width:700px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";

echo "<select Id=accountselectfield class='field-select' style='width:120px;margin-right:5px;'>";
echo "<option value='0'>Kaikki</option>";
foreach($this->registry->accounts as $index => $value) {
	if ($this->registry->currentaccountID ==  $value->accountID) {
		echo "<option  selected='selected' value='" . $value->accountID . "'>" . $value->name . "</option>";
	} else {
		echo "<option value='" . $value->accountID . "'>" . $value->name . "</option>";
	}
}
echo "</select>";



//$filterbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";
*/






if ($this->registry->currentaccount == null) {
	//$table = new UITableSection("Ei tiliä valittu","700px");

	foreach($this->registry->accounts as $accountID => $account) {
		
		echo "<br>";
		//echo "<br>Account - " . $account->name . ", " . $account->accountID;
		//echo "<br>Accounttype - " . $account->accounttypeID;
		$accounttype = $this->registry->accounttypes[$account->accounttypeID];
		
		$table = new UITableSection("" . $account->number . " " . $account->name . " (" . $accounttype->name . ") --- " . $account->number . " --- " . $account->accountID, "700px");
		$table->setOpen(true);
		$table->setFramesVisible(false);
		
		$column = new UISortColumn("Päiväys", "entrydate");
		$column->setFormatter(Column::COLUMNTYPE_DATE);
		$table->addColumn($column);
		
		$column = new UISortColumn("Tositenumero", "receiptnumber");
		$table->addColumn($column);
		
		$column = new UISelectColumn("Kustannuspaikka", "name", "costpoolID", $this->registry->costpools);
		$table->addColumn($column);
		
		$column = new UISortColumn("Brutto", "amount");
		$column->setAlign(Column::ALIGN_RIGHT);
		$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
		$table->addColumn($column);
		
		$column = new UISortColumn("Saldo", "balance");
		$column->setAlign(Column::ALIGN_RIGHT);
		$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
		$table->addColumn($column);

		//echo "<br>Searching - " . $account->accountID;
		$entries = array();
		foreach($this->registry->allentries as $index => $entry) {
			if ($entry->accountID == $account->accountID) {
				//echo "<br>Yes match - " . $entry->accountID;
				if ($accounttype->credit == 1) {
					$entry->amount = -1 * $entry->amount;
					$entry->balance = -1 * $entry->balance;
				} 
				$entries[] = $entry;
			} else {
				//echo "<br>No match - " . $entry->accountID;
			}
		}
		$table->setData($entries);
		$table->show();
		
	}

} else {
	$table = new UITableSection("" . $this->registry->currentaccount->number . " " . $this->registry->currentaccount->name,"700px");
	$table->setOpen(true);
	$table->setFramesVisible(false);
	$table->setLineAction(UIComponent::ACTION_FORWARD,"accounting/receipts/showreceipt","receiptID");
	
	//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää tosite");
	//$table->addButton($button);
	
	$column = new UISortColumn("Päiväys", "entrydate");
	$column->setFormatter(Column::COLUMNTYPE_DATE);
	$table->addColumn($column);
	
	$column = new UISortColumn("Tositenumero", "receiptnumber");
	$table->addColumn($column);
	
	$column = new UISelectColumn("Kustannuspaikka", "name", "costpoolID", $this->registry->costpools);
	$table->addColumn($column);
	
	$column = new UISortColumn("Brutto", "amount");
	$column->setAlign(Column::ALIGN_RIGHT);
	$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	$table->addColumn($column);
	
	$column = new UISortColumn("Saldo", "balance");
	$column->setAlign(Column::ALIGN_RIGHT);
	$column->setFormatter(Column::COLUMNTYPE_FLOAT, 2);
	$table->addColumn($column);
	
	$table->setData($this->registry->entries);
	$table->show();
}


?>