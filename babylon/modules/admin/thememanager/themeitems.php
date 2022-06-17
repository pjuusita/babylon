<?php

	
	// Korvattu UI Table --> UITableSection
	$themeItemTable = new UITableSection("Themeitems");
	$themeItemTable->setData($this->registry->themeitems);
	
	$IDColumn = new UIFixedColumn("ThemeitemID", "themeitemID");
	$keyColumn = new UISortColumn("Itemkey", "itemkey", "admin/thememanager/showthemeitems&sort=itemname");
	$nameColumn = new UISortColumn("Itemname", "itemname", "admin/thememanager/showthemeitems&sort=itemname");
	//$nameColumn->setLink('admin/thememanager/showthemeitem','themeitemID');
	$descriptionColumn = new UISortColumn("Kuvaus","description","admin/thememanager/showthemeitems&sort=Value");
	$publicColumn = new UISortColumn("Publicvalue", "publicvalue", "admin/thememanager/showthemeitems&sort=itemname");
	
	$themeItemTable->addColumn($IDColumn);
	$themeItemTable->addColumn($keyColumn);
	$themeItemTable->addColumn($nameColumn);
	$themeItemTable->addColumn($descriptionColumn);
	$themeItemTable->addColumn($publicColumn);
	$themeItemTable->addButton("Lisää","admin/thememanager/shownewthemeitem");
	$themeItemTable->setLineAction(UIComponent::ACTION_FORWARD,'admin/thememanager/showthemeitem','themeitemID');
	$themeItemTable->show();
	
?>