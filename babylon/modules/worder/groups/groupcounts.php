<?php




$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->grouptypeID, $this->registry->wordgrouptypes, "worder/groups/showgroupcounts", "Sanaluokka","grouptypeID", "name");


echo "<table style='width:600px;'>";
echo "	<tr>";
echo "		<td style='width:60%;vertical-align:bottom'>";
echo "		</td>";
echo "		<td style='width:40%;text-align:right;'>";
$filterbox->show();
echo "		</td>";
echo "	</tr>";
echo "</table>";




// ---------------------------------------------------------------------------------------------------
// Lisää Ryhmä dialog
// ---------------------------------------------------------------------------------------------------


$insertsection = new UISection('Ryhmän lisäys','500px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/groups/insertgroup');

$field	= new UISelectField("Parent","parentID","parentID",$registry->allgroups, "name");
$insertsection->addField($field);

$field = new UITextField("Name", "name", 'Name');
$insertsection->addField($field);

//$field = new UITextField("Description", "description", 'Description');
//$insertsection->addField($field);

$insertsection->show();



// ---------------------------------------------------------------------------------------------------
// Ryhmä muokkaus dialog
// ---------------------------------------------------------------------------------------------------


// TODO: Ei käytössä
$editsection = new UISection('Ryhmän muokkaus','500px');
$editsection->setDialog(true);
$editsection->setMode(UIComponent::MODE_EDIT);
$editsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/groups/updategroup', 'wordgroupID');
$editsection->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/groups/deletegroup', 'wordgroupID');

/*
$field = new UITextField("RyhmäID", "wordgroupID", 'wordgroupID');
$editsection->addField($field);
*/

$field	= new UISelectField("Parent","parentID","parentID",$registry->groups, "name");
$editsection->addField($field);


$field = new UITextField("Name", "name", 'name');
$editsection->addField($field);

//$field = new UITextField("Kuvaus", "description", 'description');
//$editsection->addField($field);

$editsection->show();


// ---------------------------------------------------------------------------------------------------
// Ryhmä tree
// ---------------------------------------------------------------------------------------------------

$section = new UITableSection("Ryhmät lukumäärät", "600px");

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/groups/showgroup','wordgroupID');

$column = new UISortColumn("WordgroupID", "wordgroupID", "worder/groups/showgrouplist", "15%");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UISortColumn("Nimi", "name", "worder/groups/showgrouplist&sort=nimi","70%");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$section->addColumn($column);

$column = new UISelectColumn("Count",NULL, "wordgroupID", $this->registry->groupcounts, "15%");
$section->addColumn($column);

$section->setData($this->registry->groups);
$section->setShowTotal(true);

$section->show();


?>