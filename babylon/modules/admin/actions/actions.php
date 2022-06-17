<?php


$insertsection = new UISection("Uuden actionin lisäys");
$insertsection->setDialog(true);

$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/actions/addaction');

$field = new UITextField("Actionpath", "actionpath", 'actionpath');
$insertsection->addField($field);

$insertsection->show();




$editdialog = new UISection("Actionin muokkaus");
$editdialog->setDialog(true);
$editdialog->setMode(UIComponent::MODE_EDIT);
$editdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'admin/actions/updateaction', 'actionID');

$field = new UITextField("Actionpath", "actionpath", 'actionpath');
$editdialog->addField($field);

$editdialog->show();




$table = new UITableSection("Actionit", "600px");
$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editdialog->getID(), 'actionID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää action");
$table->addButton($button);

$column = new UISortColumn("ActionID", "actionID");
$table->addColumn($column);

$column = new UISortColumn("Actionpath", "actionpath");
$table->addColumn($column);

$table->setData($this->registry->actions);
$table->show();



?>