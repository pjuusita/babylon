<?php


$themeItemSection = new UIInsertSection("Uusi teemaitem");
$themeItemSection->setOpen(true);
$themeItemSection->setInsertAction('admin/thememanager/insertthemeitemvalue&themeid=' . $registry->theme->themeID . '&themeitemid=' . $registry->themeitem->themeitemID);
$themeItemSection->setSuccessAction('admin/thememanager/showtheme&id=' . $registry->theme->themeID);	// TOOD: Funktiota muutettu
	
$themenameField = new UIFixedTextField("Teema",$registry->theme->name);
$themeitemnameField = new UIFixedTextField("Teema itemi",$registry->themeitem->itemname);
$valueField = new UITextField("Value","value","Value");

$themeItemSection->addField($themenameField);
$themeItemSection->addField($themeitemnameField);
$themeItemSection->addField($valueField);

$themeItemSection->show();

echo "<a href='".getUrl("admin/thememanager/showtheme&id=" . $registry->theme->themeID) ."'>Palaa teemaan</a><br>";





?>