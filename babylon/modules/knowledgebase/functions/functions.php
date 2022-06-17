<?php



$insertsection = new UISection("Add Function");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'knowledgebase/functions/insertfunction');

$nimifield = new UITextField("Name", "name", 'name');
$insertsection->addField($nimifield);

$insertsection->show();




$table = new UITableSection("Functions", "600px");
$table->showLineNumbers(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"knowledgebase/functions/showfunction","functionID");

$column = new UISortColumn("ID", "functionID", "functionID");
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "name");
$table->addColumn($column);

$table->setData($registry->functions);
$table->show();



?>