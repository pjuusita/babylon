<?php



$boardfilter = new UIFilterBox();
$boardfilter->addSelectFilter($this->registry->languageID, $registry->languages, "worder/lessons/showlessons", "", "languageID", "name");
$boardfilter->setEmptySelect(false);


$insertsection = new UISection("Tavoitteen lisäys");
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/inflectionsets/insertinflectionset');

$field = new UISelectField("Kieli","name","languageID",$registry->languages, 'name');
$insertsection->addField($field);

$field = new UISelectField("Parent","inflectionsetID","inflectionsetID",$registry->inflectionsets, 'name');
$insertsection->addField($field);

$nimifield = new UITextField("Nimike", "Nimike", 'name');
$insertsection->addField($nimifield);

$field = new UITextField("Kuvaus", "description", 'decription');
$insertsection->addField($field);

$insertsection->show();


echo "<table style='width:600px;'>";
echo "	<tr>";
echo "		<td rowspan=3 style='width:70%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$boardfilter->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";



$newobjectivesection = new UISection("Create New Objective");
$newobjectivesection->setDialog(true);
$newobjectivesection->setMode(UIComponent::MODE_INSERT);
$newobjectivesection->setSaveAction(UIComponent::ACTION_NEWWINDOW, 'worder/objectives/createobjective&languageID=' . $this->registry->languageID);

$field = new UITextField("Name", "name", 'name');
$newobjectivesection->addField($field);

$field = new UISelectField("Wordclass","wordclassID","wordclassID", $registry->wordclasses, "name");
$newobjectivesection->addField($field);

$newobjectivesection->show();




$table = new UITableSection("Objectives", "600px");
$table->setLineAction(UIComponent::ACTION_FORWARD,"worder/objectives/showobjective","objectiveID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $newobjectivesection->getID(), 'Add objective');
$table->addButton($button);

$column = new UISortColumn("Nimi", "name");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($column);

$column = new UISortColumn("#", "objectiveID");
$table->addColumn($column);

$table->setData($this->registry->objectives);
$table->show();


?>