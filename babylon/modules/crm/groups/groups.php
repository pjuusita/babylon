<?php




$insertsection = new UISection('Asiakasryhmän lisäys','500px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'crm/groups/insertgroup');

$field = new UITextField("Ryhmän nimi", "name", 'name');
$insertsection->addField($field);

$insertsection->show();





$table = new UITableSection("Asiakasryhmät","500px");
$table->setOpen(true);
$table->setFramesVisible(false);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää uusi asiakasryhmä');
$table->addButton($button);


//$table->setLineAction(UIComponent::ACTION_FORWARD,"crm/groups/showgroups","clientpersonID");

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addpersondialog->getID(), 'Lisää henkilä');
//$table->addButton($button);

$column = new UISortColumn("Nimi", "name", 'name');
$table->addColumn($column);

$table->setData($registry->groups);
$table->show();




?>