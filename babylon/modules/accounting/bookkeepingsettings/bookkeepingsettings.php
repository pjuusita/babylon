<?php



echo "<h1>Taloushallintoasetukset</h1>";

// Tämä pitäisi muuttaa kokonaan taulukko muotoon, sitten riviä klikkaamalla pääsisi muuttamaan taulukon rivin sisältöä...
// Sellainen olisi paljon kätevämpi ratkaisu... lisäys helpompaa. 
//
// TODO: Lisää system_settings-tauluun moduleID
// TODO: Muuta taulukko muotoon
$section = new UISection("Kirjanpitotilien asetukset");
$section->setOpen(true);
$section->editable(true);
$section->setWidths("50%","50%","0%");
$section->setUpdateAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/updatesettings', 'systemID');

$column = new UISelectField("ALV saamistili", "vatrecievablesaccountID", "vatrecievablesaccountID", $registry->accounts, 'fullname');
$column->setPredictive(true);
$section->addField($column);

$column = new UISelectField("ALV velkatili", "vatpayablesaccountID", "vatpayablesaccountID", $registry->accounts, 'fullname');
$column->setPredictive(true);
$section->addField($column);

$column = new UISelectField("Myyntisaamiset", "recievablesaccountID", "recievablesaccountID", $registry->accounts, 'fullname');
$column->setPredictive(true);
$section->addField($column);

$column = new UISelectField("Käteiskassatili", "cashaccountID", "cashaccountID", $registry->accounts, 'fullname');
$column->setPredictive(true);
$section->addField($column);

$column = new UISelectField("Henkilöstövelkatili", "hrdebtsaccountID", "hrdebtsaccountID", $registry->accounts, 'fullname');
$column->setPredictive(true);
$section->addField($column);

$column = new UISelectField("Ostovelat", "payablesaccountID", "payablesaccountID", $registry->accounts, 'fullname');
$column->setPredictive(true);
$section->addField($column);

$column = new UISelectField("Myyntilaskujen tositesarja", "receiptsetID", "receiptsetID", $registry->receiptsets, 'name');
$column->setPredictive(true);
$section->addField($column);

$column = new UISelectField("Tiliotteen tositesarja", "bankstatementsetID", "bankstatementsetID", $registry->receiptsets, 'name');
$column->setPredictive(true);
$section->addField($column);

$section->setData($registry->settings);
$section->show();





//---------------------------------------------------------------------------------------------
// 	Dimensiot
//
//	22.5.2021 - Dimensiot korvattu hardkoodatuilla toiminnoilla
//---------------------------------------------------------------------------------------------

/*
$insertdimensionsection = new UISection("Dimension lisäys");
$insertdimensionsection->setDialog(true);
$insertdimensionsection->setMode(UIComponent::MODE_INSERT);
$insertdimensionsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/insertdimension');

$field = new UITextField("Nimi", "Nimi", 'name');
$insertdimensionsection->addField($field);

$insertdimensionsection->show();


$insertdimensionvaluesection = new UISection("Dimensioarvon lisäys");
$insertdimensionvaluesection->setDialog(true);
$insertdimensionvaluesection->setMode(UIComponent::MODE_INSERT);
$insertdimensionvaluesection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/insertdimensionvalue');

$field = new UITextField("Nimi", "Nimi", 'name');
$insertdimensionvaluesection->addField($field);

$insertdimensionvaluesection->show();



$editdimensionsection = new UISection("Dimension muokkaus");
$editdimensionsection->setDialog(true);
$editdimensionsection->setMode(UIComponent::MODE_INSERT);
$editdimensionsection->setUpdateAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/updatedimension','dimensionID');

$field = new UITextField("Nimi", "Nimi", 'name');
$editdimensionsection->addField($field);

$field = new UITextField("Lyhenne", "Abbreviation", 'abbreviation');
$editdimensionsection->addField($field);

$field = new UIBooleanField("Käytössä myyntiosiossa","usedinsales","usedinsales");
$editdimensionsection->addField($field);

$field = new UIBooleanField("käytössä ostotosiossa","usedinpurchases","usedinpurchases");
$editdimensionsection->addField($field);

$field = new UIBooleanField("Käytössä palkanlaskennassa","usedinpayroll","usedinpayroll");
$editdimensionsection->addField($field);

$editdimensionsection->show();


$editdimensionvaluesection = new UISection("Dimensioarvon muokkaus");
$editdimensionvaluesection->setDialog(true);
$editdimensionvaluesection->setMode(UIComponent::MODE_INSERT);
$editdimensionvaluesection->setUpdateAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/updatedimension', 'dimensionvalueID');

$field = new UITextField("Nimi", "Nimi", 'name');
$editdimensionvaluesection->addField($field);

$field = new UITextField("Lyhenne", "Abbreviation", 'abbreviation');
$editdimensionvaluesection->addField($field);


$editdimensionvaluesection->show();



$section = new UITierTableSection("Dimensiot", "600px");
$section->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertdimensionsection->getID(), "Lisää dimensio");
$section->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertdimensionvaluesection->getID(), "Lisää dimensionarvo");
$section->addButton($button);

$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdimensionsection->getID(), 'dimensionID');

$column = new UIColumn("Nimi", "name");
$section->addColumn($column);

$column = new UIColumn("Lyhenne", "abbreviation");
$section->addColumn($column);

$column = new UIBooleanColumn("Myynti", "usedinsales");
$column->setAlign(Column::ALIGN_CENTER);
$section->addColumn($column);

$column = new UIBooleanColumn("Ostot", "usedinpurchases");
$column->setAlign(Column::ALIGN_CENTER);
$section->addColumn($column);

$column = new UIBooleanColumn("Palkat", "usedinpayroll");
$column->setAlign(Column::ALIGN_CENTER);
$section->addColumn($column);

$column = new UIHiddenColumn("dimensionID", "dimensionID");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "dimensionID", "accounting/bookkeepingsettings/removedimension" ,"5%");		// Toinen parametri workflowID tarvitaan taulussa mukana, hiddenininä jos ei muuten
$column->setIcon("fa fa-ban");
$section->addColumn($column);

$section->setData($registry->dimensions);

$subcolumns = array();
$subcolumns[] = new UIColumn("Nimi", "name");
$subcolumns[] = new UIColumn("Lyhenne", "abbreviation");
$subcolumns[] = new UIHiddenColumn("DimensionvalueID", "dimensionvalueID");

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "stateID", "accounting/bookkeepingsettings/removedimensionvalue");
$column->setIcon("fa fa-ban");
$subcolumns[] = $column;

$section->setLevelData($registry->dimensionvalues, $subcolumns, "dimensionID", "dimensionID");
$section->setSubLevelLineAction(UIComponent::ACTION_OPENDIALOG, $editdimensionvaluesection->getID(), 'dimensionvalueID');

$section->show();
*/



//---------------------------------------------------------------------------------------------
// 			Tilikausi
//---------------------------------------------------------------------------------------------

$insertperiodsection = new UISection("Tilikauden lisäys");
$insertperiodsection->setDialog(true);
$insertperiodsection->setMode(UIComponent::MODE_INSERT);
$insertperiodsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/insertperiod');

$field = new UITextField("Nimike", "Nimike", 'name');
$insertperiodsection->addField($field);

$field = new UIDateField("Alkaa", "startdate", 'startdate');
$insertperiodsection->addField($field);

$field = new UIDateField("Loppuu", "enddate", 'enddate');
$insertperiodsection->addField($field);

$insertperiodsection->show();


$editperiodsection = new UISection("Tilikauden muokkaus");
$editperiodsection->setDialog(true);
$editperiodsection->setMode(UIComponent::MODE_INSERT);
$editperiodsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/updateperiod');

$field = new UITextField("Nimike", "Nimike", 'name');
$editperiodsection->addField($field);

$field = new UIDateField("Alkaa", "startdate", 'startdate');
$editperiodsection->addField($field);

$field = new UIDateField("Loppuu", "enddate", 'enddate');
$editperiodsection->addField($field);

$editperiodsection->show();



$table = new UITableSection("Tilikaudet", '600px');
$table->setOpen(true);
$table->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertperiodsection->getID(), "Lisää uusi");
$table->addButton($button);

$nimicolumn = new UISortColumn("Nimi", "name", "");
$table->addColumn($nimicolumn);

$startcolumn = new UISortColumn("Alkaa", "startdate", "");
$startcolumn->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($startcolumn);

$endcolumn = new UISortColumn("Loppuu", "enddate", "");
$endcolumn->setFormatter(Column::COLUMNTYPE_DATE);
$table->addColumn($endcolumn);

$table->setData($registry->periods);
$table->show();




// ------------------------------------------------------------------------
//		Tositesarjat
// ------------------------------------------------------------------------


$insertreceiptset = new UISection("Tositesarjan lisäys");
$insertreceiptset->setDialog(true);
$insertreceiptset->setMode(UIComponent::MODE_INSERT);
$insertreceiptset->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/insertreceiptset');

$field = new UITextField("Nimike", "Nimike", 'name');
$insertreceiptset->addField($field);

$field = new UITextField("Lyhenne", "Lyhenne", 'code');
$insertreceiptset->addField($field);

$field = new UITextField("Alkunumero", "Alkunumero", 'startnumber');
$insertreceiptset->addField($field);

$field = new UITextField("Loppunumero", "Loppunumero", 'endnumber');
$insertreceiptset->addField($field);

$insertreceiptset->show();



$editreceiptset = new UISection("Tositesarjan muokkaus");
$editreceiptset->setDialog(true);
$editreceiptset->setMode(UIComponent::MODE_INSERT);
$editreceiptset->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/updatereceiptset');

$field = new UITextField("Nimike", "Nimike", 'name');
$editreceiptset->addField($field);

$field = new UITextField("Lyhenne", "Lyhenne", 'code');
$editreceiptset->addField($field);

$field = new UITextField("Alkunumero", "Alkunumero", 'startnumber');
$editreceiptset->addField($field);

$field = new UITextField("Loppunumero", "Loppunumero", 'endnumber');
$editreceiptset->addField($field);

$editreceiptset->show();



$table = new UITableSection("Tositesarjat","600px");
$table->setOpen(true);
$table->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertreceiptset->getID(), "Lisää uusi");
$table->addButton($button);

$column = new UISortColumn("Koodi", "code", "");
$table->addColumn($column);

$nimicolumn = new UISortColumn("Nimi", "name", "");
$table->addColumn($nimicolumn);

$startcolumn = new UISortColumn("Alkaa", "startnumber", "");
$table->addColumn($startcolumn);

$endcolumn = new UISortColumn("Loppuu", "endnumber", "");
$table->addColumn($endcolumn);

$table->setData($this->registry->receiptsets);
$table->show();






// ------------------------------------------------------------------------
//		ALV-kannat
// ------------------------------------------------------------------------


$insertvat= new UISection("ALV-kannan lisäys");
$insertvat->setDialog(true);
$insertvat->setMode(UIComponent::MODE_INSERT);
$insertvat->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/insertvat');

$field = new UITextField("Nimike", "Nimike", 'name');
$insertvat->addField($field);

$field = new UITextField("Prosentti", "Prosentti", 'percent');
$insertvat->addField($field);

$insertvat->show();



$editreceiptset = new UISection("Tositesarjan muokkaus");
$editreceiptset->setDialog(true);
$editreceiptset->setMode(UIComponent::MODE_INSERT);
$editreceiptset->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/updatereceiptset');

$field = new UITextField("Nimike", "Nimike", 'name');
$editreceiptset->addField($field);

$field = new UITextField("Prosentti", "Prosentti", 'percent');
$editreceiptset->addField($field);

$editreceiptset->show();



$table = new UITableSection("Arvonlisäverokannat","600px");
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




//---------------------------------------------------------------------------------------------
// 			Costpooltypes
//---------------------------------------------------------------------------------------------

/*
$insertdimensionsection = new UISection("Kustannuspaikkatyypin lisäys");
$insertdimensionsection->setDialog(true);
$insertdimensionsection->setMode(UIComponent::MODE_INSERT);
$insertdimensionsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/insertcostpooltype');

$field = new UITextField("Nimi", "Nimi", 'name');
$insertdimensionsection->addField($field);

$insertdimensionsection->show();
*/

/*
$insertdimensionvaluesection = new UISection("Kustannuspaikkatyypin arvon lisäys");
$insertdimensionvaluesection->setDialog(true);
$insertdimensionvaluesection->setMode(UIComponent::MODE_INSERT);
$insertdimensionvaluesection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/insertcostpooltypeaccount');


$column = new UISelectField("Parent", "costpooltypeID", "costpooltypeID", $registry->costpooltypes, 'name');
$insertdimensionvaluesection->addField($column);

$field = new UITextField("Nimi", "Nimi", 'name');
$insertdimensionvaluesection->addField($field);

$column = new UISelectField("Kirjanpitotili", "accountID", "accountID", $registry->accounts, 'fullname');
$column->setPredictive(true);
$insertdimensionvaluesection->addField($column);

$insertdimensionvaluesection->show();
*/

/*
$editdimensionsection = new UISection("Kustannuspaikkatyypin muokkaus");
$editdimensionsection->setDialog(true);
$editdimensionsection->setMode(UIComponent::MODE_INSERT);
$editdimensionsection->setUpdateAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/updatecostpooltype','costpooltypeID');

$field = new UITextField("Nimi", "Nimi", 'name');
$editdimensionsection->addField($field);

$editdimensionsection->show();
*/

/*
$editdimensionvaluesection = new UISection("Kustannuspaikkatyypin arvon muokkaus");
$editdimensionvaluesection->setDialog(true);
$editdimensionvaluesection->setMode(UIComponent::MODE_INSERT);
$editdimensionvaluesection->setUpdateAction(UIComponent::ACTION_FORWARD, 'accounting/bookkeepingsettings/updatecostpooltypeaccount', 'rowID');

$field = new UITextField("Nimi", "Nimi", 'name');
$editdimensionvaluesection->addField($field);

$column = new UISelectField("Kirjanpitotili", "accountID", "accountID", $registry->accounts, 'fullname');
$column->setPredictive(true);
$editdimensionvaluesection->addField($column);

$editdimensionvaluesection->show();
*/



/*
$section = new UITierTableSection("Kustannuspaikkatyypit", "600px");
$section->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertdimensionsection->getID(), "Lisää tyyppi");
$section->addButton($button);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertdimensionvaluesection->getID(), "Lisää tili");
$section->addButton($button);

$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdimensionsection->getID(), 'costpooltypeID');

$column = new UIColumn("Nimi", "name");
$section->addColumn($column);

$column = new UIHiddenColumn("costpooltypeID", "costpooltypeID");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "costpooltypeID", "accounting/bookkeepingsettings/removecostpooltype" ,"5%");		// Toinen parametri workflowID tarvitaan taulussa mukana, hiddenininä jos ei muuten
$column->setIcon("fa fa-ban");
$section->addColumn($column);

$section->setData($registry->costpooltypes);

$subcolumns = array();
$subcolumns[] = new UIColumn("Nimi", "name");
$subcolumns[] = new UISelectColumn("Tili", "fullname", "accountID", $registry->accounts);
$subcolumns[] = new UIHiddenColumn("rowID", "rowID");
$subcolumns[] = new UIHiddenColumn("accountID", "accountID");


$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "stateID", "accounting/bookkeepingsettings/removecostpooltypeaccount");
$column->setIcon("fa fa-ban");
$subcolumns[] = $column;

$section->setLevelData($registry->costpooltypeaccounts, $subcolumns, "costpooltypeID", "costpooltypeID");
$section->setSubLevelLineAction(UIComponent::ACTION_OPENDIALOG, $editdimensionvaluesection->getID(), 'rowID');




$section->show();
*/

