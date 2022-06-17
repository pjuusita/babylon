<?php





$insertsection = new UISection('Tittelin lisäys','500px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'crm/jobtitles/insertjobtitle');

$field = new UITextField("Nimike", "name", 'name');
$insertsection->addField($field);

$insertsection->show();





$table = new UITableSection("Tittelit","500px");
$table->setOpen(true);
$table->setFramesVisible(false);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää uusi nimike');
$table->addButton($button);


$column = new UISortColumn("Nimike", "name", 'name');
$table->addColumn($column);

$table->setData($registry->jobtitles);
$table->show();





?>