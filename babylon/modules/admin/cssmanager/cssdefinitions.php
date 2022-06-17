<?php
	
	// Korvattu UI Table --> UITableSection
	$cssDefinitionTable = new UITableSection("CSS-definitions");

	$up = getImageUrl('arrowup_32x32.png');
	$down = getImageUrl('arrowdown_32x32.png');
	
	$cssDefinitionTable->setSortingColumn($this->registry->sortingcolumn,$this->registry->sortingdirection);
	$cssDefinitionTable->setLineAction(UIComponent::ACTION_JAVASCRIPT, 'rowCheckBoxClicked');
	
	$urlPropertyName = "admin/cssmanager/showcssdefinitions&sort=propertyname";
	$propertyNameColumn = new UISortColumn("propertyname", "propertyname", $urlPropertyName);
	$propertyNameColumn->setLink('admin/cssmanager/showcssdefinition','cssdefinitionID');
	$propertyNameColumn->setSortIcons($up,$down,10);
	
	$urlValue = "admin/cssmanager/showcssdefinitions&sort=value";
	$valueColumn = new UISortColumn("value", "itemstring", $urlValue);
	$valueColumn->setSortIcons($up,$down,10);
	
	$cssDefinitionTable->addColumn($propertyNameColumn);
	$cssDefinitionTable->addColumn($valueColumn);

	$cssDefinitionTable->setData($this->registry->cssdefinitions);
	
	$cssDefinitionTable->addButton("Looppaus",  getUrl("admin/cssmanager/showcssdefinitions"));

	$cssDefinitionTable->show();
?>