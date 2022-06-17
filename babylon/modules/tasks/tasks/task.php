<?php


echo "<h1>" . $registry->task->name. "</h1>";
$width = "700px;";

$section = new UISection("Perustiedot",$width);
$section->setOpen(true);
$section->editable(true);


$section->setUpdateAction(UIComponent::ACTION_FORWARD,'tasks/tasks/updatetask', 'taskID');

$wordIDfield = new UIFixedTextField("TaskID", $registry->task->taskID);
$section->addField($wordIDfield);

$field = new UISelectField("Project","projectID","projectID",$registry->projects, 'name');
$section->addField($field);

$field = new UITextField("Nimi", "name", 'name');
//$field->setMultiline(1);
$section->addField($field);

$field = new UISelectField("Tila","stateID","stateID",$registry->states, 'name');
$section->addField($field);

$field = new UISelectField("Prioriteetti","priorityID","priorityID",$registry->priorities, 'name');
$section->addField($field);

if ($registry->task->generatorID > 0) {
	
	$field = new UIFixedTextField("Generator", $registry->generator->name);
	$section->addField($field);
}

//$field = new UISelectField("Tehtävätyyppi","tasktypeID","tasktypeID",$registry->tasktypes, 'name');
//$field->setMultiline(1);
//$section->addField($field);

$field = new UILineField();
$section->addField($field);

$field = new UITextAreaField("Kuvaus","description","description");
$section->addField($field);

$section->setData($registry->task);
$section->show();




$minitaskinsertsection = new UISection("Minitaskin lisäys");
$minitaskinsertsection->setDialog(true);
$minitaskinsertsection->setMode(UIComponent::MODE_INSERT);
$minitaskinsertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/tasks/insertminitask&taskID=' . $registry->task->taskID);

$field = new UITextField("Nimi", "name", 'name');
$minitaskinsertsection->addField($field);

$minitaskinsertsection->show();



/*
 * 

// TODO: tehokkuussyistä joudutaan ehkä jakamaan completed ja uncompleted omiin tauluihin?

$completed = array();
$uncompleted = array();

foreach($registry->minitasks as $index => $minitask) {
	//echo "<br>Minitask - " . $minitask->minitaskID;
	if ($minitask->state != 0) {
		$completed[$minitask->minitaskID] = $minitask;
	} else {
		$uncompleted[$minitask->minitaskID] = $minitask;
	}
}

echo "<br>completed - " . count($completed);
echo "<br>uncompleted - " . count($uncompleted);
*/



$editminitasksection = new UISection("Minitaskin muokkaus");

$editminitasksection->setDialog(true);
$editminitasksection->setMode(UIComponent::MODE_EDIT);
$editminitasksection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/tasks/updateminitask&taskID=' . $registry->task->taskID, 'rowID');

$field = new UITextField("Name", "name", 'name');
$editminitasksection->addField($field);

$editminitasksection->show();





$section = new UITableSection("Minitaskit",$width);		// labels
$section->setOpen(true);
$section->setFramesVisible(true);

//echo "<br>Actionpath - " . $actionpath;
if ($registry->task->actionID > 0) {
	$actionpath = $registry->action->actionpath;
	$section->setLineAction(UIComponent::ACTION_FORWARD, $actionpath, "targetID");
} else {
	$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editminitasksection->getID(),"rowID");
}

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'tasks/tasks/removeminitask&taskID=' . $registry->task->taskID, 'minitaskID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $minitaskinsertsection->getID(), "Lisää");
$section->addButton($button);

$column = new UISortColumn("ID", "minitaskID", "tasks/projects/showtask", "40px");
$section->addColumn($column);

$column = new UISortColumn("Nimike", "name", "tasks/projects/showtask", "480px");
$section->addColumn($column);

$column = new UISortColumn("State", "state", "tasks/projects/showtask");
$section->addColumn($column);

//$column = new UISortColumn("TargetID", "targetID", "tasks/projects/showtask", "80px");
//$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "minitaskID", "tasks/tasks/completeminitask&taskID=" . $registry->task->taskID);		
$column->setTitle("Done");
$section->addColumn($column);

$section->setData($registry->minitasks);
$section->show();




/*
if (count($completed) > 0) {
	$section = new UITableSection("Suoritetut minitaskit", "600px");		// labels
	$section->setOpen(true);
	$section->setFramesVisible(true);
	
	$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'tasks/tasks/removeminitask&taskID=' . $registry->task->taskID, 'rowID');
	
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $minitaskinsertsection->getID(), "Lisää");
	$section->addButton($button);
	
	$column = new UISortColumn("ID", "rowID", "tasks/projects/showtask", null, "20xp");
	$section->addColumn($column);
	
	$column = new UISortColumn("Nimike", "name", "tasks/projects/showtask");
	$section->addColumn($column);
	
	//$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "rowID", "tasks/tasks/completeminitask&taskID=" . $registry->task->taskID);
	//$column->setTitle("done");
	//$section->addColumn($column);
	
	$section->setData($completed);
	$section->show();
}
*/



$labelinsertsection = new UISection("Labelin lisäys");
$labelinsertsection->setDialog(true);
$labelinsertsection->setMode(UIComponent::MODE_INSERT);
$labelinsertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/tasks/insertlabel&taskID=' . $registry->task->taskID . '&projectID=' . $registry->task->projectID);

$field = new UISelectField("Label","labelID","labelID", $registry->labels, "name");
$labelinsertsection->addField($field);

$labelinsertsection->show();




$section = new UITableSection("Labels", $width);		// labels
$section->setOpen(true);
$section->setFramesVisible(true);


$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'tasks/tasks/removelabel&taskID=' . $registry->task->taskID, 'rowID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $labelinsertsection->getID(), "Lisää");
$section->addButton($button);

$column = new UISortColumn("#", "labelID", "tasks/projects/showtask", null, "10%");
$section->addColumn($column);

$column = new UISelectColumn("Label", "name", "labelID", $registry->labels);
$section->addColumn($column);

$section->setData($registry->tasklabels);
$section->show();




$section = new UITableSection("Logi", $width);
$section->setFramesVisible(true);
$section->setTableHeaderVisible(false);

$column = new UISortColumn("Time", "timestamp", "timestamp");
$column->setFormatter(Column::COLUMNTYPE_DATETIME);
$section->addColumn($column);

$column = new UISortColumn("Description", "description", "description");
$section->addColumn($column);

$column = new UISelectColumn("Käyttäjä", "name", "userID", $registry->users);
$section->addColumn($column);

$section->setData($registry->log);

$section->show();



$section = new UISection("Hallinta", $width);
$section->editable(false);
$field = new UISelectField("Tila","stateID","stateID", $registry->states, 'name');
$section->addField($field);
$section->setData($registry->task);

foreach($registry->transitions as $index => $transition) {

	if ($registry->task->stateID == $transition->startstateID) {
		$button = new UIButton(UIComponent::ACTION_FORWARD, "tasks/tasks/transition&id=" . $registry->task->taskID . "&target=" . $transition->targetstateID, $transition->name);
		$section->addButton($button);
	}
}

$button = new UIButton(UIComponent::ACTION_FORWARD, "tasks/tasks/removetask&id=" . $registry->task->taskID, "Poista");
$section->addButton($button);

$section->show();



?>