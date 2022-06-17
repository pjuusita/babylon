<?php


	echo "<br>Jeejee";

	$themeItemSection = new UISection("Perustiedot");
	$themeItemSection->setData($registry->themeitem);
	$themeItemSection->setOpen(true);
	$themeItemSection->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/theme/updatethemeitem','themeitemID');
	
	$keyField = new UITextField("Itemkey","itemkey", "Itemkey");
	$publicField = new UIBooleanField("Public","publicvalue", "Publicvalue");
	$nameField = new UIMultilangTextField("Name","itemname","Itemname",$registry->languages);
	$descriptionField = new UIMultilangTextField("Description","description","Description", $registry->languages);
	
	//$themeItemSection->addField($keyField);
	$themeItemSection->addField($nameField);
	$themeItemSection->addField($descriptionField);
	$themeItemSection->addField($publicField);
	
	$button = new UIButton(UIComponent::ACTION_FORWARD, "admin/theme/removethemeitem&themeitemID=" . $registry->themeitem->themeitemID, "Poista");
	$themeItemSection->addButton($button);

	$themeItemSection->show();
	
	echo "<a href='".getUrl('admin/theme/showthemeitems')."'>Palaa themeitems-tauluun</a><br>";

?>