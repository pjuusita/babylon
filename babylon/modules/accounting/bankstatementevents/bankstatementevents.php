<?php



$insertsection = new UISection("Tiliotetapahtuman lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bankstatementevents/insertbankstatementevent');
	
$nimifield = new UITextField("Nimi", "Nimi", 'name');
$insertsection->addField($nimifield);

//$nimifield = new UITextField("Tositeteksti", "Receipttext", 'receipttext');
//$insertsection->addField($nimifield);

$field = new UISelectField("Tyyppi","eventtypeID","eventtypeID",$registry->eventtypes, "name");
$insertsection->addField($field);

$field = new UISelectField("Vastatili","accountID","accountID",$registry->accounts, "fullname");
$field->setPredictive(true);
$insertsection->addField($field);

$insertsection->show();




$editsection = new UISection("Tiliotetapahtuman muokkaus");
$editsection->setDialog(true);
$editsection->setMode(UIComponent::MODE_INSERT);
$editsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/bankstatementevents/updatebankstatementevent', "eventID");

$nimifield = new UITextField("Nimi", "Nimi", 'name');
$editsection->addField($nimifield);

$field = new UISelectField("Tyyppi","eventtypeID","eventtypeID",$registry->eventtypes, "name");
$editsection->addField($field);

//$nimifield = new UITextField("Tositeteksti", "Receipttext", 'receipttext');
//$editsection->addField($nimifield);

$field = new UISelectField("Vastatili","accountID","accountID",$registry->accounts, "fullname");
$field->setPredictive(true);
$editsection->addField($field);

$editsection->show();




$table = new UITableSection("Tiliotetapahtumat", "600px");
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editsection->getID(),"eventID");

$column = new UISortColumn("#", "eventID", "", null, "50px");
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "", null, "50%");
$table->addColumn($column);

$column = new UISortColumn("Linkitys", "explanation", "", null, "50%");
$table->addColumn($column);

$column = new UIHiddenColumn("eventtypeID", "eventtypeID");
$table->addColumn($column);

$column = new UIHiddenColumn("accountID", "accountID");
$table->addColumn($column);


$column = new UIHiddenColumn("eventID", "eventID");
$table->addColumn($column);


/*
$column = new UISelectColumn("Vastaili", "fullname", "accountID", $registry->accounts);
$table->addColumn($column);
*/

$table->setData($registry->bankstatementevents);
$table->show();


?>