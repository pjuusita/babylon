<?php


$languageID = $this->registry->languageID;


echo "<table style='width:900px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->languageID, $this->registry->languages, "worder/sentences/showtemplates", "","languageID", "name");
$filterbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";

$insertsetsection = new UISection('Add TestTemplate','500px');
$insertsetsection->setDialog(true);
$insertsetsection->setMode(UIComponent::MODE_INSERT);
//$insertsection->setInsertAction(UIComponent::ACTION_FORWARD, "worder/sentences/insertsentence&lang="+$registry->languageID);

$insertsetsection->setInsertAction(UIComponent::ACTION_FORWARD, "worder/sentences/insertsentenceset&languageID=" . $this->registry->languageID);

$field = new UITextField("Prefix", "prefixstrings", 'prefixstrings');
$insertsetsection->addField($field);

$field = new UITextField("Postfix", "postfixstrings", 'postfixstrings');
$insertsetsection->addField($field);

$field = new UISelectField("Language","languageID","languageID",$registry->languages, "name");
$insertsetsection->addField($field);

$row = new Row();
$row->languageID = $this->registry->languageID;

$insertsetsection->setData($row);
$insertsetsection->show();




//$table = new UITreeSection("Rules", $width);
//$table = new UITableSection("Sentence Sets", "900px");
$table = new UITableSection("Test Templates", "900px");
$table->setFramesVisible(false);

$table->setLineAction(UIComponent::ACTION_FORWARD,"worder/sentences/showtemplate","templateID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsetsection->getID(), 'Add set');
$table->addButton($button);

$column = new UISortColumn("ID", "templateID");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$table->addColumn($column);

$column = new UISortColumn("Name", "name");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($column);

$column = new UISelectColumn("Language", "name", "languageID", $registry->languages);
$table->addColumn($column);

$table->setData($this->registry->testtemplates);
$table->show();



?>