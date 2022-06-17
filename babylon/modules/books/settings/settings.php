<?php



$labelinsertsection = new UISection("Add Label");
$labelinsertsection->setDialog(true);
$labelinsertsection->setMode(UIComponent::MODE_INSERT);
$labelinsertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'books/settings/insertlabel');

$field = new UITextField("Nimi", "name", 'name');
$labelinsertsection->addField($field);

$labelinsertsection->show();


$editlabelsection = new UISection("Edit Label");

$editlabelsection->setDialog(true);
$editlabelsection->setMode(UIComponent::MODE_EDIT);
$editlabelsection->setSaveAction(UIComponent::ACTION_FORWARD, 'books/settings/updatelabel', 'labelID');

$field = new UITextField("Name", "name", 'name');
$editlabelsection->addField($field);

$editlabelsection->show();





$section = new UITableSection("Labels", "600px");		// labels
$section->setOpen(true);
$section->setFramesVisible(true);

$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editlabelsection->getID(),"rowID");
$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'books/settings/removelabel', 'labelID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $labelinsertsection->getID(), "Lisää");
$section->addButton($button);

$column = new UISortColumn("ID", "labelID", "", "20px");
$section->addColumn($column);

$column = new UISortColumn("Nimike", "name", "", "480px");
$section->addColumn($column);

$section->setData($registry->labels);
$section->show();





?>