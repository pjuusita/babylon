<?php


include ("header.php");



$section = new UITableSection("Tiimit", "600px");
$section->setLineAction(UIComponent::ACTION_FORWARD, 'admin/usermanagement/showteams','teamID');

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
//$section->addButton($button);

$column = new UISortColumn("", "name");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$section->addColumn($column);

$section->setData($this->registry->teams);

$section->show();

