<?php



$insertsection = new UISection("Maksun saajan lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'accounting/suppliers/insertsupplier');

$nimifield = new UITextField("Nimi", "Nimi", 'name');
$insertsection->addField($nimifield);

$nimifield = new UITextField("IBAN", "IBAN", 'iban');
$insertsection->addField($nimifield);

$nimifield = new UITextField("Viitenumero", "reference", 'referencenumber');
$insertsection->addField($nimifield);

$field = new UISelectField("Maa","countryID","countryID", $registry->countries, "name");
$insertsection->addField($field);

$empty = new Row();
$empty->countryID = 1;
$insertsection->setData($empty);
$insertsection->show();




$table = new UITableSection("Tavarantoimittajat ja palveluntuottajat", "500px");
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"accounting/suppliers/showsupplier","supplierID");

$column = new UISortColumn("#", "supplierID", "", null, "50px");
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "", null, "90%");
$table->addColumn($column);

$table->setData($registry->suppliers);
$table->show();



?>