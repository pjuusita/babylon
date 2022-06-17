<?php


	// Korvattu UI Table --> UITableSection
	$table = new UITableSection("Dokumenttipohjat", "600px");
	
	$nimicolumn = new UISortColumn("Nimi", "name", "admin/doctemplates/showdoctemplates&sort=nimi");
	$nimicolumn->setLink('admin/doctemplates/showdoctemplate','doctemplateID');
	
	$table->addColumn($nimicolumn);
	$table->addButton("Lisaa uusi",  "admin/doctemplates/shownewdoctemplate");
	//$table->addButton("PDF",  "utils/pdf/generatetablepdf&table=companies");
	
	$table->setData($this->registry->doctemplates);
	$table->show();
	
	
	

?>