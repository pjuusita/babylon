<?php


echo "<a href='".getUrl('worder/arguments/showarguments')."'>Palaa arguments-listalle</a><br>";

echo "<h1>" . $registry->argument->name . "</h1>";




$width = "700px";

// ---------------------------------------------------------------------------------------------------
// Perustiedot
// ---------------------------------------------------------------------------------------------------

$section = new UISection("Argument", $width);
$section->setOpen(true);
$section->editable(true);
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/arguments/updateargument', 'argumentID');

$field = new UITextField("Nimi","name","name");
$section->addField($field);

// argumentit ovat kieliriippumattomia, languageID-sarake on turha
//$field = new UISelectField("Language", "languageID", "languageID", $this->registry->languages, 'name');
//$section->addField($field);

// TODO: tämä pitänee olla static?
$field = new UISelectField("Sanaluokka","wordclassID","wordclassID",$registry->wordclasses, "name");
$section->addField($field);

$field = new UISelectField("Arvojoukko","wordclassvalueID","wordclassvalueID",$registry->wordclasses, "name");
$section->addField($field);

$field = new UITextAreaField("Selite","description","description");
$section->addField($field);

$section->setData($registry->argument);
$section->show();




// ---------------------------------------------------------------------------------------------------
// Used in Rules
// ---------------------------------------------------------------------------------------------------

$section = new UITableSection("Used in Rules", $width);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);

$column = new UISortColumn("ID", "ruleID");
$section->addColumn($column);

$column = new UISortColumn("Name", "name");
$section->addColumn($column);

$column = new UISelectColumn("Language", "name", "languageID", $registry->languages);
$section->addColumn($column);

$section->setData($registry->rules);
$section->show();





// ---------------------------------------------------------------------------------------------------
// Used in Concepts
// ---------------------------------------------------------------------------------------------------

$section = new UITableSection("Used in Concepts", $width);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);

//$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removegroup&conceptID=' . $registry->concept->conceptID, 'wordgroupID');
//$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/groups/showgroup','wordgroupID');

$column = new UISortColumn("ID", "conceptID");
$section->addColumn($column);

$column = new UISortColumn("Name", "name");
$section->addColumn($column);

$section->setData($registry->concepts);
$section->show();



// ---------------------------------------------------------------------------------------------------
// Used in Concepts
// ---------------------------------------------------------------------------------------------------

$section = new UITableSection("Used in Concepts Recursive", $width);
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);
$section->showLineNumbers(true);
//$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/concepts/removegroup&conceptID=' . $registry->concept->conceptID, 'wordgroupID');
//$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/groups/showgroup','wordgroupID');

$column = new UISortColumn("ID", "conceptID");
$section->addColumn($column);

$column = new UISortColumn("Name", "name");
$section->addColumn($column);

$section->setData($registry->allconcepts);
$section->show();



$managementSection = new UISection("Hallinta","700px");
$managementSection->editable(false);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/arguments/removeargument&argumentID=". $this->registry->argument->argumentID, "Remove argument");
$managementSection->addButton($button);

$managementSection->show();




?>