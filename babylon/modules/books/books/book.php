<?php


echo "<h1>" . $registry->book->name. "</h1>";

$section = new UISection("Perustiedot");
$section->setOpen(true);
$section->editable(true);


$section->setUpdateAction(UIComponent::ACTION_FORWARD,'books/books/updatebook', 'bookID');

$bookIDfield = new UIFixedTextField("BookID", $registry->book->bookID);
$section->addField($bookIDfield);

$field = new UITextField("Nimi", "name", 'name');
$section->addField($field);

$field = new UITextField("Subtitle", "subtitle", 'subtitle');
$section->addField($field);



/*

$field = new UITextAreaField("Kuvaus","description","description");
$section->addField($field);
*/

$section->setData($registry->book);
$section->show();


/*

$minitaskinsertsection = new UISection("Minitaskin lisäys");
$minitaskinsertsection->setDialog(true);
$minitaskinsertsection->setMode(UIComponent::MODE_INSERT);
$minitaskinsertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/tasks/insertminitask&taskID=' . $registry->task->taskID);

$field = new UITextField("Nimi", "name", 'name');
$minitaskinsertsection->addField($field);

$minitaskinsertsection->show();

*/

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


/*
$editminitasksection = new UISection("Minitaskin muokkaus");

$editminitasksection->setDialog(true);
$editminitasksection->setMode(UIComponent::MODE_EDIT);
$editminitasksection->setSaveAction(UIComponent::ACTION_FORWARD, 'tasks/tasks/updateminitask&taskID=' . $registry->task->taskID, 'rowID');

$field = new UITextField("Name", "name", 'name');
$editminitasksection->addField($field);

$editminitasksection->show();

*/


$addauthorsection = new UISection("Add Author");
$addauthorsection->setDialog(true);
$addauthorsection->setMode(UIComponent::MODE_INSERT);
$addauthorsection->setSaveAction(UIComponent::ACTION_FORWARD, 'books/books/addauthortobook&bookID=' . $registry->book->bookID);


$field = new UISelectField("Author","authorID","authorID", $registry->authors, "name");
$addauthorsection->addField($field);

//$field = new UITextField("Nimi", "name", 'name');
//$addauthorsection->addField($field);

$addauthorsection->show();



$section = new UITableSection("Authors", "600px");		// labels
$section->setOpen(true);
$section->setFramesVisible(true);

//$section->setLineAction(UIComponent::ACTION_OPENDIALOG, $editminitasksection->getID(),"rowID");

$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'books/books/removeautor&bookID=' . $registry->book->bookID, 'authorID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $addauthorsection->getID(), "Lisää");
$section->addButton($button);

$column = new UISortColumn("ID", "authorID", "books/books/showtask", "20px");
$section->addColumn($column);

$column = new UISortColumn("Name", "name", "books/books/showtask", "480px");
$section->addColumn($column);

//$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "minitaskID", "tasks/tasks/completeminitask&taskID=" . $registry->task->taskID);		
//$column->setTitle("Done");
//$section->addColumn($column);

$section->setData($registry->bookauthors);
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
$labelinsertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'books/books/insertlabel&bookID=' . $registry->book->bookID);

$field = new UISelectField("Label","labelID","labelID", $registry->labels, "name");
$labelinsertsection->addField($field);

$labelinsertsection->show();




$section = new UITableSection("Labels", "600px");		// labels
$section->setOpen(true);
$section->setFramesVisible(true);


$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'books/books/removelabel&bookID=' . $registry->book->bookID, 'labelID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $labelinsertsection->getID(), "Lisää");
$section->addButton($button);

$column = new UISortColumn("#", "labelID", "", null, "10%");
$section->addColumn($column);

$column = new UISelectColumn("Name", "name", "labelID", $registry->labels);
$section->addColumn($column);

$section->setData($registry->booklabels);
$section->show();


/*

$section = new UITableSection("Logi", '600px');
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

*/

$section = new UISection("Hallinta");
$section->editable(false);
/*
$field = new UISelectField("Tila","stateID","stateID", $registry->states, 'name');
$section->addField($field);
*/

//$section->setData($registry->task);

/*
foreach($registry->transitions as $index => $transition) {

	if ($registry->task->stateID == $transition->startstateID) {
		$button = new UIButton(UIComponent::ACTION_FORWARD, "tasks/tasks/transition&id=" . $registry->task->taskID . "&target=" . $transition->targetstateID, $transition->name);
		$section->addButton($button);
	}
}
*/

$button = new UIButton(UIComponent::ACTION_FORWARD, "books/books/removebook&id=" . $registry->book->bookID, "Poista");
$section->addButton($button);

$section->show();



?>