<?php

echo "<a href='".getUrl('admin/database/showdatabasetables')."'>Palaa tietokantalistaan</a>";

	$insertSection = new UIInsertSection("Uusi Taulu", "600px", true);
	$insertSection->setOpen(true);
	$insertSection->setInsertAction('admin/database/inserttable',true);
	$insertSection->setSuccessAction('admin/database/showdatabasetables');		// TODO: funktiota muutettu
	
	$nameField = new UITextField("Nimi", "name", 'Name');
	$tablenamefield = new UITextField("Luokkanimi", "classname", 'Classname');
	$idfield = new UITextField("ID-Sarakenimi", "idfieldname", 'IDFieldname');
	$tableexistsfield = new UIBooleanField("Oma taulu", "tableexists", 'Tableexists');
	//$tableexistsfield->setBooleanStrings("Arvot systeemitaulussa", "Oma tietokantataulu");
	
	$modulesField = new UISelectField("Moduli","moduleID","moduleID",$registry->modules, "name");
	
	
	$insertSection->addField($nameField);
	//$insertSection->addField($tablenamefield);
	$insertSection->addField($idfield);
	//$insertSection->addField($tableexistsfield);
	$insertSection->addField($modulesField);
	
	$insertSection->show();
?>