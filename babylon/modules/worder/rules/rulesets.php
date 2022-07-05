<?php




$languageID = $this->registry->languageID;


echo "<table style='width:800px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->languageID, $this->registry->languages, "worder/rules/showrulesets", "","languageID", "name");
$filterbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";



$insertsetsection = new UISection('Add RuleSet','500px');
$insertsetsection->setDialog(true);
$insertsetsection->setMode(UIComponent::MODE_INSERT);
//$insertsection->setInsertAction(UIComponent::ACTION_FORWARD, "worder/sentences/insertsentence&lang="+$registry->languageID);

$insertsetsection->setInsertAction(UIComponent::ACTION_FORWARD, "worder/rules/insertruleset");

$field = new UITextField("Name", "name", 'name');
$insertsetsection->addField($field);

$languagefield = new UISelectField("Kieli","languageID","languageID",$registry->languages, "name");
$insertsetsection->addField($languagefield);

$insertsetsection->show();




$table = new UITableSection("RuleSets", "800px");
$table->setFramesVisible(false);

$table->setLineAction(UIComponent::ACTION_FORWARD,"worder/rules/showruleset","setID");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsetsection->getID(), 'Add set');
$table->addButton($button);

$column = new UISortColumn("ID", "setID");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$table->addColumn($column);

$column = new UISelectColumn("Languages", "name", "languageID", $registry->languages);
$table->addColumn($column);

$column = new UISortColumn("Name", "name");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$table->addColumn($column);

$column = new UISelectColumn("Sentenceset", "name", "sentencesetID", $registry->sentencesets);
$table->addColumn($column);

$table->setData($this->registry->rulesets);
$table->show();



?>