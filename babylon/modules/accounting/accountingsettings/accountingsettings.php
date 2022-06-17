<?php



echo "<h1>Rahaliikenteen asetukset</h1>";

$width = "700px";

$bankaccountinsert = new UISection("Pankkitilin lisäys");
$bankaccountinsert->setDialog(true);
$bankaccountinsert->setMode(UIComponent::MODE_INSERT);
$bankaccountinsert->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/accountingsettings/insertbankaccount');

$nimifield = new UITextField("Nimi", "Nimi", 'name');
$bankaccountinsert->addField($nimifield);

$nimifield = new UITextField("IBAN", "IBAN", 'iban');
$bankaccountinsert->addField($nimifield);

$field = new UISelectField("Tili","accountID","accountID",$registry->accounts, "fullname");
$bankaccountinsert->addField($field);

$field = new UISelectField("Myyntisaamistili","receivablesaccountID","receivablesaccountID",$registry->accounts, "fullname");
$bankaccountinsert->addField($field);

$field = new UISelectField("Ostovelkatili","payablesaccountID","payablesaccountID",$registry->accounts, "fullname");
$bankaccountinsert->addField($field);

$bankaccountinsert->show();





$bankaccountedit = new UISection("Pankkitilin muokkaus");
$bankaccountedit->setDialog(true);
$bankaccountedit->setMode(UIComponent::MODE_INSERT);
$bankaccountedit->setUpdateAction(UIComponent::ACTION_FORWARD, 'accounting/accountingsettings/updatebankaccount', "bankaccountID");

$nimifield = new UITextField("Nimi", "Nimi", 'name');
$bankaccountedit->addField($nimifield);

$nimifield = new UITextField("IBAN", "IBAN", 'iban');
$bankaccountedit->addField($nimifield);

$field = new UISelectField("Kirjanpitotili","accountID","accountID",$registry->accounts, "fullname");
$bankaccountedit->addField($field);

$field = new UISelectField("Myyntisaamistili","receivablesaccountID","receivablesaccountID",$registry->accounts, "fullname");
$bankaccountedit->addField($field);

$field = new UISelectField("Ostovelkatili","payablesaccountID","payablesaccountID",$registry->accounts, "fullname");
$bankaccountedit->addField($field);

$bankaccountedit->show();



$table = new UITableSection("Pankkitilit",$width);
$table->setOpen(true);
$table->setFramesVisible(true);


$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $bankaccountedit->getID(), "bankaccountID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $bankaccountinsert->getID(), "Lisää uusi");
$table->addButton($button);


$column = new UISortColumn("Nimike", "name", 'name');
$table->addColumn($column);

$column = new UISortColumn("Iban", "iban", 'iban');
$table->addColumn($column);

$column = new UISelectColumn("Kirjanpitotili", "number", "accountID", $registry->accounts);
$table->addColumn($column);

//$column = new UISelectColumn("Myyntisaamiset", "number", "receivablesaccountID", $registry->accounts);
$column = new UIHiddenColumn("Myyntisaamiset", "receivablesaccountID", 'receivablesaccountID');
$table->addColumn($column);

//$column = new UISelectColumn("Ostovelat", "number", "payablesaccountID", $registry->accounts);
$column = new UIHiddenColumn("Ostovelat", "payablesaccountID", 'payablesaccountID');
$table->addColumn($column);

$column = new UIHiddenColumn("AccountID", "bankaccountID", 'bankaccountID');
$table->addColumn($column);

$table->setData($registry->bankaccounts);
$table->show();






$paymentcardinsert = new UISection("Maksukortin lisäys");
$paymentcardinsert->setDialog(true);
$paymentcardinsert->setMode(UIComponent::MODE_INSERT);
$paymentcardinsert->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/accountingsettings/insertpaymentcard');

$field = new UISelectField("Pankkitili","bankaccountID","bankaccountID",$registry->bankaccounts, "name");
$paymentcardinsert->addField($field);

$field = new UITextField("Numero", "number", 'number');
$paymentcardinsert->addField($field);

$field = new UISelectField("Luottotili","creditaccountID","creditaccountID",$registry->accounts, "fullname");
$field->setPredictive(true);
$paymentcardinsert->addField($field);

$field = new UISelectField("Haltija","workerID","workerID",$registry->workers, "fullname");
$paymentcardinsert->addField($field);

$paymentcardinsert->show();





$paymentcardedit = new UISection("Maksukortin muokkaus");
$paymentcardedit->setDialog(true);
$paymentcardedit->setMode(UIComponent::MODE_INSERT);
$paymentcardedit->setUpdateAction(UIComponent::ACTION_FORWARD, 'accounting/accountingsettings/updatepaymentcard', "cardID");

$field = new UISelectField("Pankkitili","bankaccountID","bankaccountID",$registry->bankaccounts, "name");
$paymentcardedit->addField($field);

$field = new UITextField("Numero", "number", 'number');
$paymentcardedit->addField($field);

$field = new UISelectField("Luottotili","creditaccountID","creditaccountID",$registry->accounts, "fullname");
$field->setPredictive(true);
$paymentcardedit->addField($field);

$field = new UISelectField("Haltija","workerID","workerID",$registry->workers, "fullname");
$paymentcardedit->addField($field);

$paymentcardedit->show();



$table = new UITableSection("Maksukortit",$width);		// pankki/luottokortit
$table->setOpen(true);
$table->setFramesVisible(true);


$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $paymentcardedit->getID(), "bankaccountID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $paymentcardinsert->getID(), "Lisää uusi");
$table->addButton($button);

$column = new UISortColumn("Numero", "number", 'number');	// Visa-kortti, Osuuspankki Debet, Osuuspankki luottokortti
$table->addColumn($column);

$column = new UISelectColumn("Pankkitili", "name", "bankaccountID", $registry->bankaccounts);
$table->addColumn($column);

$column = new UISelectColumn("Haltija", "fullname", "workerID", $registry->workers);
$table->addColumn($column);

$column = new UISelectColumn("Luottotili", "number", "creditaccountID", $registry->accounts);
$table->addColumn($column);

$column = new UIHiddenColumn("CardID", "cardID", 'cardID');
$table->addColumn($column);

$table->addColumn($column);

$table->setData($registry->paymentcards);
$table->show();



// ------------------------------------------------------------------------
//		Maksutavat
// ------------------------------------------------------------------------

$selection = array();
foreach($registry->duedateselectionshort as $index => $value) {
	$row = new Row();
	$row->duedateusage = $index;
	$row->name = $value;
	$row->fullname = $registry->duedateselection[$index];
	$selection[$index] = $row;
}


$insertpaymentmethod = new UISection("Ostolaskutyypin lisäys");
$insertpaymentmethod->setDialog(true);
$insertpaymentmethod->setMode(UIComponent::MODE_INSERT);
$insertpaymentmethod->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/accountingsettings/insertpaymentmethod');

$field = new UITextField("Nimike", "Nimike", 'name');
$insertpaymentmethod->addField($field);

$field = new UISelectField("Tili","accountID","accountID",$registry->accounts, "fullname");
$insertpaymentmethod->addField($field);

$field = new UISelectField("Eräpäivä","duedateusage","duedateusage",$selection, "fullname");
$insertpaymentmethod->addField($field);

$insertpaymentmethod->show();



$updatepaymentmethod = new UISection("Ostolaskutyypin muokkaus");
$updatepaymentmethod->setDialog(true);
$updatepaymentmethod->setMode(UIComponent::MODE_INSERT);
$updatepaymentmethod->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/accountingsettings/updatepaymentmethod', 'paymentmethodID');

$field = new UITextField("Nimike", "Nimike", 'name');
$updatepaymentmethod->addField($field);

$field = new UISelectField("Tili","accountID","accountID",$registry->accounts, "fullname");
$field->setPredictable(true);
$updatepaymentmethod->addField($field);

$field = new UISelectField("Eräpäivä","duedateusage","duedateusage",$selection, "fullname");
$updatepaymentmethod->addField($field);

$updatepaymentmethod->show();



$table = new UITableSection("Ostolaskun tyypit",$width);
$table->setOpen(true);
$table->setFramesVisible(true);

$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $updatepaymentmethod->getID());
$table->setDeleteAction(UIComponent::ACTION_FORWARD, 'accounting/accountingsettings/removepaymentmethod', 'paymentmethodID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertpaymentmethod->getID(), "Lisää uusi");
$table->addButton($button);

$column = new UISortColumn("#", "paymentmethodID", 'paymentmethodID');
$table->addColumn($column);

$column = new UISortColumn("Nimike", "name", null, "200px");
$table->addColumn($column);

$column = new UISelectColumn("Ostovelkatili", "fullname", "accountID", $registry->accounts);
$table->addColumn($column);

$column = new UISelectColumn("Eräpäivä", "name", "duedateusage", $selection);
$table->addColumn($column);

$table->setData($registry->paymentmethods);
$table->show();




// ------------------------------------------------------------------------
//		ALV-kannat
// ------------------------------------------------------------------------


$insertvat= new UISection("ALV-kannan lisäys");
$insertvat->setDialog(true);
$insertvat->setMode(UIComponent::MODE_INSERT);
$insertvat->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/accountingsettings/insertvat');

$field = new UITextField("Nimike", "Nimike", 'name');
$insertvat->addField($field);

$field = new UITextField("Prosentti", "Prosentti", 'percent');
$insertvat->addField($field);

$insertvat->show();



$editreceiptset = new UISection("Tositesarjan muokkaus");
$editreceiptset->setDialog(true);
$editreceiptset->setMode(UIComponent::MODE_INSERT);
$editreceiptset->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/accountingsettings/updatevat');

$field = new UITextField("Nimike", "Nimike", 'name');
$editreceiptset->addField($field);

$field = new UITextField("Prosentti", "Prosentti", 'percent');
$editreceiptset->addField($field);

$editreceiptset->show();



$table = new UITableSection("Arvonlisäverokannat",$width);
$table->setOpen(true);
$table->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertvat->getID(), "Lisää uusi");
$table->addButton($button);

$column = new UISortColumn("Nimike", "name", 'name');
$table->addColumn($column);

$column = new UISortColumn("Prosentti", "percent", 'percent');
$table->addColumn($column);

$table->setData($registry->vats);
$table->show();
