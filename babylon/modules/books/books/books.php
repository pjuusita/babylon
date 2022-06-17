<?php

//echo "<br>windowID:" . $_SESSION['windowID'];
//echo "<br>";

//echo "" . $_SERVER['PHP_SELF'];
//echo "<br>" . $_SERVER['QUERY_STRING'];
//echo "<br>";

// [15.10.2021] Kopioitu projects/tasks.php


$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($registry->labelID, $registry->labels, "books/books/showbooks", "", "labelID", "name");
//$filterbox->addTextFilter("books/books/showbooks", $registry->search, "Search", "search");


echo "<table style='width:600px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$filterbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";


$insertsection = new UISection("Kirjan lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'books/books/insertbook');

$nimifield = new UITextField("Name", "name", 'name');
$insertsection->addField($nimifield);

// TODO: Pitää tsekata onko ko. käyttäjällä oikeuksia lisätä useampaan projektiin, jos vain yksi niin fixed
/*
$field = new UISelectField("Projekti","projectID","projectID", $registry->projects, "name");
$insertsection->addField($field);

$field = new UISelectField("Label","labelID","labelID", $registry->labels, "name");
$insertsection->addField($field);

$field = new UISelectField("Prioriteetti","priorityID","priorityID", $registry->priorities, "name");
$insertsection->addField($field);

$field = new UISelectField("Alkutila","stateID","stateID", $registry->states, "name");
$insertsection->addField($field);


// TODO: Pitää tsekata onko ko. käyttäjällä oikeuksia lisätä taskeja muille kuin itselleen...
// TODO: käyttäjiin pitäisi oikeastaan pompsahtaa vain ko. projektin memberit..
//$field = new UISelectField("Käyttäjä","userID","userID", $registry->users, "username");
//$insertsection->addField($field);

// TODO: oletukseksi tulee ensimmäinne startstate, jos jätetään tyhjäksi, niin 
// TODO: ehkä state pitää piilottaa ja aina oletus startstateen.
//$field = new UISelectField("State","stateID","stateID", $registry->states, "name");
//$insertsection->addField($field);

$row = new Row();
$row->projectID = $this->registry->projectID;
if (isset($this->registry->labelID)) $row->labelID = $this->registry->labelID;
if (isset($this->registry->stateID)) $row->stateID = $this->registry->stateID;
$insertsection->setData($row);
*/
$insertsection->show();




$table = new UITableSection("Books", "600px");
$table->showLineNumbers(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"books/books/showbook","bookID");

$column = new UISortColumn("Nimi", "name", "tasks/projects/showproject&sort=nimi", null, "60%");
$table->addColumn($column);


//$column = new UISortColumn("Prefix", "prefix", "tasks/projects/showproject&sort=nimi", null, "30%");
//$table->addColumn($column);


//$column = new UISelectColumn("Workflow", "name", "workdflowID", $this->registry->workflows);
//$table->addColumn($column);

$table->setData($registry->books);
$table->show();



?>