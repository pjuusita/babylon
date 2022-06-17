<?php



// ---------------------------------------------------------------------------------------------------
// Lisää dialogi
// ---------------------------------------------------------------------------------------------------

$insertsection = new UISection("Adding wordclass");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/wordclasses/insertwordclass');

$nimifield = new UITextField("Name", "name", 'name');
$insertsection->addField($nimifield);

$field = new UISelectField("Parent","parentID","parentID", $registry->wordclasses, "name");
$insertsection->addField($field);

$insertsection->show();




$table = new UITreeSection("Sanaluokat", "600px");
//$table->showLineNumbers(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Lisää uusi');
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD, "worder/wordclasses/showwordclass", 'wordclassID');

$nimicolumn = new UISortColumn("Nimi", "name");
$table->addColumn($nimicolumn);

$table->setData($this->registry->hierarchy);
$table->show();


?>