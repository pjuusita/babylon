<?php




$insertsection = new UISection("Elementin lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'wiki/elements/insertelement');


$insertsection->show();



$section = new UITreeSection("Workflows", "600px");


$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää uusi");
$section->addButton($button);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'wiki/elements/showelement', 'elementID', UIComponent::ACTION_FORWARD);


$column = new UIColumn("Identifier", "identifier");
$section->addColumn($column);

$column = new UIColumn("Nimi", "name");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "elementID", "wiki/elements/setsortdown");
$column->setIcon("fa fa-chevron-down");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "elementID", "wiki/elements/setsortup");
$column->setIcon("fa fa-chevron-up");
$section->addColumn($column);

$section->setData($registry->elements);
$section->show();




?>