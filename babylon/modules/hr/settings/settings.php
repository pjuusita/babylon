<?php




$table = new UITableSection("Työtehtävänimikkeet","600px");
$table->setOpen(true);
$table->setFramesVisible(true);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää uusi nimike');
//$table->addButton($button);


$column = new UISortColumn("Nimike", "name", 'name');
$table->addColumn($column);

$table->setData($registry->worktitles);
$table->show();



$table = new UITableSection("Työntekijäryhmät","600px");
$table->setOpen(true);
$table->setFramesVisible(true);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää uusi nimike');
//$table->addButton($button);


$column = new UISortColumn("Nimike", "name", 'name');
$table->addColumn($column);

$table->setData($registry->workergroups);
$table->show();



$table = new UITableSection("Työntekijätilat","600px");
$table->setOpen(true);
$table->setFramesVisible(true);
//$table->setFramesVisible(false);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää uusi nimike');
//$table->addButton($button);


$column = new UISortColumn("Nimike", "name", 'name');
$table->addColumn($column);

$table->setData($registry->workerstates);
$table->show();
