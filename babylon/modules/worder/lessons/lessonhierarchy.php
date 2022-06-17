<?php


$boardfilter = new UIFilterBox();
$boardfilter->addSelectFilter($this->registry->languageID, $registry->languages, "worder/lessons/showhierarchy", "", "languageID", "name");
$boardfilter->addSelectFilter($this->registry->lessonplanID, $registry->lessonplans, "worder/lessons/showhierarchy", "", "lessonplanID", "name");
$boardfilter->setEmptySelect(false);




$insertparentsection = new UISection("Lesson parent add");
$insertparentsection->setDialog(true);
$insertparentsection->setMode(UIComponent::MODE_INSERT);
$insertparentsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/lessons/insertlessonprerequisite');

$field = new UISelectField("Parent","lessonID","parentID",$registry->lessons, "name");
$field->setPredictable(true);
$insertparentsection->addField($field);

$field = new UISelectField("Lesson","lessonID","lessonID",$registry->lessons, "name");
$field->setPredictable(true);
$insertparentsection->addField($field);

$insertparentsection->show();





$languagessection = new UISection('Kielten näkyvyys','500px');
$languagessection->setDialog(true);
$languagessection->setMode(UIComponent::MODE_EDIT);
$languagessection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/lessons/updateactivelanguages');

$row = new Row();

foreach($this->registry->languages as $index => $language) {
	
	$field = new UIBooleanField($language->name, 'language-' . $language->languageID, 'language-' . $language->languageID);
	$languagessection->addField($field);
	if (isset($this->registry->activelanguages[$language->languageID])) {
		$var = 'language-' . $language->languageID;
		$row->$var = 0;
	}
}

foreach($this->registry->activelanguages as $index => $languageID) {
	$var = 'language-' . $languageID;
	$row->$var = 1;
}
$languagessection->setData($row);
$languagessection->show();



echo "<table style='width:600px;'>";
echo "	<tr>";
echo "		<td rowspan=3 style='width:70%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;padding-right:22px;'>";
$boardfilter->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";


$section = new UITreeSection("Lessonhierarkia", "600px");
//$section->setCollapse(true);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/lessons/showlesson','lessonID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertparentsection->getID(), "Lisää");
$section->addButton($button);

$column = new UIMultilangColumn("Nimi", "name", $this->registry->languageID);
$section->addColumn($column);

$column = new UISortColumn("LessonID", "lessonID");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);


$column = new UISortColumn("identifier", "identifier");
$section->addColumn($column);


$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "identifier", "worder/lessons/lessonup");
$column->setIcon("fa fa-chevron-up");
$section->addColumn($column);


$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "identifier", "worder/lessons/lessondown");
$column->setIcon("fa fa-chevron-down");
$section->addColumn($column);

$section->setData($this->registry->hierarchy);

$section->show();


echo "<br><br>";

$table = new UITableSection("Oppitunnit", "600px");
//$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
//$table->addButton($button);
$table->setSettingsAction($languagessection);
$table->setLineAction(UIComponent::ACTION_FORWARD,"worder/lessons/showlesson","lessonID");
$table->setLineBackground("color");
$table->showRowNumbers(true);

$column = new UISortColumn("#", "lessonID", "worder/lessons/showlesson", null, "10%");
$table->addColumn($column);


foreach($this->registry->activelanguages as $index => $languageID) {
	$language = $this->registry->languages[$languageID];
	$var = "name" . $languageID;
	$column = new UIMultilangColumn($language->name, "name", $languageID);
	$table->addColumn($column);
}

$column = new UISortColumn("Noun", "subscount", "worder/lessons/showlesson&sort=nimi", null, "10%");
$table->addColumn($column);

$column = new UISortColumn("Verb", "verbcount", "worder/lessons/showlesson&sort=nimi", null, "10%");
$table->addColumn($column);

$column = new UISortColumn("Adj", "arjcount", "worder/lessons/showlesson&sort=nimi", null, "10%");
$table->addColumn($column);

$column = new UISortColumn("Other", "othercount", "worder/lessons/showlesson&sort=nimi", null, "10%");
$table->addColumn($column);

$column = new UISortColumn("Total", "totalcount", "worder/lessons/showlesson&sort=nimi", null, "10%");
$table->addColumn($column);

$table->setData($registry->freelessons);
$table->show();

?>