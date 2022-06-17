<?php 

	$themeTable = new UITableSection("Teemat","600px");

	$themeTable->setData($this->registry->themes);
	
	$nameColumn = new UISortColumn("Name", "name", "admin/thememanager/showthemes&sort=Name");
	$nameColumn->setLink('admin/thememanager/showtheme','themeID');
	
	$themeTable->addColumn($nameColumn);
	$themeTable->addButton("Lisää","admin/thememanager/showinsertthemepage");
	$themeTable->show();
	
?>