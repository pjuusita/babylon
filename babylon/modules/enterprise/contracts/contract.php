<?php


	

echo "<h1>" . $registry->contract->name . "</h1>";


$section = new UISection("Palvelusopimus","800px");
$section->setOpen(true);
$section->editable(true);

$section->setUpdateAction(UIComponent::ACTION_FORWARD,'entreprise/contracts/updatecontract', 'contractID');

$field = new UITextField("Name","name","name");
$section->addField($field);

$section->setData($registry->contract);
$section->show();


$existing = array();
$available = array();
foreach($registry->modules as $index => $module) {
	if ($module->active == 1) {
		$existing[$index] = $module;
	} else {
		$available[$index] = $module;
	}
}


$dialog = new UISection('Modulin lisäys','500px');
$dialog->setDialog(true);
$dialog->setMode(UIComponent::MODE_INSERT);
$dialog->setSaveAction(UIComponent::ACTION_FORWARD, 'enterprise/contracts/insertmodule&contractID=' . $registry->contract->rowID);

$posfield	= new UISelectField("Moduli","moduleID","moduleID",$available, "name");
$dialog->addField($posfield);

$dialog->show();



$table = new UITableSection("Modulit","800px");
$table->setOpen(true);
$table->editable(true);
$table->setFramesVisible(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $dialog->getID(), 'Lisää moduli');
$table->addButton($button);

$column = new UISortColumn("ModuleID", "moduleID");
$table->addColumn($column);

$column = new UISortColumn("Name", "name");
$table->addColumn($column);

$table->setData($existing);
$table->show();


?>