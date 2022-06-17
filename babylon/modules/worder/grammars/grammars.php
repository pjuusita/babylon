<?php




$insertsection = new UISection("Add grammar");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);

$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/grammars/insertgrammar');

$nimifield = new UITextField("Grammar name", "name", 'name');
$insertsection->addField($nimifield);

$nimifield = new UITextField("Language", "language", 'language');
$insertsection->addField($nimifield);

$insertsection->show();





$table = new UITableSection("Grammars", "600px");

$table->setLineAction(UIComponent::ACTION_FORWARD, 'worder/grammars/showgrammar','grammarID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);

$field = new UISortColumn("GrammarID", "grammarID");
$field->setColumnType(Column::COLUMNTYPE_INTEGER);
$table->addColumn($field);

$field = new UISortColumn("Nimi", "name");
$field->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($field);

$table->setData($this->registry->grammars);
$table->show();


?>