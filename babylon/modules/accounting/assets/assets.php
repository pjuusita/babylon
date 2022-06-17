<?php



// TODO: tähän käteisvarat ja pankkitilien saldot
//			- toinen vaihtoehtoinen tapa olisi luoda päämenu: varallisuus
//			  ja sitten täällä olisi myös saamiset, velat ja oma pääoma

$section = new UITreeSection("Rahat", "600px");

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Lisää tuotantotekijä');
//$section->addButton($button);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'accounting/assets/showasset', 'assetID', UIComponent::ACTION_FORWARD);

$column = new UIColumn("Nimi", "name");
$section->addColumn($column);

$section->setData(array());
$section->show();



$editsection = new UISection("Tuotantotekijän muokkaus");
$editsection->setDialog(true);
$editsection->setMode(UIComponent::MODE_INSERT);
$editsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/assets/updateasset', "assetID");

$nimifield = new UITextField("Nimi", "Nimi", 'name');
$editsection->addField($nimifield);

$field = new UISelectField("Tasetili","assetaccountID","assetaccountID",$registry->accounts, "fullname");
$field->setPredictive(true);
$editsection->addField($field);

$editsection->show();



$insertsection = new UISection("Tuotantotekijän lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/assets/insertasset');
	
$nimifield = new UITextField("Nimi", "Nimi", 'name');
$insertsection->addField($nimifield);

$field = new UISelectField("Parent","parentID","parentID",$registry->assets, "name");
$field->setPredictive(true);
$insertsection->addField($field);

//$field = new UISelectField("Tasetili","assetaccountID","assetaccountID",$registry->accounts, "fullname");
//$field->setPredictive(true);
//$editsection->addField($field);

$insertsection->show();





echo "<br><br>";
$section = new UITreeSection("Tuotantotekijät", "600px");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Lisää tuotantotekijä');
$section->addButton($button);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'accounting/assets/showasset', 'assetID', UIComponent::ACTION_FORWARD);


//$column = new UIColumn("#", "accountID");
//$section->addColumn($column);

$column = new UIColumn("Nimi", "name");
$section->addColumn($column);

/*
$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "accountID", "accounting/accountchart/moveaccount");
$column->setIcon("fa fa-chevron-down");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "accountID", "accounting/accountchart/moveaccount");
$column->setIcon("fa fa-chevron-up");
$section->addColumn($column);
*/

$section->setData($registry->assethierarchy);
$section->show();



/*
$table = new UITableSection("Omaisuus", "600px");
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editsection->getID(),"assetID");

//$table->setLineAction(UIComponent::ACTION_FORWARD,"","projectID");

$column = new UISortColumn("#", "assetID", "", null, "50px");
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "", null, "90%");
$table->addColumn($column);

//$column = new UISelectColumn("Tulotili", "fullname", "incomeaccountID", $registry->accounts);
//$table->addColumn($column);

//$column = new UISelectColumn("Kulutili", "fullname", "expenseaccountID", $registry->accounts);
//$table->addColumn($column);

//$column = new UISelectColumn("Tasetili", "fullname", "deptaccountID", $registry->accounts);
//$table->addColumn($column);

$table->setData($registry->assets);
$table->show();
*/

?>