<?php

echo "<a href='".getUrl('admin/thememanager/showthemes')."'>Palaa teemat-tauluun</a>";

	$insertSection = new UIInsertSection("Uuden teeman lisays");
	$insertSection->setInsertAction('admin/thememanager/inserttheme');
	
	$nameField = new UITextField("Teeman nimi","name","Name");

	$selectField = new UISelectField("Kayttaja","userID","OwnerID",$registry->kayttajat);

	
	$insertSection->addField($nameField);
	$insertSection->addField($selectField);
	
	$insertSection->show();
	

?>