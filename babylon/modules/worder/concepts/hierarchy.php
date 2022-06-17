<?php





$insertsection = new UISection("Käsitteen lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_NEWWINDOW, 'worder/concepts/addconcept&source=2');

$field = new UITextField("Käsite", "Käsite", 'name');
$insertsection->addField($field);

//$field= new UITextField("Kuvaus", "Description", "description");
//$insertsection->addField($field);

$field = new UISelectField("Sanaluokka","wordclassID","wordclassID",$registry->wordclasses, "name");
$insertsection->addField($field);

$insertsection->show();





$section = new UITreeSection("Käsitehierarkia", "600px");
//$section->setCollapse(true);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/concepts/showconcept','conceptID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$section->addButton($button);


$column = new UISortColumn("Nimi", "name");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$section->addColumn($column);

//$column = new UISortColumn("Sanaluokka", "wordclassID", "worder/concepts/");
$column = new UISelectColumn("Sanaluokka", "name", "wordclassID", $this->registry->wordclasses);
//$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UISortColumn("ConceptID", "conceptID");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);



$section->setData($this->registry->hierarchy);

$section->show();

echo "<br><br><br>";

?>