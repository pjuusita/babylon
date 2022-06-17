<?php

	$section = new UIInsertSection("ThemeItem");
	$section->setInsertAction('admin/theme/insertthemeitem', true);
	$section->setSuccessAction('admin/theme/showthemeitems');		// TODO: Funktiota muutettu
	
	$parentSelectField = new UISelectField("ParentItem","parentID","ParentID",$registry->parents);
	$parentSelectField->setValue(85);
	$nameField = new UIMultiLangTextField("Itemname","itemname","Itemname",$registry->languages);
	$descriptionField = new UIMultiLangTextField("Description","description","Description", $registry->languages);
	
	$section->addField($parentSelectField);
	$section->addField($nameField);
	$section->addField($descriptionField);
	$section->setData($registry->defaultitem);
	$section->show();
		
?>