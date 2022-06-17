<?php

	echo "<a href='".getUrl('admin/cssmanager/showcssclass')."&id=".$registry->cssdefinition->cssclassID."'>Palaa ".$registry->cssclass->name."-tauluun</a><br>";

	$cssDefinitionSection = new UISection("CssDefinition");
	//$cssDefinitionSection->editable(false);
	$cssDefinitionSection->setData($registry->cssdefinition);
	$cssDefinitionSection->setOpen(true);
	$cssDefinitionSection->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/cssmanager/updatecssdefinition','cssdefinitionID');
	
	$button = new UIButton(UIComponent::ACTION_FORWARD, "admin/cssmanager/removecssdefinition&id=" .  $registry->cssdefinition->cssdefinitionID, "Poista");
	$cssDefinitionSection->addButton($button);
	
	$propertyNameField = new UITextField("Property name","propertyname","Propertyname");
	$valueField = new UITextField("Value","value","Value");
	
	$classField = new UIFixedTextField("CssClass",$registry->cssclass->name);
	
	$selectThemeItem = new UISelectField("Themeitem","themeitemID","ThemeitemID",$this->registry->themeitems);
	
	$cssDefinitionSection->addField($propertyNameField);
	$cssDefinitionSection->addField($valueField);
	$cssDefinitionSection->addField($classField);
	$cssDefinitionSection->addField($selectThemeItem);
	
	$cssDefinitionSection->show();
	
	echo "<a href='".getUrl('admin/cssmanager/showcssclass')."&id=".$registry->cssdefinition->cssclassID."'>Palaa ".$registry->cssclass->name."-tauluun</a><br>";
?>