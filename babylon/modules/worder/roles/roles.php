<?php




$insertsection = new UISection("Roolin lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);

$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/roles/insertrole');

$nimifield = new UITextField("Nimi", "name", 'name');
$insertsection->addField($nimifield);

$nimifield = new UITextField("Kuvaus", "description", 'description');
$insertsection->addField($nimifield);

$insertsection->show();




$table = new UITableSection("Roolit", "600px");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);


$wordclassIDcolumn = new UISortColumn("RoleID", "roleID", "worder/roles/showrole");
$wordclassIDcolumn->setColumnType(Column::COLUMNTYPE_INTEGER);
$table->addColumn($wordclassIDcolumn);

$nimicolumn = new UISortColumn("Nimi", "name", "worder/wordclasses/showwordclasses&sort=nimi");
$nimicolumn->setColumnType(Column::COLUMNTYPE_STRING);
$nimicolumn->setLink('worder/roles/showroles','roleID');
$table->addColumn($nimicolumn);

$desccolumn = new UISortColumn("Kuvaus", "description", "worder/wordclasses/showwordclasses&sort=nimi");
$desccolumn->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($desccolumn);

$table->setData($this->registry->roles);
$table->show();


?>