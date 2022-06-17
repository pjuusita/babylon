<?php


$insertsection = new UISection("Generaattorin lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/generators/insertgenerator');

$nimifield = new UITextField("Name", "name", 'name');
$insertsection->addField($nimifield);

$insertsection->show();




$table = new UITableSection("Task Generators", "600px");
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää geraattori");
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"tasks/generators/showgenerator","generatorID");


$column = new UISortColumn("#", "generatorID", "", "80px");
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "", "420px");
$table->addColumn($column);

// luontipäivä

// tasksettien lukumäärä

// taskien lukumäärä

// tehtyjen taskien lukumäärä

// avointen taskien lukumäärä

// uusien luokittelemattomien taskien lukumäärä

$table->setData($this->registry->generators);
$table->show();




?>