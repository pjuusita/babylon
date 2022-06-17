<?php



$wordclassbox = new UIFilterBox();
$wordclassbox->addSelectFilter($this->registry->wordclassID, $this->registry->wordclasses, "worder/concepts/showconcepts", "Sanaluokka","wordclassID", "name");
//$wordclassbox->addTextFilter("worder/concepts/showconcepts", $this->registry->search, "Tekstihaku", "search");

//$languagebox = new UIFilterBox();
$wordclassbox->addSelectFilter($this->registry->languageID, $this->registry->languages, "worder/concepts/showconcepts", "Kieli","languageID", "name");
//$languagebox->addTextFilter("worder/concepts/showconcepts", $this->registry->search, "Tekstihaku", "search");
//$paginator->setFastFind(true);
//$paginator->setPagingStyle("pagenumber");




echo "<table style='width:800px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
//$paginator->show();
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$wordclassbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";






$table = new UITableSection("Käsitteet", '800px');

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/concepts/showconcepts&wordgroupId=0&wordclassID=0&search=", "Tyhjennä filtteri");
$table->addButton($button);

$table->showLineNumbers(true);

//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
//$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"worder/concepts/showconcept","conceptID");

$idcolumn = new UISortColumn("#", "conceptID");
$table->addColumn($idcolumn);

$wordclasscolumn = new UISelectColumn("Sanaluokka", "name", "wordclassID", $this->registry->wordclasses, "");
$wordclasscolumn->setColumnType(Column::COLUMNTYPE_INTEGER);
$table->addColumn($wordclasscolumn);

$column = new UISortColumn("Name", "name");
$table->addColumn($column);

$column = new UISortColumn("LessonID", "lessonID");
$table->addColumn($column);

$column = new UISortColumn("Word", "word");
$table->addColumn($column);

$table->setData($this->registry->concepts);

$table->show();


echo "<br>Total - " . $this->registry->fullcount;
echo "<br>Found - " . $this->registry->found;
echo "<br>Missing - " . ($this->registry->fullcount - $this->registry->found);

/*

$insertsection = new UISection("Käsitteen lisäys 2");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_NEWWINDOW, 'worder/concepts/addconcept');


$nimifield = new UITextField("Käsite", "Käsite", 'name');
$insertsection->addField($nimifield);

//$descriptionfield= new UITextField("Kuvaus", "Description", "description");
//$insertsection->addField($descriptionfield);

$wordclassfield = new UISelectField("Sanaluokka","wordclassID","wordclassID",$registry->wordclasses, "name");
$insertsection->addField($wordclassfield);

$insertsection->show();

*/


/*

$table = new UITableSection("Käsitteet", '800px');

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/concepts/showconcepts&wordgroupId=0&wordclassID=0&search=", "Tyhjennä filtteri");
$table->addButton($button);

$table->showLineNumbers(true);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$table->addButton($button);

$table->setLineAction(UIComponent::ACTION_FORWARD,"worder/concepts/showconcept","conceptID");

$idcolumn = new UISortColumn("#", "conceptID");
$table->addColumn($idcolumn);

$wordclasscolumn = new UISelectColumn("Sanaluokka", "name", "wordclassID", $this->registry->wordclasses, "");
$wordclasscolumn->setColumnType(Column::COLUMNTYPE_INTEGER);
$table->addColumn($wordclasscolumn);

//$column = new UIMultilangColumn("Concept", "name", 2);
$column = new UISortColumn("Name", "name");
$table->addColumn($column);

/*
$column = new UISortColumn("Suomi", "finnish_word");
$table->addColumn($column);

$column = new UISortColumn("Englanti", "english_word");
$table->addColumn($column);

$column = new UISortColumn("Ruotsi", "swedish_word");
$table->addColumn($column);

$column = new UISortColumn("Espanja", "spanish_word");
$table->addColumn($column);

$column = new UISortColumn("Saksa", "german_word");
$table->addColumn($column);

$column = new UISortColumn("Viro", "estonian_word");
$table->addColumn($column);

$column = new UISortColumn("Venäjä", "russian_word");
$table->addColumn($column);
*/
/*
$wordcolumn = new UISortColumn("Word_fi", "word_fi", "");
$wordcolumn->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($wordcolumn);
*/

/*
$column = new UIMultilangColumn("Kuvaus", "gloss", 2);
$table->addColumn($column);


$column = new UISortColumn("Freq", "frequency");
$table->addColumn($column);
*/

//$glosscolumn = new UISortColumn("Gloss", "gloss", "");
//$glosscolumn->setColumnType(Column::COLUMNTYPE_STRING);
//$table->addColumn($glosscolumn);

/*
$raritycolumn = new UISelectColumn("Rarity", "name", "rarityID", $this->registry->rarities, "");
$raritycolumn->setColumnType(Column::COLUMNTYPE_INTEGER);
$table->addColumn($raritycolumn);
* /

$table->setData($this->registry->concepts);

$table->show();
*/

?>