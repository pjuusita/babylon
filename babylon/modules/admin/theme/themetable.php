<?php 

	// Korvattu UI Table --> UITableSection
	$themeTable = new UITableSection("Teemat","600px");
	
	$nameColumn = new UISortColumn("Name", "name", "admin/theme/showthemetable&sort=Name");
	$nameColumn->setLink('admin/theme/showtheme','themeID');
	$themeTable->addColumn($nameColumn);
	
	//$themeTable->addButton("Lisää","admin/thememanager/showinsertthemepage");
	
	$themeTable->setData($this->registry->themes);
	$themeTable->show();
	
?>