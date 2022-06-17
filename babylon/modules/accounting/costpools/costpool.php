<?php

echo "<a href='".getUrl('accounting/costpools/showcostpools')."'>Palaa kustannupaikkalistaan</a><br>";
echo "<br>";

$section = new UISection("Kustannuspaikan tiedot", "700px");
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'accounting/costpools/updatecostpool', 'costpoolID');
$section->setWidths("20%","49%","31%");

//$field = new UISelectField("Parent", "parentID", 'parentID', $registry->costpools, 'name');
//$field->setPredictive(true);
//$section->addField($field);

$field = new UITextField("Name", "name", 'name');
$section->addField($field);

$column = new UISelectField("Menotili", "expenseaccountID", "expenseaccountID", $registry->accounts, 'fullname');
$column->setPredictive(true);
$section->addField($column);

//$column = new UISelectField("Tulotili", "incomeaccountID", "incomeaccountID", $registry->accounts, 'fullname');
//$column->setPredictive(true);
//$section->addField($column);

$column = new UISelectField("Oletus ALV", "vatID", "vatID", $registry->vats, 'name');
$section->addField($column);


$column = new UISelectField("Jaottelut", "costpooltype", "costpooltype", $registry->costpooltypes);
$section->addField($column);

$section->setData($registry->costpool);

$section->show();




$insertcostpoolaccount = new UISection("Kustannuspaikkatyypin arvon lisäys");
$insertcostpoolaccount->setDialog(true);
$insertcostpoolaccount->setMode(UIComponent::MODE_INSERT);
$insertcostpoolaccount->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/costpools/insertcostpoolaccount&costpoolID=' . $registry->costpool->costpoolID);


$field = new UITextField("Nimi", 'name', 'name');
$insertcostpoolaccount->addField($field);

$column = new UISelectField("Kirjanpitotili", "accountID", "accountID", $registry->accounts, 'fullname');
$column->setPredictive(true);
$insertcostpoolaccount->addField($column);

$column = new UISelectField("ALV", "vatID", "vatID", $registry->vats, 'short');
$insertcostpoolaccount->addField($column);

$insertcostpoolaccount->show();



$editcostpoolaccount = new UISection("Kustannuspaikkatilin muokkaus");
$editcostpoolaccount->setDialog(true);
$editcostpoolaccount->setMode(UIComponent::MODE_INSERT);
$editcostpoolaccount->setUpdateAction(UIComponent::ACTION_FORWARD, 'accounting/costpools/updatecostpoolaccount&costpoolID=' . $registry->costpool->costpoolID, 'rowID');

$field = new UITextField("Nimi", 'name', 'name');
$editcostpoolaccount->addField($field);

$column = new UISelectField("Kirjanpitotili", "accountID", "accountID", $registry->accounts, 'fullname');
$column->setPredictive(true);
$editcostpoolaccount->addField($column);

$column = new UISelectField("ALV", "vatID", "vatID", $registry->vats, 'short');
$editcostpoolaccount->addField($column);

$editcostpoolaccount->show();





$insertexpendituredialog = new UISection("Kustannuserän lisäys");
$insertexpendituredialog->setDialog(true);
$insertexpendituredialog->setMode(UIComponent::MODE_INSERT);
$insertexpendituredialog->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/costpools/insertexpenditure&costpoolID=' . $registry->costpool->costpoolID);

$namefield = new UITextField("Name", "name", 'name');
$insertexpendituredialog->addField($namefield);

$column = new UISelectField("Kirjanpitotili", "accountID", "accountID", $registry->accounts, 'fullname');
$column->setPredictive(true);
$insertexpendituredialog->addField($column);

$column = new UISelectField("Oletus ALV", "vatID", "vatID", $registry->vats, 'name');
$column->setPredictive(true);
$insertexpendituredialog->addField($column);

$insertexpendituredialog->show();





$expenditurestable = new UITableSection("Kustannuserät","700px");
$expenditurestable->setOpen(true);
$expenditurestable->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertexpendituredialog->getID(), "Lisää kustannuserä");
$expenditurestable->addButton($button);

$column = new UISortColumn("#", "expenditureID");
$expenditurestable->addColumn($column);

$column = new UISortColumn("Nimi", "name", "name");
$expenditurestable->addColumn($column);

$column = new UISelectColumn("Kirjanpitotili", "fullname", "accountID", $registry->accounts);
$expenditurestable->addColumn($column);

$column = new UISelectColumn("ALV", "name", "vatID", $registry->vats);
$expenditurestable->addColumn($column);

$expenditurestable->setData($registry->expenditures);
$expenditurestable->show();





$managementSection = new UISection("Hallinta", "700px");
$managementSection->editable(false);
$managementSection->setDebug(true);
$managementSection->setOpen(false);

// TODO: poista poistaa kustannuspaikan kokonaan, disablointi vain poistaa uudelleen valinta mahdollisuuden
//		 pitäisi periaatteessa tehdä niin, että poisto tarkistaa onko viitteitä.

$button = new UIButton(UIComponent::ACTION_FORWARD, "accounting/costpools/removecostpool&costpoolID=" . $registry->costpool->costpoolID, "Poista kustannuspaikka");
$managementSection->addButton($button);

$button = new UIButton(UIComponent::ACTION_FORWARD, "accounting/costpools/disablecostpool&costpoolID=" . $registry->costpool->costpoolID, "Arkistoi kustannuspaikka");
$managementSection->addButton($button);

$managementSection->show();





if (count($this->registry->purchaserows) > 0) {
	echo "<br>purchaserows - " . count($this->registry->purchaserows);
	foreach($this->registry->purchaserows as $index => $value) {
		echo "<br> -- RowID:" . $value->rowID . ", PurchaseID:" . $value->purchaseID;
	}
} else {
	echo "<br>purchaserows - 0";
}

if (count($this->registry->defaultpurchaserows) > 0) {
	echo "<br>defaultpurchaserows - " . count($this->registry->defaultpurchaserows);
	foreach($this->registry->defaultpurchaserows as $index => $value) {
		echo "<br> -- RowID:" . $value->rowID;
	}
} else {
	echo "<br>defaultpurchaserows - 0";
}

if (count($this->registry->entries) > 0) {
	echo "<br>entries - " . count($this->registry->entries);
	foreach($this->registry->entries as $index => $value) {
		echo "<br> -- EntryID:" . $value->entryID . ", ReceiptID:" . $value->receiptID;
	}
} else {
	echo "<br>entries - 0";
}

if (count($this->registry->purchases) > 0) {
	echo "<br>purchases - " . count($this->registry->purchases);
	foreach($this->registry->purchases as $index => $value) {
		echo "<br> -- PurchaseID:" . $value->purchaseID;
	}
} else {
	echo "<br>purchases - 0";
}

if (count($this->registry->workers) > 0) {
	echo "<br>workers - " . count($this->registry->workers);
	foreach($this->registry->workers as $index => $value) {
		echo "<br> -- WorkerID:" . $value->workerID;
	}
} else {
	echo "<br>workers - 0";
}


?>