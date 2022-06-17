<?php



// [15.10.2021] Kopioitu projects/projects.php


$insertprojects = $registry->module->hasAccess(TasksModule::ACCESSRIGHTKEY_TASKMANAGEMENT);

if ($insertprojects) {
	$insertsection = new UISection("Projektin lisäys");
	$insertsection->setDialog(true);
	$insertsection->setMode(UIComponent::MODE_INSERT);
	$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/insertproject');
	
	$nimifield = new UITextField("Nimike", "Nimike", 'name');
	$insertsection->addField($nimifield);
	
	$insertsection->show();
} else {
	$insertsection = new UISection("Projektin lisäys");
	$insertsection->setDialog(true);
	$insertsection->setMode(UIComponent::MODE_INSERT);
	$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/projects/insertproject');
	
	$nimifield = new UITextField("Ei oikeuksia", "Nimike", 'name');
	$insertsection->addField($nimifield);
	
	$insertsection->show();
}




$table = new UITableSection("Projektit", "500px");
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"tasks/projects/showproject","projectID");

$column = new UISortColumn("#", "projectID", "tasks/projects/showproject", null, "10%");
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "tasks/projects/showproject&sort=nimi", null, "60%");
$table->addColumn($column);


$column = new UISortColumn("Prefix", "prefix", "tasks/projects/showproject&sort=nimi", null, "30%");
$table->addColumn($column);


//$column = new UISelectColumn("Workflow", "name", "workdflowID", $this->registry->workflows);
//$table->addColumn($column);

$table->setData($registry->projects);
$table->show();


?>