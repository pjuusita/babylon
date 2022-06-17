<?php


echo "<h1>Generator - " . $registry->generator->name . "</h1>";


$section = new UISection("Perustiedot",'600px');
$section->setOpen(true);
$section->editable(true);


$section->setUpdateAction(UIComponent::ACTION_FORWARD,'tasks/generators/updategenerator', 'generatorID');

$wordIDfield = new UIFixedTextField("GeneratorID", $registry->generator->generatorID);
$section->addField($wordIDfield);

$field = new UISelectField("Project","projectID","projectID",$registry->projects, "name");
$section->addField($field);

$field = new UITextField("Nimi", "name", 'name');
$section->addField($field);

$field = new UITextField("Taskprefix", "taskprefix", 'taskprefix');
$section->addField($field);

$field = new UISelectField("Task startstate","startstateID","startstateID",$registry->states, "name");
$section->addField($field);

$field = new UISelectField("Task priority","priorityID","priorityID",$registry->priorities, "name");
$section->addField($field);


$field = new UISelectField("DefaultUser","userID","userID",$registry->users, "fullname");
$section->addField($field);


//$field = new UISelectField("Basetable","basetableID","tableID",$registry->tables, "name");
//$section->addField($field);

//$field = new UISelectField("Basenamecolumn", "basecolumnID", 'basecolumnID', $registry->columns, 'name');
//$field->setPredictable(true);
//$section->addField($field);

//$field = new UISelectField("Namecolumn","basenamecolumnID","basenamecolumnID",$registry->tables, "name");
//$section->addField($field);

//$field = new UISelectField("Basetable","basetableID","tableID",$registry->tables, "name");
//$section->addField($field);


//$field = new UITextField("Link", "targetlink", 'targetlink');
//$section->addField($field);

//$field = new UILineField();
//$section->addField($field);

$field = new UITextAreaField("Kuvaus","description","description");
$section->addField($field);

$section->setData($registry->generator);
$section->show();



$section = new UISection("Basetable",'600px');
$section->setOpen(true);
$section->editable(true);

$section->setUpdateAction(UIComponent::ACTION_FORWARD,'tasks/generators/updategenerator', 'generatorID');

$field = new UISelectField("Tietokantataulu","basetableID","basetableID",$registry->tables, "name");
$section->addField($field);

// Näkyvissä ainoastaan jos secondarytable on asetettu, oletuksena keyID
//$field = new UISelectField("Linkvariable", "basecolumnID", 'basecolumnID', $registry->columns, 'name');
//$field->setPredictable(true);
//$section->addField($field);

$field = new UISelectField("Name column", "basenamecolumnID", 'basenamecolumnID', $registry->columns, 'name');
$field->setPredictable(true);
$section->addField($field);

$field = new UISelectField("Language", "languageID", 'languageID', $registry->languages, 'languagename');
$section->addField($field);

//$field = new UITextField("Where-clause", "basefilter", 'basefilter');
//$section->addField($field);

$field = new UITextAreaField("Where-clause","basefilter","basefilter");
$section->addField($field);

//$field = new UITextField("Action", "actionpath", 'actionpath');
//$section->addField($field);
$field = new UISelectField("Actionpath", "actionID", 'actionID', $registry->actions, 'actionpath');
//$field->setPredictable(true);
$section->addField($field);

$field = new UISelectField("Actionvariable", "actionvariableID", 'actionvariableID', $registry->columns, 'name');
$field->setPredictable(true);
$section->addField($field);

if ($registry->generator->targettableID == 0) {
	$field = new UISelectField("Secondarytable","targettableID","targettableID",$registry->tables, "name");
	$section->addField($field);
}

$section->setData($registry->generator);
$section->show();


if ($registry->generator->targettableID > 0) {

	$section = new UISection("Secondarytable",'600px');
	$section->setOpen(true);
	$section->editable(true);

	$section->setUpdateAction(UIComponent::ACTION_FORWARD,'tasks/generators/updategenerator', 'generatorID');

	$field = new UISelectField("Tietokantataulu","targettableID","targettableID",$registry->tables, "name");
	$section->addField($field);

	$field = new UISelectField("Linkvariable", "targetcolumnID", 'targetcolumnID', $registry->columns, 'name');
	$field->setPredictable(true);
	$section->addField($field);
	
	$field = new UITextField("Where-clause", "targetfilter", 'targetfilter');
	$section->addField($field);
	
	$field	= new UISelectField("Targetoperator",'operator',"operator",$registry->operators);
	$section->addField($field);
	
	$field = new UITextField("Targetcount", "targetcount", 'targetcount');
	$section->addField($field);
	
	
	$section->setData($registry->generator);
	$section->show();
}


/*
$section = new UISection("Completion Condition",'600px');
$section->setOpen(true);
$section->editable(true);

$section->setUpdateAction(UIComponent::ACTION_FORWARD,'tasks/generators/updategenerator', 'generatorID');

$field = new UITextField("Completionquery", "completioncondition", 'completioncondition');
$section->addField($field);

$section->setData($registry->generator);
$section->show();
*/



$labelinsertsection = new UISection("Labelin lisäys");
$labelinsertsection->setDialog(true);
$labelinsertsection->setMode(UIComponent::MODE_INSERT);
$labelinsertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/generators/insertgeneratorlabel&generatorID=' . $registry->generator->generatorID);

$field = new UISelectField("Label","labelID","labelID", $registry->labels, "name");
$labelinsertsection->addField($field);

$labelinsertsection->show();



$section = new UITableSection("Task Labels", "600px");		// labels
$section->setOpen(true);
$section->setFramesVisible(true);

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'tasks/generators/removegeneratorlabel&generatorID=' . $registry->generator->generatorID, 'rowID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $labelinsertsection->getID(), "Lisää");
$section->addButton($button);

$column = new UISortColumn("#", "labelID", "", null, "10%");
$section->addColumn($column);

$column = new UISelectColumn("Label", "name", "labelID", $registry->labels);
$section->addColumn($column);

$section->setData($registry->generatorlabels);
$section->show();




// ---------------------------------------------------------------------------------------------------
// add Component
// ---------------------------------------------------------------------------------------------------

/*
$orcomponenentdialog = new UISection('Adding OR-Component','600px');
$orcomponenentdialog->setDialog(true);
$orcomponenentdialog->setMode(UIComponent::MODE_INSERT);
$orcomponenentdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/generators/insertorrequirement&generatorID=' . $this->registry->generator->generatorID);

$field = new UISelectField("Parent", "rowID", 'parentID', $registry->baserequirementlist, 'name');
$field->setPredictable(true);
$orcomponenentdialog->addField($field);

$field = new UISelectField("Column", "columnID", 'columnID', $registry->columns, 'name');
$field->setPredictable(true);
$orcomponenentdialog->addField($field);

$operatorfield	= new UISelectField("Operator",null,"operator",$registry->operators);
$orcomponenentdialog->addField($operatorfield);

$field = new UITextField("Value", "value", 'value');
$orcomponenentdialog->addField($field);

$orcomponenentdialog->show();


$andcomponenentdialog = new UISection('Adding AND-Component','600px');
$andcomponenentdialog->setDialog(true);
$andcomponenentdialog->setMode(UIComponent::MODE_INSERT);
$andcomponenentdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/generators/insertandrequirement&generatorID=' . $this->registry->generator->generatorID);


$field = new UISelectField("Parent", "rowID", 'parentID', $registry->baserequirementlist, 'name');
$field->setPredictable(true);
$andcomponenentdialog->addField($field);

$operatorfield	= new UISelectField("Operator",null,"operator",$registry->operators);
$andcomponenentdialog->addField($operatorfield);

$field = new UISelectField("Column", "columnID", 'columnID', $registry->columns, 'name');
$field->setPredictable(true);
$andcomponenentdialog->addField($field);

$field = new UITextField("Value", "value", 'value');
$andcomponenentdialog->addField($field);

$andcomponenentdialog->show();



$firstcomponenentdialog = new UISection('Adding Requirement','600px');
$firstcomponenentdialog->setDialog(true);
$firstcomponenentdialog->setMode(UIComponent::MODE_INSERT);
$firstcomponenentdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/generators/insertfirstrequirement&generatorID=' . $this->registry->generator->generatorID);

$field = new UISelectField("Column", "columnID", 'columnID', $registry->columns, 'name');
$field->setPredictable(true);
$firstcomponenentdialog->addField($field);

$operatorfield	= new UISelectField("Operator",null,"operator",$registry->operators);
$firstcomponenentdialog->addField($operatorfield);

$field = new UITextField("Value", "value", 'value');
$firstcomponenentdialog->addField($field);

$firstcomponenentdialog->show();


// ---------------------------------------------------------------------------------------------------
// Lesson component requirements
// ---------------------------------------------------------------------------------------------------


$section = new UITreeSection("Base requirements",'600px');
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
//$section->setButtonAlign(UIComponent::VALIGN_BOTTOM); // TODO: UITreeSectionista puuttuu tämä, UITableSectionista löytyy...

//$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/components/showcomponent','componentID');


if (count($registry->baserequirements) > 0) {
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $andcomponenentdialog->getID(), 'Add AND-requirement');
	$section->addButton($button);
	
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $orcomponenentdialog->getID(), 'Add OR-requirement');
	$section->addButton($button);
} else {

	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $firstcomponenentdialog->getID(), 'Add Requirement');
	$section->addButton($button);
	
}


$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'tasks/generators/removerequirement&generatorID=' . $this->registry->generator->generatorID, 'requirementID');

$column = new UISortColumn("#","requirementID");
$section->addColumn($column);

$column = new UISortColumn("Requirements", "name");
$section->addColumn($column);

$section->setData($registry->baserequirements);
$section->show();
*/




/*
$section = new UITreeSection("Completion requirements",'600px');
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
//$section->setButtonAlign(UIComponent::VALIGN_BOTTOM); // TODO: UITreeSectionista puuttuu tämä, UITableSectionista löytyy...

//$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/components/showcomponent','componentID');


if (count($registry->baserequirements) > 0) {
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $andcomponenentdialog->getID(), 'Add AND-requirement');
	$section->addButton($button);

	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $orcomponenentdialog->getID(), 'Add OR-requirement');
	$section->addButton($button);
} else {

	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $firstcomponenentdialog->getID(), 'Add Requirement');
	$section->addButton($button);

}


$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'tasks/generators/removerequirement&generatorID=' . $this->registry->generator->generatorID, 'requirementID');

$column = new UISortColumn("#","requirementID");
$section->addColumn($column);

$column = new UISortColumn("Requirements", "name");
$section->addColumn($column);

$section->setData(array());
$section->show();
*/




$table = new UITableSection("Tehtävät","600px");
$table->setFramesVisible(true);
$table->setShowSumRow(true);

$table->setLineAction(UIComponent::ACTION_FORWARD,"tasks/tasks/showtask","taskID");

$column = new UISortColumn("TaskID", "taskID");
$table->addColumn($column);

$column = new UISortColumn("Name", "name");
$table->addColumn($column);

$column = new UISelectColumn("State", "name", "stateID", $registry->states);
$table->addColumn($column);

$table->setData($registry->tasks);
$table->show();





$minitaskgeneratedialog = new UISection("Taskien generointi minitaskeina");

$minitaskgeneratedialog->setDialog(true);
$minitaskgeneratedialog->setMode(UIComponent::MODE_INSERT);
$minitaskgeneratedialog->setSaveAction(UIComponent::ACTION_JAVASCRIPT, 'generateminitasks_' . $minitaskgeneratedialog->getID());

$selectedcountfield = new UIFixedTextField("Valittuja", 0);
$minitaskgeneratedialog->addField($selectedcountfield);

$mtaskcountfield = new UITextField("Task lukumäärä", "taskcount", 'taskcount');
$mtaskcountfield->setOnBlur("taskcountchanged_" . $mtaskcountfield->getID() . "()");
$minitaskgeneratedialog->addField($mtaskcountfield);

$subtaskscountfield = new UITextField("Subtasks per Task", "subtaskscount", 'subtaskscount');
$subtaskscountfield->setOnBlur("subtaskschanged_" . $subtaskscountfield->getID() . "()");
$minitaskgeneratedialog->addField($subtaskscountfield);

$minitaskgeneratedialog->setOnOpenFunction('onminitasksdialog');
$minitaskgeneratedialog->show();

echo "	<script>";
echo "		function onminitasksdialog() {";
echo "			console.log('onminitasksdialog');";
echo "			console.log(' - selecteditems - '+selecteditems.length);";
echo "	 		" . $selectedcountfield->setValueJSFunction() . "(selecteditems.length);";
echo "	 		" . $mtaskcountfield->setValueJSFunction() . "(1);";
echo "	 		" . $subtaskscountfield->setValueJSFunction() . "(selecteditems.length);";
echo "		}";
echo "	</script>";


echo "<script>";
echo "	function taskcountchanged_" . $mtaskcountfield->getID() . "() {";
echo "		console.log('taskcountchanged');";
echo "	 	var taskcount = $('#editfield-". $mtaskcountfield->getID() ."').val();";
echo "		console.log(' - taskcount - '+taskcount);";
echo "		var pertask = Math.ceil(selecteditems.length / taskcount);";
echo "	 	" . $subtaskscountfield->setValueJSFunction() . "(pertask);";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function subtaskschanged_" . $subtaskscountfield->getID() . "() {";
echo "		console.log('subtaskschanged');";
echo "	 	var pertask = $('#editfield-". $subtaskscountfield->getID() ."').val();";
echo "		console.log(' - pertask - '+pertask);";
echo "		var pertask = Math.ceil(selecteditems.length / pertask);";
echo "	 	" . $mtaskcountfield->setValueJSFunction() . "(pertask);";
echo "	}";
echo "</script>";





$taskgeneratedialog = new UISection("Taskien generointi");

$taskgeneratedialog->setDialog(true);
$taskgeneratedialog->setMode(UIComponent::MODE_INSERT);
$taskgeneratedialog->setSaveAction(UIComponent::ACTION_JAVASCRIPT, 'generatetasks_' . $taskgeneratedialog->getID());

$taskcountfield = new UIFixedTextField("Valittuja", 0);
$taskgeneratedialog->addField($taskcountfield);

$taskgeneratedialog->setOnOpenFunction('ontasksdialogopen');
$taskgeneratedialog->show();

echo "	<script>";
echo "		function ontasksdialogopen() {";
echo "			console.log('onasksdialogopen');";
echo "			console.log(' - selecteditems - '+selecteditems.length);";
echo "	 		" . $taskcountfield->setValueJSFunction() . "(selecteditems.length);";
echo "		}";
echo "	</script>";




$itemssection = new UITableSection("Task Candidates","600px");
$itemssection->setFramesVisible(true);
//$itemssection->setShowSumRow(true);
$itemssection->showLineNumbers(true);

$button = new UIButton(UIComponent::ACTION_JAVASCRIPT, "createminitasks", 'Create minitasks');
$itemssection->addButton($button);

$button = new UIButton(UIComponent::ACTION_JAVASCRIPT, "createtasks", 'Create tasks');
$itemssection->addButton($button);


//$table->setLineAction(UIComponent::ACTION_FORWARD,"tasks/tasksets/showtaskset","setID");
//echo "<br>BasetableID - " . $registry->generator->basetableID;

if (($registry->generator->basetableID > 0) && ($registry->generator->basenamecolumnID > 0)) {
	$table = $registry->tables[$registry->generator->basetableID];
	$column = $registry->columns[$registry->generator->basenamecolumnID];
	//$keycolumn = $table->getKeyColumn();  // ei toimi tietokannasta ladatulle...
	$keycolumn = null;
	foreach($registry->columns as $columnID => $tempcolumn) {
		if ($tempcolumn->type == 2) {
			$keycolumn = $tempcolumn;
			break;
		}
	}
	
	$itemssection->showSelectBoxes(true, "selected", "selectable", $keycolumn->variablename);
	
	$sortcolumn = new UISortColumn($keycolumn->columnname, $keycolumn->variablename);
	$itemssection->addColumn($sortcolumn);
	
	if ($column->type == Column::COLUMNTYPE_MULTILANG) {
		$valuecolumn = new UIMultilangColumn($column->columnname, $column->variablename, $registry->generator->languageID);
		$itemssection->addColumn($valuecolumn);
	} else {
		$valuecolumn = new UISortColumn($column->columnname, $column->variablename);
		$itemssection->addColumn($valuecolumn);
	}
	
	//$column = new UISortColumn("Checkeable", "checkable");
	//$itemssection->addColumn($column);
	
	//$column = new UISortColumn("TaskID", "taskID");
	//$itemssection->addColumn($column);
	
	//$column = new UISortColumn("Taskname", "taskname");
	//$itemssection->addColumn($column);
	
	//$column = new UISortColumn("Taskname", "taskname");
	//$itemssection->addColumn($column);
	
	$taskcolumn = new UILinkColumn("Task", "taskname", "taskID", "tasks/tasks/showtask");
	$taskcolumn->setShowVariable("taskname");
	$itemssection->addColumn($taskcolumn);
	
}



$itemssection->setData($registry->items);
$itemssection->show();

echo "	<script>";

echo "		selecteditems = null;";

echo "		function createminitasks() {";
echo "			console.log('create minitasks');";
echo "			selecteditems = " . $itemssection->getSelectedItemsFunction() . "();";
echo "			tasklist = '';";
echo "			for(let i = 0; i < selecteditems.length; i++) {";
echo "				if (tasklist != '') tasklist = tasklist + ':';";
echo "				tasklist = tasklist + selecteditems[i];";
echo "			}";
echo "			console.log('tt - '+tasklist);";
echo "  		$('#sectiondialog-" . $minitaskgeneratedialog->getID() . "').dialog('open');";
echo "		}";


echo "		function createtasks() {";
echo "			console.log('create tasks');";
echo "			selecteditems = " . $itemssection->getSelectedItemsFunction() . "();";
echo "			tasklist = '';";
echo "			for(let i = 0; i < selecteditems.length; i++) {";
echo "				if (tasklist != '') tasklist = tasklist + ':';";
echo "				tasklist = tasklist + selecteditems[i];";
echo "			}";
echo "			console.log('tt - '+tasklist);";
echo "  		$('#sectiondialog-" . $taskgeneratedialog->getID() . "').dialog('open');";
echo "		}";
echo "	</script>";


echo "<script>";
echo "	function generateminitasks_" . $minitaskgeneratedialog->getID() ."() {";
//echo "		console.log('generateminitasks');";
//echo "		console.log(' - selecteditems - '+selecteditems.length);";
//echo "	 	var taskcount = $('#editfield-" . $taskcountfield->getID() . "').val();";
//echo "	 	" . $selectedcountfield->setValueJSFunction() . "(taskcount);";
//echo "		console.log('taskcount -'+taskcount);";
echo "			selecteditems = " . $itemssection->getSelectedItemsFunction() . "();";
echo "			tasklist = '';";
echo "			for(let i = 0; i < selecteditems.length; i++) {";
echo "				if (tasklist != '') tasklist = tasklist + ':';";
echo "				tasklist = tasklist + selecteditems[i];";
echo "			}";
echo "	 		var taskcount = $('#editfield-". $mtaskcountfield->getID() ."').val();";
echo "	 		var subtaskcount = $('#editfield-". $subtaskscountfield->getID() ."').val();";

echo "			url = '" . getUrl("tasks/generators/generateminitasks") . "&tasklist='+tasklist+'&generatorID=" . $registry->generator->generatorID . "&subtaskcount='+subtaskcount+'&taskcount='+taskcount;";
echo "			console.log('url - '+url);";
echo "			window.location = url;";
echo "	}";
echo "</script>";


echo "<script>";
echo "	function generatetasks_" . $taskgeneratedialog->getID() ."() {";
echo "		console.log('generatetasks');";
echo "		selecteditems = " . $itemssection->getSelectedItemsFunction() . "();";
echo "		tasklist = '';";
echo "		for(let i = 0; i < selecteditems.length; i++) {";
echo "			if (tasklist != '') tasklist = tasklist + ':';";
echo "			tasklist = tasklist + selecteditems[i];";
echo "		}";
echo "		url = '" . getUrl("tasks/generators/generatetasks") . "&tasklist='+tasklist+'&generatorID=" . $registry->generator->generatorID . "';";
echo "		console.log('url2 - '+url);";
echo "		window.location = url;";
echo "	}";
echo "</script>";





$managementSection = new UISection("Hallinta");
$managementSection->editable(false);
$managementSection->setDebug(true);

//$button = new UIButton(UIComponent::ACTION_FORWARD, "tasks/generators/showreferences&id=".$registry->rule->getID(), "Näytä viitteet");
//$managementSection->addButton($button);

//$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/rules/copyrule&id=".$registry->rule->getID(), "Kopioi sääntö");
//$managementSection->addButton($button);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/rules/removegenerator&id=".$registry->generator->getID(), "Remove generator");
$managementSection->addButton($button);

$managementSection->show();



?>