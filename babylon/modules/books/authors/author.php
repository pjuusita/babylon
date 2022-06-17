<?php


echo "<h1>" . $registry->author->name. "</h1>";

$section = new UISection("Perustiedot");
$section->setOpen(true);
$section->editable(true);


$section->setUpdateAction(UIComponent::ACTION_FORWARD,'books/authors/updateauthor', 'authorID');

$wordIDfield = new UIFixedTextField("AuthorID", $registry->author->authorID);
$section->addField($wordIDfield);

$field = new UITextField("Nimi", "name", 'name');
$section->addField($field);

$section->setData($registry->author);
$section->show();






$section = new UITableSection("Books", "600px");		// labels
$section->setOpen(true);
$section->setFramesVisible(true);

//$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'tasks/tasks/removelabel&taskID=' . $registry->task->taskID, 'rowID');

$column = new UISortColumn("#", "labelID", "");
$section->addColumn($column);

$column = new UISortColumn("Name", "name", "");
$section->addColumn($column);

$section->setData($registry->books);
$section->show();




$section = new UISection("Hallinta");
$section->editable(false);

$button = new UIButton(UIComponent::ACTION_FORWARD, "books/authors/removeauthor&id=" . $registry->author->authorID, "Poista");
$section->addButton($button);

$section->show();



?>