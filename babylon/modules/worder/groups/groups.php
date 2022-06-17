<?php


$filterbox = new UIFilterBox();
$filterbox->addSelectFilter($this->registry->grouptypeID, $this->registry->grouptypes, "worder/groups/showgrouplist", "Tyyppi","grouptypeID", "name");
$filterbox->addSelectFilter($this->registry->languageID, $this->registry->languages, "worder/groups/showgrouplist", "Kieli","languageID", "name");


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


$insertsection = new UISection('Ryhmän lisäys2','500px');
$insertsection->setDialog(true);
$insertsection->setMode(UIComponent::MODE_INSERT);
$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/groups/insertgroup&grouptypeID=' . $this->registry->grouptypeID);

$field	= new UISelectField("Parent","parentID","parentID",$registry->groupselection, "name");
$insertsection->addField($field);

$field = new UITextField("Name", "name", 'Name');
$insertsection->addField($field);

$field	= new UISelectField("Language","languageID","languageID",$registry->languages, "name");
$insertsection->addField($field);



//$field	= new UISelectField("Type","grouptypeID","wordgrouptype",$registry->grouptypes, "name");
//$insertsection->addField($field);

$insertsection->show();



$insertlinkdialog = new UISection('Ryhmälinkin lisäys','500px');
$insertlinkdialog->setDialog(true);
$insertlinkdialog->setMode(UIComponent::MODE_INSERT);
$insertlinkdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/groups/insertgrouplink&grouptypeID=' . $this->registry->grouptypeID);

$field	= new UISelectField("Parent","parentID","parentID",$registry->groupselection, "name");
$insertlinkdialog->addField($field);

$field	= new UISelectField("Group","wordgroupID","wordgroupID",$registry->groupselection, "name");
$insertlinkdialog->addField($field);

$insertlinkdialog->show();



$deletelinkdialog = new UISection('Ryhmälinkin poisto','500px');
$deletelinkdialog->setDialog(true);
$deletelinkdialog->setMode(UIComponent::MODE_INSERT);
$deletelinkdialog->setSaveAction(UIComponent::ACTION_FORWARD, 'worder/groups/deletegrouplink&grouptypeID=' . $this->registry->grouptypeID);

$field	= new UISelectField("Parent","parentID","parentID",$registry->groupselection, "name");
$deletelinkdialog->addField($field);

$field	= new UISelectField("Group","wordgroupID","wordgroupID",$registry->groupselection, "name");
$deletelinkdialog->addField($field);

$deletelinkdialog->show();



// ---------------------------------------------------------------------------------------------------
// Ryhmä tree
// ---------------------------------------------------------------------------------------------------

$section = new UITreeSection("Ryhmät 1", "600px");

$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Uusi ryhmä');
$section->addButton($button);
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertlinkdialog->getID() ,'Uusi linkki');
$section->addButton($button);
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $deletelinkdialog->getID() ,'poista linkki');
$section->addButton($button);

$section->setLineAction(UIComponent::ACTION_FORWARD, 'worder/groups/showgroup','wordgroupID');
//$section->setDeleteAction(UIComponent::ACTION_FORWARD, 'worder/groups/deletegrouplink', 'wordgroupID');


$column = new UISortColumn("Nimi", "name", "worder/groups/showgrouplist&sort=nimi");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$section->addColumn($column);

$column = new UISortColumn("WordgroupID", "wordgroupID", "worder/groups/showgrouplist");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

//$column = new UIHiddenColumn("Parent", "parentID", "parentID", "parentID");
$column = new UISortColumn("ParentID", "parentID", "worder/groups/showgrouplist");
$column->setColumnType(Column::COLUMNTYPE_INTEGER);
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "wordgroupID", "worder/groups/movegroup&dir=up");
$column->setIcon("fa fa-chevron-up");
$section->addColumn($column);

$column = new UIButtonColumn(UIComponent::ACTION_FORWARD, "wordgroupID", "worder/groups/movegroup&dir=down");
$column->setIcon("fa fa-chevron-down");
$section->addColumn($column);

$section->setData($this->registry->groups);

$section->show();



?>