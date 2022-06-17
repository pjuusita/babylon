<?php


$insertsection = new UISection("Kirjailijan lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'books/authors/insertauthor');

$nimifield = new UITextField("Name", "name", 'name');
$insertsection->addField($nimifield);

$insertsection->show();




$table = new UITableSection("Authors", "500px");
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"books/authors/showauthor","authorID");

$column = new UISortColumn("Nimi", "name", "", null, "60%");
$table->addColumn($column);

$table->setData($registry->authors);
$table->show();



?>