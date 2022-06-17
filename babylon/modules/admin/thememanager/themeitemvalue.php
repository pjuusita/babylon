<?php

	$section = new UISection("Perustiedot");
	$section->setData($registry->themeitemvalue);
	$section->setOpen(true);
	//$themeItemSection->setSuccessAction('admin/thememanager/showtheme&id=' . $this->theme->themeID);		// TODO: Funktiota muutettu
	//echo "<br>Themeid - " .  $registry->theme->themeID;
	
	$section->setUpdateAction(UIComponent::ACTION_FORWARD,"admin/thememanager/updatethemeitemvalue&themeid=" . $registry->theme->themeID, "themeitemID");
	
	$themenameField = new UIFixedTextField("Teema",$registry->theme->name);
	$themeitemnameField = new UIFixedTextField("Teema itemi",$registry->themeitem->itemname);
	$valueField = new UITextField("Value","value","Value");
		
	$section->addField($themenameField);
	$section->addField($themeitemnameField);
	$section->addField($valueField);
	$section->show();
	
	echo "<a href='".getUrl("admin/thememanager/showtheme&id=" . $registry->theme->themeID) ."'>Palaa teemaan</a><br>";
	

?>