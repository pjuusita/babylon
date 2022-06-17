<?php






$insertobjectsection = new UISection("Varauskohteen lisäys");
$insertobjectsection->setDialog(true);
$insertobjectsection->setMode(UIComponent::MODE_INSERT);
$insertobjectsection->setSaveAction(UIComponent::ACTION_FORWARD, 'reservation/reservationsettings/insertobject');

$field = new UITextField("Nimi", "Nimi", 'name');
$insertobjectsection->addField($field);

$insertobjectsection->show();



$editobjectsection = new UISection("Varauskohteen muokkaus");
$editobjectsection->setDialog(true);
$editobjectsection->setMode(UIComponent::MODE_INSERT);
$editobjectsection->setUpdateAction(UIComponent::ACTION_FORWARD, 'reservation/reservationsettings/updateobject', 'objectID');


$field = new UITextField("Nimike", "Nimike", 'name');
$editobjectsection->addField($field);

$editobjectsection->show();




$table = new UITableSection("Varauskohteet", '600px');
$table->setOpen(true);
$table->setFramesVisible(true);
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertobjectsection->getID(), "Lisää uusi");

$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editobjectsection->getID(),"objectID");
$table->setDeleteAction(UIComponent::ACTION_FORWARD, 'sales/salessettings/removeproductgroup', 'objectID');

$table->addButton($button);

$nimicolumn = new UISortColumn("Nimi", "name", "");
$table->addColumn($nimicolumn);

$table->setData($registry->objects);
$table->show();


?>