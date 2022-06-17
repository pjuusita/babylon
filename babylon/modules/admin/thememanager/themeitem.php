<?php

	$themeItemSection = new UISection("Perustiedot");
	$themeItemSection->setData($registry->themeitem);
	$themeItemSection->setOpen(true);
	$themeItemSection->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/thememanager/updatethemeitem','themeitemID');
	
	$keyField = new UITextField("Itemkey","itemkey", "Itemkey");
	$publicField = new UIBooleanField("Public","publicvalue", "Publicvalue");
	$nameField = new UIMultilangTextField("Name","itemname","Itemname",$registry->languages);
	$descriptionField = new UIMultilangTextField("Description","description","Description", $registry->languages);
	
	$themeItemSection->addField($keyField);
	$themeItemSection->addField($publicField);
	$themeItemSection->addField($nameField);
	$themeItemSection->addField($descriptionField);
	
	$button = new UIButton(UIComponent::ACTION_FORWARD, "admin/thememanager/removethemeitem&themeitemID=" . $registry->themeitem->themeitemID, "Poista");
	$themeItemSection->addButton($button);
	
	echo "<a href='".getUrl('admin/thememanager/showthemeitems')."'>Palaa themeitems-tauluun</a><br>";
	
	//************************************************************************************************************************
	// JAVASCRIPTS
	//************************************************************************************************************************
	
	/*
	echo "<script>																													";
	echo "	function removeThemeItem() {																							";
	echo "		window.location='".getUrl('admin/thememanager/removethemeitem')."&themeid=".$this->registry->themeitem->themeID."&themeitemid=".$this->registry->themeitem->themeitemID."';	";
	echo "		return true;																										";
	echo "}																															";
	echo "</script>																													";
	*/
?>