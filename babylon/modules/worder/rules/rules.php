<?php

$width = "850px";

//echo "<br>Test";
//echo "<br>language - " . $this->registry->languageID;

$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->languageID, $this->registry->languages, "worder/rules/showrules", "Kieli", "languageID", "name");
$filterbox->setEmptySelect(false);

echo "<table style='width:" . $width . "'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$filterbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";




$insertresultdialog = new UISection('Loppusäännön lisäys',"600px");
$insertresultdialog->setDialog(true);
$insertresultdialog->setMode(UIComponent::MODE_INSERT);
$insertresultdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/rules/insertresultrule');

$field	= new UISelectField("Language","languageID","languageID",$registry->languages, "name");
$insertresultdialog->addField($field);

$posfield	= new UISelectField("Part of speech","wordclassID","wordclassID",$registry->wordclasses, "name");
$insertresultdialog->addField($posfield);

$field = new UITextField("Name", "name", 'name');
$insertresultdialog->addField($field);

$insertresultdialog->show();





$insertdialog = new UISection('Säännön lisäys',"600px");
$insertdialog->setDialog(true);
$insertdialog->setMode(UIComponent::MODE_INSERT);
$insertdialog->setSaveAction(UIComponent::ACTION_NEWWINDOW, 'worder/rules/insertrule');

$field	= new UISelectField("Parent","parentID","parentID",$registry->rules, "name");
$insertdialog->addField($field);

$field	= new UISelectField("Language","languageID","languageID",$registry->languages, "name");
$insertdialog->addField($field);

$posfield	= new UISelectField("Part of speech","wordclassID","wordclassID",$registry->wordclasses, "name");
//$posfield->setOnChange("poschanged()");
$insertdialog->addField($posfield);

/*
$argumentfield	= new UISelectField("Argument","argumentID","argumentID",$registry->arguments, "name");
$insertdialog->addField($argumentfield);

$field = new UITextField("Argumentposition", "argumentposition", 'argumentposition');
$insertdialog->addField($field);
*/

$field = new UITextField("Name", "name", 'name');
$insertdialog->addField($field);

$field = new UITextField("Description", "description", 'description');
$insertdialog->addField($field);

$insertdialog->show();


$table = new UITableSection("Result Structures", $width);
$table->setLineAction(UIComponent::ACTION_FORWARD, 'worder/rules/showresultrule','ruleID');
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertresultdialog->getID() ,'Lisää uusi');
$table->addButton($button);

$column = new UISortColumn("Nimi", "name", "worder/rules/showrules&sort=nimi");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($column);

$column = new UISelectColumn("POS","name","wordclassID",$registry->wordclasses);
$table->addColumn($column);

$column = new UISelectColumn("Status",NULL,"status",$registry->statuses);
$table->addColumn($column);


$table->setData($this->registry->resultrules);
$table->show();


echo "<br>";

$table = new UITreeSection("Rules", $width);
$table->setLineAction(UIComponent::ACTION_FORWARD, 'worder/rules/showrule','ruleID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertdialog->getID() ,'Lisää uusi');
$table->addButton($button);

$column = new UISortColumn("Nimi", "name", "worder/rules/showrules&sort=nimi");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($column);

$column = new UISelectColumn("POS","name","wordclassID",$registry->wordclasses);
$table->addColumn($column);

$column = new UISortColumn("ID", "ruleID", "worder/rules/showrules&sort=nimi");
$table->addColumn($column);

$column = new UIBooleanColumn("Generate","generate");
$table->addColumn($column);

$column = new UIBooleanColumn("Analyse","analyse");
$table->addColumn($column);

$column = new UISelectColumn("Status",NULL,"status",$registry->statuses);
$table->addColumn($column);

$column = new UIHiddenColumn("RuleID","ruleID");
$table->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "ruleID", "worder/rules/moverule&dir=up");
$column->setIcon("fa fa-chevron-up");
$table->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "ruleID", "worder/rules/moverule&dir=down");
$column->setIcon("fa fa-chevron-down");
$table->addColumn($column);

$table->setData($this->registry->rules);
$table->show();



?>
