<?php

	$classID = $this->registry->defaultitem->cssclassID;
	echo "<br>ClassID - " . $classID . " - " . count($this->registry->cssclasses);
	//echo "<a href='".getUrl('admin/cssmanager/showcssclass')."&id=".$classID."'>Palaa " . $this->registry->cssclasses[$classID] ."-tauluun</a>";

	$insertSection = new UIInsertSection("Uusi CSS-Definition");
	$insertSection->setOpen(true);
	$insertSection->setInsertAction('admin/cssmanager/insertcssdefinition&id='.$classID);
	$insertSection->setSuccessAction('admin/cssmanager/showcssclass&id='.$classID);		// TODO: Funktiota muutettu
	$insertSection->setData($registry->defaultitem);
	
	$propertyNameField = new UITextField("Propertyname","propertyname","Propertyname");
	$valueField = new UITextField("Value","value","Value");
	$selectThemeItem = new UISelectField("Themeitem","themeitemID","ThemeitemID",$registry->themeitems);
	$selectCSSClass = new UISelectField("CSS-luokka","cssclassID","CssclassID",$registry->cssclasses);
	
	$insertSection->addField($propertyNameField);
	$insertSection->addField($valueField);
	$insertSection->addField($selectThemeItem);
	$insertSection->addField($selectCSSClass);

	$insertSection->show();
	
?>