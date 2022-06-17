<?php

	echo "<a href='".getUrl('admin/cssmanager/showcssfiles')."'>Palaa cssfiles-tauluun</a>";
	$insertSection = new UIInsertSection("Uusi Css-file");
	$insertSection->setOpen(true);
	$insertSection->setInsertAction('admin/cssmanager/insertcssfile');
	$nameField = new UITextField("Name","name","name");
	$insertSection->addField($nameField);
	$insertSection->show();
?>