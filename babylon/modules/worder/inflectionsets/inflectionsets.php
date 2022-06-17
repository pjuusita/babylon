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


echo "<table style='width:800px;'>";
echo "	<tr>";
echo "		<td rowspan=3 style='width:70%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$boardfilter->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";




$section = new UITreeSection("Inflectionsets", "800px");
//$section->setCollapse(true);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/inflectionsets/showinflectionset','inflectionsetID');

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID(), "Lisää");
$section->addButton($button);

$column = new UIMultilangColumn("Nimi", "name", $this->registry->languageID);
$section->addColumn($column);

$column = new UISortColumn("#", "inflectionsetID");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UISortColumn("Itemcount", "itemcount");
$section->addColumn($column);

$column = new UISortColumn("Wordcount", "wordcount");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "inflectionsetID", "worder/inflectionsets/inflectionsetup");
$column->setIcon("fa fa-chevron-up");
$section->addColumn($column);


$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "inflectionsetID", "worder/inflectionsets/inflectionsetdown");
$column->setIcon("fa fa-chevron-down");
$section->addColumn($column);

$section->setData($this->registry->hierarchy);

$section->show();



?>