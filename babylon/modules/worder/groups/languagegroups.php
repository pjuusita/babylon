<?php


$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->grouptypeID, $this->registry->grouptypes, "worder/groups/showlanguagegrouplist&languageid=" . $this->registry->languageID, "Tyyppi","grouptypeID", "name");


echo "<table style='width:600px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$filterbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";



$section = new UITreeSection("Ryhmät", "600px");

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/groups/showgroup&languageid=' . $this->registry->languageID ,'wordgroupID');

$column = new UISortColumn("Nimi", "name", "worder/groups/showgrouplist&sort=nimi");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$section->addColumn($column);

$column = new UISortColumn("WordgroupID", "wordgroupID", "worder/groups/showgrouplist");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UISortColumn("ParentID", "parentID", "worder/groups/showgrouplist");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

// Lisää ryhmän kieli

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "wordgroupID", "worder/groups/movegroup&dir=up");
$column->setIcon("fa fa-chevron-up");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "wordgroupID", "worder/groups/movegroup&dir=down");
$column->setIcon("fa fa-chevron-down");
$section->addColumn($column);

$section->setData($this->registry->groups);

$section->show();



?>