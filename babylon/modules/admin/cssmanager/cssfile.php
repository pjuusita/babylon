<?php

	echo "<h1>CSS-tiedosto - " . $registry->cssfile->name . "</h1>";

	$tiedotSection = new UISection("Tiedot");
	$tiedotSection->setData($registry->cssfile);
	$tiedotSection->setOpen(true);
	$tiedotSection->setUpdateAction(UIComponent::ACTION_FORWARD,'admin/cssmanager/updatecssfile','cssfileID');
	
	$nameField = new UITextField("Nimi","name","name");
	$tiedotSection->addField($nameField);
	$tiedotSection->show();
	
	
	
	// Tiedoston sisältämät css-luokat
	
	// Korvattu UI Table --> UITableSection
	$classesTable = new UITableSection("", "100%");
	$classesTable->innerTable(true);
	$classesTable->setLineaction(UIComponent::ACTION_FORWARD, "admin/cssmanager/showcssclass", 'cssclassID');
	
	
	$nameColumn = new UISortColumn("Nimi", "name", 'Name');
	
	$classesTable->addColumn($nameColumn);
	
	$classesSection = new UISection("CSS-luokat");
	$classesSection->editable(false);
	$classesSection->setOpen(true);
	$classesSection->addButton("Lisää luokka","admin/cssmanager/showinsertcssclass&cssfileid=".$registry->cssfile->getID());
	
	$classesSection->addField($classesTable);
	
	$classesTable->setData($registry->cssclasses);
	$classesSection->show();
	
	
?>