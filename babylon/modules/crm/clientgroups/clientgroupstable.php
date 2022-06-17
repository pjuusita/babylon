<?php



$table = new UITableSection("Asiakasryhmät","600px");
$table->setOpen(true);
$table->setFramesVisible(false);
$table->setLineAction(UIComponent::ACTION_FORWARD,"crm/clients/showclient","clientpersonID");

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addpersondialog->getID(), 'Lisää henkilä');
//$table->addButton($button);

$column = new UISortColumn("Name", "name", 'Name');
$table->addColumn($column);

$table->setData($registry->asiakasryhmat);
$table->show();




?>