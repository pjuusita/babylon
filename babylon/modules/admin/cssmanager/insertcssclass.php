<?php

echo "<a href='".getUrl('admin/cssmanager/showthemes')."'>Palaa teemat-tauluun</a>";

	$insertSection = new UIInsertSection("Uusi Css-luokka");
	$insertSection->setOpen(true);
	$insertSection->setInsertAction('admin/cssmanager/insertcssclass');
	$insertSection->setSuccessAction('admin/cssmanager/showcssclasses');	// TODO: Funktiota muutettu
	
	$selectCssFile = new UISelectField("CssFile","cssfileID","CssfileID",$registry->cssfiles);
	$nameField = new UITextField("Name","Name","Name");
	
	$insertSection->addField($selectCssFile);
	$insertSection->addField($nameField);
	
	$insertSection->setData($registry->defaultitem);
	$insertSection->show();
?>