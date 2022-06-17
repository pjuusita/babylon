<?php





$insertsection = new UISection('Työkohdetyypin lisäys','500px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'crm/locationtypes/insertlocationtype');

$field = new UITextField("Nimike", "name", 'name');
$insertsection->addField($field);

$insertsection->show();





$table = new UITableSection("Työkohdetyypit","500px");
$table->setOpen(true);
$table->setFramesVisible(false);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää uusi tyyppi');
$table->addButton($button);


$column = new UISortColumn("Tyyppi", "name", 'name');
$table->addColumn($column);

$table->setData($registry->locationtypes);
$table->show();





?>