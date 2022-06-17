<?php
	
	echo "<br>ThemeID - " . $registry->theme->themeID;

	$themeSection = new UISection("Teema");
	$themeSection->setData($registry->theme);
	$themeSection->setUpdateAction(UIComponent::ACTION_FORWARD,"admin/thememanager/updatetheme","themeID");
	
	$nimiField = new UITextField("Nimi","name","Name");
	$themeSection->addField($nimiField);
	
	$themeSection->setOpen(true);
	$themeSection->show();	
	
	// Korvattu UI Table --> UITableSection
	$themeitemtable = new UITableSection("", "100%");
	$themeitemtable->setData($registry->themeitems);
	$themeitemtable->innerTable(true);
	
	
	$nameColumn = new UIColumn("Itemin nimi", "itemname");
	$nameColumn->setLink("admin/thememanager/showthemeitemvalue&themeid=" . $registry->theme->themeID, "themeitemID");
	//$nameColumn = new UIArrayColumn("Itemi", "themeitemID", $this->registry->themeitems, "admin/cssmanager/showitemvalue");
	$valueColumn = new UIArrayColumn("Arvo", "themeitemID", $this->registry->themeitemvalues);
	//$nameLinkString = 'admin/cssmanager/showcssdefinition&cssclassid='.$registry->cssclass->cssclassID;
	//$nameColumn->setLink($nameLinkString,'cssdefinitionID');
	
	$themeitemtable->addColumn($nameColumn);
	$themeitemtable->addColumn($valueColumn);
	//$themeitemtable->show();
	
	// 1.12.2019 Tämä hasAccess on tainnut vanhentua, ei muistikuvaa miten tämä pitäisi toimia
	if (hasAccess("system.thememanager.viewglobalcolors")) {
		$itemsSection = new UISection("Varit");
		foreach($registry->themeitems as $themeitemID => $themeitem) {
			if ($themeitem->publicvalue ==  1) {
				$value = $registry->themeitemvalues[$themeitemID];
				// TODO: 22.10.21 UIColorField-konstruktori lienee vanhentunut
				$itemField = new UIColorField($themeitem->itemname,"name","Name");
				$itemsSection->addField($itemField);
			}
		}
		$itemsSection->setOpen(true);
		$itemsSection->show();
	}
	
	
	$itemsSection = new UISection("Teeman asetukset");
	//$itemsSection->editable(false);
	$itemsSection->setOpen(true);
	$itemsSection->addField($themeitemtable);
	$itemsSection->show();
	
	
	

	
	
	
	
	//*** LISaa NAPPULA *******************************************************************************************
	
	/*
	$lisaaSection = new UISection("Hallinta");
	$perustiedotSection->setData($registry->cssclass);
	$lisaaSection->editable(false);
	$lisaaSection->setOpen(true);
	$lisaaSection->addButton("Lisaa maarittely","admin/cssmanager/aaaaaa&cssclassid=".$registry->cssclass->cssclassID);
	
	$lisaaSection->addField($cssClassesTable);
	
	$lisaaSection->show();
	*/
	/*
	foreach($registry->themeitems as $index => $themeitem) {

		$themeItemSection = new UISection($themeitem->itemname);
		$themeItemSection->setData($themeitem);
		$themeItemSection->setOpen(false);
		$themeItemSection->setUpdateAction(UIComponent::ACTION_FORWARD,"admin/thememanager/updatethemeitem","themeitemID");
		
		$nameField = new UITextField("Name","itemname","Itemname");
		$valueField = new UITextField("Value","value","Value");
		
		$themeItemSection->addField($nameField);
		$themeItemSection->addField($valueField);
		
		$themeItemSection->addButton('Poista','removeThemeItem('.$themeitem->themeID.','.$themeitem->themeitemID.')');
				
		$themeItemSection->show();
	}
	*/
	
	echo "<a href='".getUrl('admin/thememanager/showthemes')."'>Palaa themes-tauluun</a><br>";
	
?>