<?php


$languageID = $this->registry->languageID;


echo "<table style='width:900px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->languageID, $this->registry->languages, "worder/sentences/showclipsets", "","languageID", "name");
$filterbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";


$insertsetsection = new UISection('Add ClipSet','500px');
$insertsetsection->setDialog(true);
$insertsetsection->setMode(UIComponent::MODE_INSERT);
//$insertsection->setInsertAction(UIComponent::ACTION_FORWARD, "worder/sentences/insertsentence&lang="+$registry->languageID);

$insertsetsection->setInsertAction(UIComponent::ACTION_FORWARD, "worder/audio/insertclipset&languageID=" . $this->registry->languageID);

$field = new UITextField("Name", "name", 'name');
$insertsetsection->addField($field);

$field = new UISelectField("Language","languageID","languageID",$registry->languages, "name");
$insertsetsection->addField($field);

$row = new Row();
$row->languageID = $this->registry->languageID;

$insertsetsection->setData($row);
$insertsetsection->show();




//$table = new UITreeSection("Rules", $width);
//$table = new UITableSection("Sentence Sets", "900px");
$table = new UITreeSection("ClipSets", "900px");
$table->setFramesVisible(false);

$table->setLineAction(UIComponent::ACTION_FORWARD,"worder/sentences/showclipset","setID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsetsection->getID(), 'Add set');
$table->addButton($button);

$column = new UISortColumn("ID", "setID");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$table->addColumn($column);

$column = new UISortColumn("Set Name", "name");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($column);

$column = new UISelectColumn("Language", "name", "languageID", $registry->languages);
$table->addColumn($column);

$table->setData($this->registry->sets);
$table->show();



?>