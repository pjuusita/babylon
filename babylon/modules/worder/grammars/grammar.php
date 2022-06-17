<?php



echo "<h1>" . $registry->grammar->name . "</h1>";

$width = "600px";

$section = new UISection("Kielioppi",$width);
$section->setOpen(true);
$section->editable(true);

$section->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/grammars/updategrammar', 'grammarID');


$field = new UIFixedTextField("GrammarID", $registry->grammar->grammarID);
$section->addField($field);

$field = new UITextField("Name","name","name");
$section->addField($field);

$field = new UITextField("Description","description","description");
$section->addField($field);

if ($registry->grammar->multilangactive == 0) {
	$field = new UITextField("Language", "languagename", "languagename");
	$section->addField($field);
}

$field = new UIBooleanField("Concepts", "conceptsactive", "conceptsactive");
$section->addField($field);

$field = new UIBooleanField("Components", "componentsactive", "componentsactive");
$section->addField($field);

$field = new UIBooleanField("Multiple Languages", "multilangactive", "multilangactive");
$section->addField($field);

$section->setData($registry->grammar);
$section->show();




//-----------------------------------------------------------------------------
//   Ryhmän lisäys dialogi
//-----------------------------------------------------------------------------

//$contept = $registry->concepts[$registry->word->conceptID];

$dialog = new UISection('Kielen lisäys','500px');
$dialog->setDialog(true);
$dialog->setMode(UIComponent::MODE_INSERT);
$dialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/grammars/insertlanguage&grammarID=' . $registry->grammar->grammarID);

$field = new UITextField("Name", "Name", 'name');
$dialog->addField($field);

$dialog->show();



//-----------------------------------------------------------------------------
//   Ryhmä section
//-----------------------------------------------------------------------------

if ($registry->grammar->multilangactive == 1) {

	$table = new UITableSection("Kielet",$width);
	$table->setOpen(true);
	$table->editable(true);
	$table->setFramesVisible(true);
	
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $dialog->getID(), 'Lisää kieli');
	$table->addButton($button);
	
	$table->setDeleteAction(UIComponent::ACTION_FORWARD,  'worder/grammars/removelanguage&grammarID=' . $registry->grammar->grammarID, 'languageID');
	
	$column = new UISortColumn("LanguageID", "languageID");		// tää pitäisi olla taulun sisäinen operaatio innertablella
	$table->addColumn($column);
	
	$column = new UISortColumn("Name", "name");
	$table->addColumn($column);
	
	$column = new UISortColumn("Abbreviation", "shortname");
	$table->addColumn($column);
	
	$table->setData($registry->languages);
	$table->show();
}	




$editstatesection = new UISection('Tilan muokkaus',$width);
$editstatesection->setDialog(true);
$editstatesection->setMode(UIComponent::MODE_EDIT);
$editstatesection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/grammars/updatestate&grammarID=' . $registry->grammar->grammarID, 'stateID');

$field = new UITextField("Name", "name", 'name');
$editstatesection->addField($field);

$field = new UIColorField("Väri","colorID","colorID",$registry->colors);
$editstatesection->addField($field);

$editstatesection->show();


$insertstatesection = new UISection("Tilan lisäys");
$insertstatesection->setDialog(true);
$insertstatesection->setMode(UIComponent::MODE_INSERT);
$insertstatesection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/grammars/insertstate&grammarID=' . $registry->grammar->grammarID);

$nimifield = new UITextField("Nimike", "Nimike", 'name');
$insertstatesection->addField($nimifield);

$field = new UIColorField("Väri","colorID","colorID",$registry->colors);
$insertstatesection->addField($field);

$insertstatesection->show();


$table = new UITableSection("Tilat", $width);
$table->setMode(UIComponent::MODE_EDIT);
$table->setFramesVisible(true);
//$table->setTableHeaderVisible(false);
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertstatesection->getID(), "Lisää");
$table->addButton($button);
$table->setLineAction(UIComponent::ACTION_OPENDIALOG, $editstatesection->getID(),"stateID");

$column = new UISortColumn("#", "stateID", "", null, "10%");
$table->addColumn($column);

$column = new UISortColumn("Nimi", "name", "");
$table->addColumn($column);

$column = new UIColorColumn("Väri", "colorID", "colorID", $registry->colors);
$table->addColumn($column);

$table->setData($registry->states);
$table->show();



$managementSection = new UISection("Hallinta",$width);
$managementSection->editable(false);
$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/grammar/removegrannar&noframes=1&grammarID=" . $registry->grammar->grammarID, "Remove grammar");
$managementSection->addButton($button);
$managementSection->show();


?>