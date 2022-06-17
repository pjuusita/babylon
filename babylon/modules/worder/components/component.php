<?php



echo "<a href='".getUrl('worder/components/showcomponents')."'>Takaisin</a><br>";
echo "<h1>" . $registry->component->name . "</h1>";

$section = new UISection('Component','600px');
$section->setUpdateAction(UIComponent::ACTION_FORWARD,'worder/components/updatecomponent', 'componentID');

$field = new UITextField("Name", "name", 'name');
$section->addField($field);

$field = new UITextField("Abbreviation", "abbreviation", 'abbreviation');
$section->addField($field);

$field = new UISelectField("Parent", "parentID", "parentID", $this->registry->components, 'name');
$field->setPredictable(true);
$section->addField($field);

$field = new UITextAreaField("Description","description","description");
$section->addField($field);

$section->setData($registry->component);
$section->show();



$section = new UITableSection("Concepts","600px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/concepts/showconcept', 'conceptID');
$section->showLineNumbers(true);

$column = new UISelectColumn("Wordclass", "name", "wordclassID", $registry->wordclasses);
$section->addColumn($column);

$column = new UISortColumn("Concept", "name");
$section->addColumn($column);

$column = new UISortColumn("ID", "conceptID");
$section->addColumn($column);

$section->setData($registry->concepts);
$section->show();




$section = new UITableSection("Concepts with Argument","600px");
$section->setOpen(true);
$section->editable(true);
$section->setFramesVisible(true);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/concepts/showconcept', 'conceptID');
$section->showLineNumbers(true);

$column = new UISelectColumn("Wordclass", "name", "wordclassID", $registry->wordclasses);
$section->addColumn($column);

$column = new UISortColumn("Concept", "name");
$section->addColumn($column);

$column = new UISortColumn("ID", "conceptID");
$section->addColumn($column);

$section->setData($registry->argumentconcepts);
$section->show();





// ---------------------------------------------------------------------------------------------------
// Hallinta
// ---------------------------------------------------------------------------------------------------

$managementSection = new UISection("Hallinta");
$managementSection->editable(false);
$managementSection->setDebug(true);

$button = new UIButton(UIComponent::ACTION_FORWARD, "worder/components/removecomponent&id=".$registry->component->getID(), "Poista komponentti");
$managementSection->addButton($button);

$managementSection->show();


?>