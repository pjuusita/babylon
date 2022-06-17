<?php

	//print_r($registry->languages);

	$section = new UIInsertSection("ThemeItem");
	$section->setInsertAction('admin/thememanager/insertthemeitem', true);
	$section->setSuccessAction('admin/thememanager/showthemeitems');		// TODO: Funktiota muutettu
	
	$nameField	  = new UIMultiLangTextField("Itemname","itemname",$registry->languages);
	$descriptionField	  = new UIMultiLangTextField("Description","description",$registry->languages);
	
	$section->addField($nameField);
	$section->addField($descriptionField);
	$section->show();
		
?>