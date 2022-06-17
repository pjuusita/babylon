<?php


	// Korvattu UI Table --> UITableSection
	$table = new UITableSection("Tuntilistapohjat", "600px");
	
	$nimicolumn = new UISortColumn("Nimi", "name", "admin/timesheettemplates/showdoctemplates&sort=nimi");
	$nimicolumn->setLink('admin/timesheettemplates/showtimesheettemplate','templateID');
	
	$table->addColumn($nimicolumn);
	$table->addButton("Lisaa uusi",  "admin/timesheettemplates/shownewtimesheettemplate");
	//$table->addButton("PDF",  "utils/pdf/generatetablepdf&table=companies");
	
	$table->setData($this->registry->timesheettemplates);
	$table->show();
	
	
	

?>