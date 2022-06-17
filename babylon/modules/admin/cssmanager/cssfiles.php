<?php
	
	// Korvattu UI Table --> UITableSection
	$cssFilesTable = new UITableSection("CSS-files", "600px");
	//$cssFilesTable->setLineAction(UIComponent::ACTION_FORWARD,'selectRow');
	$cssFilesTable->setData($registry->cssfiles);
	
	$columnName = new UISortColumn("name", "name","");
	$columnName->setLink('admin/cssmanager/showcssfile','cssfileID');
	
	$cssFilesTable->addColumn($columnName);
	$cssFilesTable->addButton("Lisää uusi",  "admin/cssmanager/showinsertcssfile");
	
	$cssFilesTable->show();
?>