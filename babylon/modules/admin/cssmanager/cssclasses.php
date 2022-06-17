<?php
	
	
	// Korvattu UI Table --> UITableSection
	$table = new UITableSection("CSS-luokat","600px");
	
	$column = new UISortColumn("Name", "name", "admin/cssmanager/showcssclasses&sort=name");
	$table->addColumn($column);
	
	$column = new UISelectColumn("CssFile", "name", "cssfileID", $this->registry->cssfiles, "admin/cssmanager/showcssclasses");
	$table->addColumn($column);
	
	$table->setData($registry->cssclasses);
	$table->show();
	
		
		
	
	
	/*
	// Korvattu UI Table --> UITableSection
	$cssClassesTable = new UITableSection("CSS-luokat", "600px");
	//$cssClassesTable->setLineAction(UIComponent::ACTION_FORWARD,'');
	$cssClassesTable->setSortingColumn($this->registry->sortingcolumn,$this->registry->sortingdirection);
	$cssClassesTable->checkable(true);
	
	$up = getImageUrl('arrowup_32x32.png');
	$down = getImageUrl('arrowdown_32x32.png');
	
	$nameUrl = "admin/cssmanager/showcssclasses&sort=name";
	$nameColumn = new UISortColumn("name", "name", $nameUrl);
	$nameColumn->setLink('admin/cssmanager/showcssclass','cssclassID');
	$nameColumn->setSortIcons($up,$down,10);
	
	// TODO: ei muistikuvaa mika tan selector columnin idea on
	//$selectorColumn = new UISortColumn("selector", "selector", "admin/cssmanager/showcssclasses");
	
	$fileColumn = new UISelectColumn("CssFile", "cssfileID", $registry->cssfiles, "admin/cssmanager/showcssclasses");
	
	$cssClassesTable->addColumn($nameColumn);
	//$cssClassesTable->addColumn($selectorColumn);
	$cssClassesTable->addColumn($fileColumn);
	
	//$testArrayColumnName = new UIFixedColumn("Nimi", "name");
	//$cssClassesTable->addColumn($testArrayColumnName);
	
	$cssClassesTable->addButton("Lisää uusi",  "admin/cssmanager/showinsertcssclass");
	$cssClassesTable->addCustomJavascriptButton("All(test)","selectAllRows()");
	
	// Luodaan url Javascript-funktion (sendSelectedRowIDs) parametriksi. Funktionaalisuuden testaus.
	$actionString = getUrl('admin/cssmanager/showcssclasses');
	$cssClassesTable->addCustomJavascriptButton("Send IDs","sendSelectedRowIDs(\"".$actionString."\")");

	$cssClassesTable->checkable(true);
	$cssClassesTable->setData($registry->cssclasses);

	$cssClassesTable->show();
	*/
?>