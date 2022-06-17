<?php

	echo "<br>";
	$insertsection = new UISection('Asiakkuuden lisäys','550px');
	$insertsection->setDialog(true);
	$insertsection->setMode(UIComponent::MODE_INSERT);
	$insertsection->setSaveAction(UIComponent::ACTION_FORWARD, 'enterprise/contracts/insertcontract');
		
	$invoicetypefield = new UISelectField("Asiakasyritys","companyID","companyID",$registry->companies, "name");
	$insertsection->addField($invoicetypefield);
	
	// TODO: ei toistaiseksi lisätä yhtään oletusmodulia, nämä pitää käydä erikseen lisäämässä...
	//  - ainoastaan system ja admin toiminnot käytössä alussa...
	/*
	$title = "Modulit";
	foreach($registry->modules as $index => $module) {
		if ($module->moduletype == 1) {
			$field = new UIBooleanField($module->name,"module-" . $module->moduleID,"module-" . $module->moduleID);
			$title = "";
			$insertsection->addField($field);
		}
	}
	*/
	$insertsection->show();
	
	
	

	$table = new UITableSection("Asiakkuudet","600px");
	$table->setOpen(true);
	$table->setFramesVisible(false);
	
	$button = new UIButton(UIComponent::ACTION_OPENDIALOG, $insertsection->getID() ,'Lisää uusi palvelusopimus');
	$table->addButton($button);
	
	$table->setLineAction(UIComponent::ACTION_FORWARD,"enterprise/contracts/showcontract","rowID");
	
	$column = new UISortColumn("Asiakas", "name");
	$table->addColumn($column);
	
	$table->setData($registry->contracts);
	$table->show();
	
	
	echo "<br>Count - " . count($registry->contracts);
	
?>