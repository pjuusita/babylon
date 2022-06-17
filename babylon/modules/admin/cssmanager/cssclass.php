<?php
	//*** SECTION PERUSTIEDOT ********************************************************************************

	echo "<h1>CSS Class</h1>";

	//echo count($registry->cssclass);
	
	$perustiedotSection = new UISection("Perustiedot");
	$perustiedotSection->setData($registry->cssclass);
	$perustiedotSection->setOpen(true);
	$perustiedotSection->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/cssmanager/updatecssclass','cssclassID');
		
	$nameField = new UITextField("Nimi","name","Name");
	$selectorField = new UITextField("Selector","selector","Selector");
	
	$perustiedotSection->addField($nameField);
	$perustiedotSection->addField($selectorField);
	$perustiedotSection->show();

	echo "<div style='width:100%;height:5px;'></div>";
	
	//*** SECTION MÄÄRITTELYT ********************************************************************************
	
		/*foreach($registry->cssdefinitions as $cssdefinitions => $cssdefinition) {
	
			$cssDefinitionSection = new UISection($cssdefinition->propertyname);
			$cssDefinitionSection->setData($cssdefinition);
			$cssDefinitionSection->setOpen(false);
			$cssDefinitionSection->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/cssmanager/updatecssdefinition','cssdefinitionID');
			
			$cssDefinitionValue = new UITextField($cssdefinition->propertyname,"value","Value");
			$selectThemeItem = new UISelectField("Themeitem","themeitemID","ThemeitemID",$this->registry->themeitems);
			
			$cssDefinitionSection->addField($cssDefinitionValue);
			$cssDefinitionSection->addField($selectThemeItem);
			
			$cssDefinitionSection->addButton("Poista", "admin/cssmanager/removecssdefinition&cssdefinitionid=".$cssdefinition->cssdefinitionID."&cssclassid=".$registry->cssclass->cssclassID);
			
			$cssDefinitionSection->show();

		}*/
		
		
		//*** CSSDEFINITIONS TABLE *************************************************************************************
		
		// Korvattu UI Table --> UITableSection
		$cssClassesTable = new UITableSection("", "100%");
		$cssClassesTable->setData($registry->cssdefinitions);
		$cssClassesTable->innerTable(true);
		$cssClassesTable->checkable(true);
		
		$showStringProperty = "admin/cssmanager/showcssclass&sort=propertyname&id=".$registry->cssclass->cssclassID;
		$showStringValue = "admin/cssmanager/showcssclass&sort=value&id=".$registry->cssclass->cssclassID;
		
		$nameColumn = new UISortColumn("Propertyname", "propertyname", $showStringProperty);

		$nameLinkString = 'admin/cssmanager/showcssdefinition&cssclassid='.$registry->cssclass->cssclassID;
		$nameColumn->setLink($nameLinkString,'cssdefinitionID');
	
		$selectorColumn = new UISortColumn("Value", "value", $showStringValue);
		
		$cssClassesTable->addColumn($nameColumn);
		$cssClassesTable->addColumn($selectorColumn);
		
		//*** LISÄÄ NAPPULA *******************************************************************************************
		
		$lisääSection = new UISection("Attribuutit");
		$perustiedotSection->setData($registry->cssclass);
		$lisääSection->editable(false);
		$lisääSection->setOpen(true);
		$lisääSection->addButton("Lisää property","admin/cssmanager/showinsertcssdefinition&cssclassid=".$registry->cssclass->cssclassID);
		
		$lisääSection->addField($cssClassesTable);
		
		$lisääSection->show();
	
	//*** END PAGE *******************************************************************************************
		
	echo "<script>";
	echo "	function linkclick() {";
	
	echo "		alert('jeejee');";
	echo "		return true;";
	echo "	}";
	echo "</script>";
	
?>
