<?php





$insertsection = new UISection('Tuotteen lisäys','550px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'sales/products/insertproduct');

$field = new UITextField("Nimi", "name", 'name');
$insertsection->addField($field);

$field = new UITextField("Productnumber", "productnumber", 'productnumber');
$insertsection->addField($field);

$field = new UISelectField("Kirjanpitotili","accountID","accountID",$registry->accounts, "fullname");
$insertsection->addField($field);

$field = new UISelectField("Yksikkö","unitID","unitID",$registry->units, "name");
$insertsection->addField($field);

//$field = new UIDateField("Alkaa", "startdate", 'startdate');
//$insertsection->addField($field);

//$field = new UIDateField("Loppuu", "enddate", 'enddate');
//$insertsection->addField($field);

if (count($registry->productgroups) > 0) {
	$field = new UISelectField("Tuoteryhmä", "name", 'productgroupID', $registry->productgroups, 'name');
	$insertsection->addField($field);
}


$column = new UISelectField("ALV", "vatID", "vatID", $registry->vats, "name");
$insertsection->addField($column);


$insertsection->show();


// Tuoteryhmäfiltteri
// näytä poistuneet tuotteet täppä (jos poistettuja tuotteita on)

$table = new UITableSection("Tuotteet","600px");
$table->setOpen(true);
$table->setFramesVisible(false);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää tuote');
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"sales/products/showproduct","productID");

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addpersondialog->getID(), 'LisÃ¤Ã¤ henkilÃ¤');
//$table->addButton($button);

$column = new UISelectColumn("Tyyppi", null, "service", $registry->producttypes);
$table->addColumn($column);


$column = new UISortColumn("Nimike", "name", 'sales/products/showproducts&sort=nimi');
$table->addColumn($column);

if ($this->registry->productnumberused == 1) {
	$column = new UISortColumn("Tuotenumero", "productnumber", 'sales/products/showproducts&sort=nimi');
	$table->addColumn($column);
}


$column = new UISelectColumn("Tili", "fullname", "accountID", $registry->accounts);
$table->addColumn($column);

$column = new UISelectColumn("Yksikkö", "name", "unitID", $registry->units);
$table->addColumn($column);

$column = new UISelectColumn("ALV", "name", "vatID", $registry->vats);
$table->addColumn($column);


$table->setData($registry->products);
$table->show();


?>