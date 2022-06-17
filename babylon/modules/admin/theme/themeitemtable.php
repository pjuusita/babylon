<?php
	

$section = new UITreeSection("Themeitems", "600px");
$button = new UIButton(UIComponent::ACTION_OPENDIALOG, null ,'Lisää uusi');
$section->addButton($button);

$column = new UISortColumn("Nimi", "itemname", "worder/theme/showthemeitems");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$section->addColumn($column);

$column = new UISortColumn("Kuvaus", "description", "worder/theme/showthemeitems");
$column->setColumnType(Column::COLUMNTYPE_STRING);
$section->addColumn($column);

$section->setData($registry->themeitems);
$section->show();

	
?>