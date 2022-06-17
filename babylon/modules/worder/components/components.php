<?php




/*
$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->languageID, $this->registry->languages, "worder/components/showcomponents", "Semantic", "languageID", "name");

echo "<table style='width:800px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
//$paginator->show();
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$filterbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";
*/



// ---------------------------------------------------------------------------------------------------
// Edit component dialog
// ---------------------------------------------------------------------------------------------------

// TODO: Tätä sectionia ei käytetä missään
/*
$editsection = new UISection('Edit component','500px');
$editsection->setDialog(true);
$editsection->setMode(UIComponent::MODE_EDIT);
$editsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/components/updatecomponent', 'componentID');
//$editsection->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/components/updatecomponent', 'componentID');

$languages = array();
$row = new Row();
$row->languageID = 0;
$row->name = "Shared";
$languages[0] = $row;
foreach($this->registry->languages as $index => $language) {
	$languages[$language->languageID] = $language;
}
$field = new UISelectField("Language", "languageID", "languageID", $languages, "name");
$field->acceptEmpty(false);
$field->setValue(null);
$editsection->addField($field);


$field = new UITextField("Abbreviation", "Abbreviation", 'abbreviation');
$editsection->addField($field);

$field = new UITextField("Nimi", "Name", 'name');
$editsection->addField($field);

$field = new UITextField("Kuvaus", "Description", 'description');
$editsection->addField($field);

$editsection->show();
*/


// ---------------------------------------------------------------------------------------------------
// Add New Component dialog
// ---------------------------------------------------------------------------------------------------

$insertsection = new UISection('Add New Component','500px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/components/insertcomponent&languageID=' . $this->registry->languageID);

/*
if ($this->registry->languageID > 0) {
	$language = $this->registry->languages[$this->registry->languageID];
	$field = new UIFixedTextField("Language", $language->name);
	$insertsection->addField($field);
} else {
	$field = new UIFixedTextField("Language", "Semantic");
	$insertsection->addField($field);
}
*/

$field = new UITextField("Name", "Name", 'name');
$insertsection->addField($field);

$field = new UITextField("Abbreviation", "abbreviation", 'abbreviation');
$insertsection->addField($field);

$field = new UISelectField("Parent", "parentID", "parentID", $this->registry->rules, 'name');
$insertsection->addField($field);

/*
$field = new UITextField("Abbreviation", "Abbreviation", 'abbreviation');
$insertsection->addField($field);

$field = new UITextField("Kuvaus", "Kuvaus", 'description');
$insertsection->addField($field);
*/

$insertsection->show();




$table = new UITreeSection("Components", "600px");
$table->setLineAction(UIComponent::ACTION_FORWARD, 'worder/components/showcomponent','componentID');
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Lisää component');
$table->addButton($button);

$column = new UISortColumn("Nimi", "name");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($column);

$column = new UISortColumn("Lyhenne", "abbreviation");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($column);

$column = new UISortColumn("#", "componentID");
$table->addColumn($column);


//$column = new UISelectColumn("Part of speech","name","wordclassID",$registry->wordclasses);
//$table->addColumn($column);

//$column = new UISelectColumn("Status",NULL,"status",$registry->statuses);
//$table->addColumn($column);

$table->setData($this->registry->components);
$table->show();


// ---------------------------------------------------------------------------------------------------
// Taulukko
// ---------------------------------------------------------------------------------------------------

/*
echo "<br><br>";

$table = new UITableSection("Components", "800px");
$table->setFramesVisible(false);
$table->setMode(UIComponent::MODE_EDIT);	// tämä vaaditaan, että editsectionille asetetaan kenttiin arvot
//$table->setLineAction(UIComponent::ACTION_OPENDIALOG,$editsection->getID(),"componentID");

$wordclassIDcolumn = new UISortColumn("ComponentID", "componentID", "worder/components/showcomponent");
$wordclassIDcolumn->setColumnType(Column::COLUMNTYPE_INTEGER);
$table->addColumn($wordclassIDcolumn);

$abbreviationcolumn = new UISortColumn("Abbreviation", "abbreviation", "worder/components/showcomponent&sort=nimi");
$abbreviationcolumn->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($abbreviationcolumn);

$nimicolumn = new UISortColumn("Nimi", "name", "worder/components/showcomponent&sort=nimi");
$nimicolumn->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($nimicolumn);

$desccolumn = new UISortColumn("Kuvaus", "description", "worder/components/showcomponent&sort=description");
$desccolumn->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($desccolumn);

$column = new UIHiddenColumn("LanguageID", "languageID");
$table->addColumn($column);

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), 'Lisää component');
$table->addButton($button);

$table->setData($this->registry->components);
$table->show();

*/

?>